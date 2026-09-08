<?php

namespace RedTec\Checkout;

use Throwable;

/**
 * Controlador del Proceso de Checkout y Creación de Preferencias de Mercado Pago
 */
class CheckoutController
{
    /**
     * Muestra la vista principal de checkout.
     */
    public function index(): void
    {
        $pageTitle       = "Finalizar Pedido — RedTec Informática";
        $pageDescription = "Elegí tu forma de pago favorita: Mercado Pago (tarjeta de crédito/débito o redes de cobranza) o coordiná directo por WhatsApp con RedTec Informática.";
        $currentPage     = "checkout";
        $metaRobots      = "noindex, nofollow";
        $canonicalUrl    = absolute_url('/checkout');

        require __DIR__ . '/views/checkout.php';
    }

    /**
     * Endpoint AJAX POST para procesar el pedido con Mercado Pago y generar la preferencia.
     */
    public function crearPreferencia(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (empty($input)) {
            $input = $_POST;
        }

        $name    = trim($input['name'] ?? '');
        $email   = trim($input['email'] ?? '');
        $phone   = trim($input['phone'] ?? '');
        $address = trim($input['address'] ?? '');
        $notes   = trim($input['notes'] ?? '');
        $items   = $input['items'] ?? [];

        if (empty($name) || empty($phone) || empty($address)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Por favor complete todos los datos obligatorios (Nombre, Teléfono y Dirección).']);
            exit;
        }

        if (empty($items) || !is_array($items)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Tu carrito está vacío. Agregá productos antes de continuar.']);
            exit;
        }

        try {
            $orderRepo = new OrderRepository();
            $clienteData = [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'address' => $address,
                'notes'   => $notes
            ];

            // 1. Crear el pedido interno en la base de datos MySQL con precios revalidados desde BD
            $resultadoPedido = $orderRepo->crearPedido($clienteData, $items, 'mercadopago');
            $orderId         = $resultadoPedido['order_id'];
            $itemsValidados  = $resultadoPedido['items'];

            // 2. Formatear los ítems para la API de Mercado Pago
            $mpItems = [];
            foreach ($itemsValidados as $iv) {
                $mpItems[] = [
                    'id'          => (string)$iv['product_id'],
                    'title'       => $iv['product_name'],
                    'description' => "Código: " . $iv['product_code'],
                    'quantity'    => (int)$iv['quantity'],
                    'currency_id' => 'UYU',
                    'unit_price'  => (float)$iv['price']
                ];
            }

            // 3. URLs absolutas de retorno y webhook
            $successUrl = absolute_url('/checkout/resultado?status=success&order_id=' . $orderId);
            $pendingUrl = absolute_url('/checkout/resultado?status=pending&order_id=' . $orderId);
            $failureUrl = absolute_url('/checkout/resultado?status=failure&order_id=' . $orderId);
            $webhookUrl = absolute_url('/pagos/webhook-mercadopago');

            // 4. Armar Payload de Preferencia de Mercado Pago
            $preferencePayload = [
                'items' => $mpItems,
                'payer' => [
                    'name'    => $name,
                    'email'   => !empty($email) ? $email : 'cliente_' . $orderId . '@redtecinformatica.com',
                    'phone'   => [
                        'number' => $phone
                    ],
                    'address' => [
                        'street_name' => $address
                    ]
                ],
                'external_reference' => (string)$orderId,
                'back_urls' => [
                    'success' => $successUrl,
                    'pending' => $pendingUrl,
                    'failure' => $failureUrl
                ],
                'auto_return' => 'approved',
                'notification_url' => $webhookUrl,
                'statement_descriptor' => 'REDTEC INFORMATICA'
            ];

            // 5. Invocar servicio REST API de Mercado Pago
            $mpService = new MercadoPagoService();
            $preference = $mpService->createPreference($preferencePayload);

            $prefId   = $preference['id'] ?? '';
            $env      = $mpService->getEnvironment();
            // Seleccionar init_point según el entorno (sandbox vs producción)
            $initPoint = ($env === 'test' && !empty($preference['sandbox_init_point'])) 
                ? $preference['sandbox_init_point'] 
                : ($preference['init_point'] ?? '');

            if (empty($prefId) || empty($initPoint)) {
                throw new \Exception("No se pudo obtener el enlace de pago de Mercado Pago.");
            }

            // 6. Vincular la preferencia al pedido en BD
            $orderRepo->vincularPreferencia($orderId, $prefId);

            echo json_encode([
                'status'     => 'success',
                'order_id'   => $orderId,
                'init_point' => $initPoint
            ]);
            exit;

        } catch (Throwable $e) {
            error_log("Error en CheckoutController::crearPreferencia: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Endpoint AJAX POST para registrar un pedido finalizado vía WhatsApp.
     */
    public function crearPedidoWhatsapp(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (empty($input)) {
            $input = $_POST;
        }

        $name    = trim($input['name'] ?? '');
        $email   = trim($input['email'] ?? '');
        $phone   = trim($input['phone'] ?? '');
        $address = trim($input['address'] ?? '');
        $notes   = trim($input['notes'] ?? '');
        $items   = $input['items'] ?? [];

        if (empty($name) || empty($phone) || empty($address)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Por favor complete todos los datos obligatorios.']);
            exit;
        }

        try {
            $orderRepo = new OrderRepository();
            $clienteData = [
                'name'    => $name,
                'email'   => $email,
                'phone'   => $phone,
                'address' => $address,
                'notes'   => $notes
            ];

            $resultado = $orderRepo->crearPedido($clienteData, $items, 'whatsapp');

            echo json_encode([
                'status'   => 'success',
                'order_id' => $resultado['order_id']
            ]);
            exit;
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    /**
     * Muestra la página de resultado post-checkout.
     */
    public function resultado(): void
    {
        $rawStatus = strtolower(trim($_GET['status'] ?? ($_GET['collection_status'] ?? '')));
        $paymentId = trim($_GET['payment_id'] ?? ($_GET['collection_id'] ?? ''));
        $orderIdInput = (int)($_GET['order_id'] ?? ($_GET['external_reference'] ?? 0));

        $orderRepo = new OrderRepository();
        $pedido    = $orderIdInput > 0 ? $orderRepo->obtenerPorId($orderIdInput) : null;

        // Si viene ID de Pago y el pedido aún no figura como pagado en BD, revalidar contra la API REST de MP
        if ($pedido && $pedido['status'] !== 'pagado' && !empty($paymentId)) {
            try {
                $mpService = new MercadoPagoService();
                $paymentInfo = $mpService->getPayment($paymentId);
                $mpRealStatus = strtolower($paymentInfo['status'] ?? '');
                $merchantOrderId = (string)($paymentInfo['order']['id'] ?? ($_GET['merchant_order_id'] ?? ''));

                if ($mpRealStatus === 'approved') {
                    $orderRepo->marcarComoPagado($pedido['id'], $paymentId, $merchantOrderId);
                    $pedido = $orderRepo->obtenerPorId($pedido['id']); // Recargar pedido

                    // Enviar email de confirmación
                    $mailService = new MailService();
                    $mailService->enviarConfirmacionCompra($pedido);
                } elseif (in_array($mpRealStatus, ['rejected', 'cancelled', 'refunded', 'charged_back'], true)) {
                    $orderRepo->marcarComoFallido($pedido['id']);
                    $pedido = $orderRepo->obtenerPorId($pedido['id']);
                }
            } catch (Throwable $e) {
                error_log("Error al revalidar pago en resultado(): " . $e->getMessage());
            }
        }

        // Determinar el estado para la vista ('success', 'pending', 'failure')
        $orderStatusInDb = $pedido ? strtolower($pedido['status']) : '';

        if ($orderStatusInDb === 'pagado' || in_array($rawStatus, ['approved', 'success', 'accredited'], true)) {
            $statusEstado = 'success';
        } elseif ($orderStatusInDb === 'pendiente_pago' || in_array($rawStatus, ['pending', 'in_process', 'in_mediation'], true)) {
            $statusEstado = 'pending';
        } else {
            $statusEstado = 'failure';
        }

        $orderNumber = $pedido ? sprintf('#%05d', $pedido['id']) : '';

        $pageTitle       = "Resultado del Pedido — RedTec Informática";
        $pageDescription = "Estado del pago de tu pedido en RedTec Informática.";
        $currentPage     = "checkout-resultado";
        $metaRobots      = "noindex, nofollow";

        require __DIR__ . '/views/resultado.php';
    }
}
