<?php

/**
 * RedTec Informática - Endpoint Público de Webhook / Notificaciones IPN de Mercado Pago
 * 
 * URL Pública Oficial: https://redtecinformatica.com/pagos/webhook-mercadopago
 */

// Cargar configuración general del sitio
require_once __DIR__ . '/../../private/config/site.php';

// Autoloader PSR-4 para clases internas
spl_autoload_register(function ($class) {
    $prefixes = [
        'RedTec\\Shared\\'   => __DIR__ . '/../../private/shared/',
        'RedTec\\Checkout\\' => __DIR__ . '/../../private/src/Checkout/',
        'RedTec\\Admin\\'    => __DIR__ . '/../../private/src/Admin/',
        'RedTec\\'           => __DIR__ . '/../../private/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

// Invocar controlador del webhook
$controller = new \RedTec\Checkout\WebhookController();
$controller->procesar();
