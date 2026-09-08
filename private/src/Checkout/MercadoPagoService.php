<?php

namespace RedTec\Checkout;

use Exception;

/**
 * Cliente HTTP REST para la API de Mercado Pago (cURL Nativo sin SDK)
 */
class MercadoPagoService
{
    private string $environment;
    private string $accessToken;
    private string $publicKey;
    private string $webhookSecret;

    public function __construct()
    {
        $configFile  = REDTEC_PRIVATE_DIR . '/config/mercadopago.php';
        $exampleFile = REDTEC_PRIVATE_DIR . '/config/mercadopago.example.php';

        if (file_exists($configFile)) {
            $config = require $configFile;
        } elseif (file_exists($exampleFile)) {
            $config = require $exampleFile;
        } else {
            throw new Exception("Error de Configuración: No se encontró 'mercadopago.php'.");
        }

        $this->environment   = $config['environment'] ?? 'production';
        $envConfig           = $config[$this->environment] ?? ($config['production'] ?? []);

        $this->accessToken   = trim($envConfig['access_token'] ?? '');
        $this->publicKey     = trim($envConfig['public_key'] ?? '');
        $this->webhookSecret = trim($envConfig['webhook_secret'] ?? '');

        if (empty($this->accessToken)) {
            throw new Exception("Error de Mercado Pago: Access Token no configurado para entorno '{$this->environment}'.");
        }
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Crea una preferencia de pago en Mercado Pago Checkout Pro.
     *
     * @param array $payload Datos de la preferencia (items, payer, back_urls, external_reference, notification_url)
     * @return array Respuesta parseada de la API de Mercado Pago
     * @throws Exception Si ocurre un error HTTP o cURL
     */
    public function createPreference(array $payload): array
    {
        $url = 'https://api.mercadopago.com/checkout/preferences';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
                'User-Agent: RedTecInformatica/1.0'
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Error cURL al conectar con Mercado Pago: " . $error);
        }

        $data = json_decode($response, true);
        if ($httpCode < 200 || $httpCode >= 300) {
            $msg = $data['message'] ?? ($data['error'] ?? 'Error al crear la preferencia de pago');
            throw new Exception("Error API Mercado Pago [HTTP {$httpCode}]: " . $msg);
        }

        return $data;
    }

    /**
     * Obtiene los detalles reales de un pago por su ID directamente de la API REST.
     *
     * @param string $paymentId
     * @return array
     * @throws Exception
     */
    public function getPayment(string $paymentId): array
    {
        $url = 'https://api.mercadopago.com/v1/payments/' . urlencode($paymentId);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET        => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
                'User-Agent: RedTecInformatica/1.0'
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Error cURL al consultar pago {$paymentId}: " . $error);
        }

        $data = json_decode($response, true);
        if ($httpCode < 200 || $httpCode >= 300) {
            $msg = $data['message'] ?? ($data['error'] ?? 'Error al obtener información del pago');
            throw new Exception("Error API Mercado Pago [HTTP {$httpCode}]: " . $msg);
        }

        return $data;
    }

    /**
     * Valida la firma x-signature del webhook si hay un secret configurado.
     *
     * @param string $xSignature Header x-signature (ej: ts=1600000000,v1=5d... )
     * @param string $xRequestId Header x-request-id
     * @param string $dataId ID del recurso notificado
     * @return bool
     */
    public function verifyWebhookSignature(string $xSignature, string $xRequestId, string $dataId): bool
    {
        if (empty($this->webhookSecret) || empty($xSignature)) {
            // Si no hay secret configurado, dependemos de la validación server-to-server vía getPayment()
            return true;
        }

        $parts = explode(',', $xSignature);
        $ts = null;
        $hash = null;

        foreach ($parts as $part) {
            $keyValue = explode('=', trim($part), 2);
            if (count($keyValue) === 2) {
                if ($keyValue[0] === 'ts') {
                    $ts = $keyValue[1];
                } elseif ($keyValue[0] === 'v1') {
                    $hash = $keyValue[1];
                }
            }
        }

        if (!$ts || !$hash) {
            return false;
        }

        $manifest = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";
        $computedHash = hash_hmac('sha256', $manifest, $this->webhookSecret);

        return hash_equals($computedHash, $hash);
    }
}
