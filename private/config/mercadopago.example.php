<?php

/**
 * RedTec Informática - Configuración de Mercado Pago
 * 
 * Plantilla de credenciales para integración Mercado Pago Checkout Pro.
 * Copiar a 'mercadopago.php' y configurar según el entorno.
 */

return [
    // Entorno: 'test' (Sandbox) o 'production' (Producción Real)
    'environment' => 'test',

    // Credenciales Sandbox (Test)
    'test' => [
        'public_key'     => 'TEST-xxx',
        'access_token'   => 'TEST-xxx',
        'client_id'      => '',
        'client_secret'  => '',
        'webhook_secret' => '',
    ],

    // Credenciales Producción (Real)
    'production' => [
        'public_key'     => 'APP_USR-xxx',
        'access_token'   => 'APP_USR-xxx',
        'client_id'      => '',
        'client_secret'  => '',
        'webhook_secret' => '',
    ],
];
