<?php

namespace RedTec\Checkout;

use Throwable;

/**
 * Controlador para la recepción y procesamiento de Webhooks e IPN de Mercado Pago
 */
class WebhookController
{
    /**
     * Procesa la notificación POST enviada por Mercado Pago.
     */
    public function procesar(): void
    {
        // Asegurar encabezado de respuesta JSON y HTTP status 200 OK
        header('Content-Type: application/json; charset=utf-8');

        $logFile = REDTEC_PRIVATE_DIR . '/logs/mercadopago_webhook.log';
        $logDir  = dirname($logFile);
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        $rawInput = file_get_contents('php_input') ?: file_get_contents('php://input');
        $bodyData = json_decode($rawInput, true) ?: [];

        $xSignature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
        $xRequestId = $_SERVER['HTTP_X_REQUEST_ID'] ?? '';

        // Extraer ID de Pago desde Body o Query Parameters
        $paymentId = $bodyData['data']['id'] ?? ($bodyData['id'] ?? ($_GET['id'] ?? ($_GET['data_id'] ?? ($_GET['data_id'] ?? ''))));
        $type      = $bodyData['type'] ?? ($bodyData['action'] ?? ($_GET['topic'] ?? ($_GET['type'] ?? '')));

        @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "NOTIFICATION: PaymentId={$paymentId}, Type={$type}, IP={$_SERVER['REMOTE_ADDR']}\n", FILE_APPEND);

        // Si no se proporcionó ID de pago o el tipo no corresponde a pago, responder OK y finalizar
        if (empty($paymentId)) {
            http_response_code(200);
            echo json_encode(['status' => 'ok', 'message' => 'Notificación recibida sin ID de pago']);
            exit;
        }

        try {
            $mpService = new MercadoPagoService();

            // Validar firma si hay secreta configurada
            if (!$mpService->verifyWebhookSignature($xSignature, $xRequestId, (string)$paymentId)) {
                @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "WARN: Firma de webhook inválida para PaymentId={$paymentId}\n", FILE_APPEND);
            }

            // Consultar el estado REAL del pago contra la API REST de Mercado Pago (Server-to-Server)
            $paymentInfo = $mpService->getPayment((string)$paymentId);

            $status            = $paymentInfo['status'] ?? '';
            $externalReference = $paymentInfo['external_reference'] ?? '';
            $merchantOrderId   = (string)($paymentInfo['order']['id'] ?? '');

            @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "PAYMENT DETAILS: PaymentId={$paymentId}, OrderId={$externalReference}, Status={$status}\n", FILE_APPEND);

            $orderId = (int)$externalReference;

            if ($orderId <= 0) {
                http_response_code(200);
                echo json_encode(['status' => 'ok', 'message' => 'Sin referencia de pedido interno']);
                exit;
            }

            $orderRepo = new OrderRepository();
            $pedido    = $orderRepo->obtenerPorId($orderId);

            if (!$pedido) {
                @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "ERROR: Pedido ID {$orderId} no encontrado en base de datos\n", FILE_APPEND);
                http_response_code(200);
                echo json_encode(['status' => 'ok', 'message' => 'Pedido no encontrado']);
                exit;
            }

            // Procesamiento según el estado auténtico del pago
            switch ($status) {
                case 'approved':
                    // Marcar como pagado, descontar stock de BD y enviar email si no estaba pagado previamente
                    if ($pedido['status'] !== 'pagado') {
                        $exito = $orderRepo->marcarComoPagado($orderId, (string)$paymentId, $merchantOrderId);
                        if ($exito) {
                            @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "SUCCESS: Pedido {$orderId} marcado como PAGADO. Stock descontado.\n", FILE_APPEND);
                            
                            // Reobtener pedido actualizado y enviar email
                            $pedidoActualizado = $orderRepo->obtenerPorId($orderId);
                            $mailService = new MailService();
                            $mailService->enviarConfirmacionCompra($pedidoActualizado);
                        }
                    }
                    break;

                case 'rejected':
                case 'cancelled':
                case 'refunded':
                case 'charged_back':
                    if ($pedido['status'] !== 'pagado') {
                        $orderRepo->marcarComoFallido($orderId);
                        @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "INFO: Pedido {$orderId} marcado como FALLIDO/CANCELADO ({$status}). Reserva liberada.\n", FILE_APPEND);
                    }
                    break;

                case 'pending':
                case 'in_process':
                default:
                    // Dejar en estado pendiente_pago
                    @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "INFO: Pedido {$orderId} en estado PENDIENTE ({$status}).\n", FILE_APPEND);
                    break;
            }

            http_response_code(200);
            echo json_encode(['status' => 'ok', 'order_id' => $orderId, 'payment_status' => $status]);
            exit;

        } catch (Throwable $e) {
            @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);

            // Devolver SIEMPRE 200 OK a Mercado Pago para evitar loops infinitos de notificaciones
            http_response_code(200);
            echo json_encode(['status' => 'ok', 'error' => $e->getMessage()]);
            exit;
        }
    }
}
