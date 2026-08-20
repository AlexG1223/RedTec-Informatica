<?php

/**
 * RedTec Informática - Configuración General del Sitio, Entorno y Manejo de Errores (Producción)
 */

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
    
    $logDir = __DIR__ . '/../logs';
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

// Número y Enlace de WhatsApp Centralizado de RedTec Informática
if (!defined('REDTEC_WHATSAPP_NUMBER')) {
    define('REDTEC_WHATSAPP_NUMBER', '59891633699');
}

if (!defined('REDTEC_WHATSAPP_LINK')) {
    define('REDTEC_WHATSAPP_LINK', 'https://wa.me/' . REDTEC_WHATSAPP_NUMBER);
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
