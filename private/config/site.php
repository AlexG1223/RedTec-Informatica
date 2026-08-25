<?php

/**
 * RedTec Informática - Configuración General del Sitio, Entorno y Rutas Absolutas (Producción)
 */

// Definición de Rutas Absolutas a la Carpeta Privada y Componentes Compartidos
if (!defined('REDTEC_PRIVATE_DIR')) {
    define('REDTEC_PRIVATE_DIR', dirname(__DIR__));
}

if (!defined('REDTEC_SHARED_DIR')) {
    define('REDTEC_SHARED_DIR', REDTEC_PRIVATE_DIR . '/shared');
}

// Detección de Entorno
if (!defined('IS_LOCAL')) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    define('IS_LOCAL', $isLocal);
    define('APP_ENV', IS_LOCAL ? 'local' : 'production');
}

// Configuración de Manejo de Errores (Producción)
if (IS_LOCAL) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('log_errors', '1');
    
    $logDir = REDTEC_PRIVATE_DIR . '/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    ini_set('error_log', $logDir . '/php_error.log');
}

// Umbral de Alerta de Stock Bajo
if (!defined('LOW_STOCK_THRESHOLD')) {
    define('LOW_STOCK_THRESHOLD', 5);
}

// Google Search Console
if (!defined('GOOGLE_SITE_VERIFICATION')) {
    define('GOOGLE_SITE_VERIFICATION', '');
}

// Número y Enlace de WhatsApp Centralizado (099 372 649 -> 59899372649)
if (!defined('REDTEC_WHATSAPP_NUMBER')) {
    define('REDTEC_WHATSAPP_NUMBER', '59899372649');
}

if (!defined('REDTEC_WHATSAPP_DISPLAY')) {
    define('REDTEC_WHATSAPP_DISPLAY', '099 372 649');
}

if (!defined('REDTEC_WHATSAPP_LINK')) {
    define('REDTEC_WHATSAPP_LINK', 'https://wa.me/' . REDTEC_WHATSAPP_NUMBER);
}

// Teléfono Fijo Oficial (4371 6456)
if (!defined('REDTEC_PHONE_FIJO')) {
    define('REDTEC_PHONE_FIJO', '4371 6456');
}

if (!defined('REDTEC_PHONE_FIJO_LINK')) {
    define('REDTEC_PHONE_FIJO_LINK', 'tel:43716456');
}

// Redes Sociales Oficiales (Instagram y Threads)
if (!defined('REDTEC_INSTAGRAM_URL')) {
    define('REDTEC_INSTAGRAM_URL', 'https://www.instagram.com/redtec_atlantida/');
}

if (!defined('REDTEC_THREADS_URL')) {
    define('REDTEC_THREADS_URL', 'https://www.threads.com/@redtec_atlantida?xmt=AQG03DkuYO9-ubC6GdZPbaFjmF_4GDHd7rEP55gFRuuH9-I');
}

/**
 * Helper global ultra-rápido para generar URLs relativas limpias.
 * 
 * @param string $path Ruta relativa (ej: '/assets/css/base.css', '/tienda')
 * @return string URL adaptada sin sobrecarga de runtime.
 */
if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        if ($path === '' || $path === '/') {
            return '/';
        }
        return '/' . ltrim($path, '/');
    }
}

/**
 * Obtiene la URL absoluta completa (con protocolo y host).
 * 
 * @param string $path
 * @return string
 */
if (!function_exists('absolute_url')) {
    function absolute_url(string $path = ''): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'redtecinformatica.com';
        return $scheme . '://' . $host . url($path);
    }
}
