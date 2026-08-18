<?php

/**
 * RedTec Informática - Configuración General del Sitio, Entorno y Manejo de Errores
 */

// Detección de Entorno (Local vs Producción)
if (!defined('IS_LOCAL')) {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (
        strpos($host, 'localhost') !== false ||
        strpos($host, '127.0.0.1') !== false ||
        strpos($host, '::1') !== false ||
        substr($host, -6) === '.local' ||
        substr($host, -5) === '.test'
    );
    define('IS_LOCAL', $isLocal);
    define('APP_ENV', IS_LOCAL ? 'local' : 'production');
}

// Configuración de Manejo de Errores según Entorno
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

// Umbral de Alerta de Stock Bajo para el Panel de Administración
if (!defined('LOW_STOCK_THRESHOLD')) {
    define('LOW_STOCK_THRESHOLD', 5);
}

// Verificación de Google Search Console (vacío hasta la fase de publicación/despliegue)
if (!defined('GOOGLE_SITE_VERIFICATION')) {
    define('GOOGLE_SITE_VERIFICATION', '');
}

// Número y Enlace de WhatsApp
if (!defined('REDTEC_WHATSAPP_NUMBER')) {
    define('REDTEC_WHATSAPP_NUMBER', '59899000000'); // TODO: reemplazar por el número real de RedTec
}

if (!defined('REDTEC_WHATSAPP_LINK')) {
    define('REDTEC_WHATSAPP_LINK', 'https://wa.me/' . REDTEC_WHATSAPP_NUMBER);
}

/**
 * Función helper global para generar URLs adaptativas.
 * Funciona automáticamente tanto en servidor local (con subdirectorios como /RedTec/public/)
 * como en producción sobre el hosting compartido de one.com (en la raíz /).
 * 
 * @param string $path Ruta relativa comenzando con / (ej: '/assets/css/base.css', '/tienda')
 * @return string URL adaptada al entorno.
 */
if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        static $basePath = null;

        if ($basePath === null) {
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            if ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\') {
                $basePath = '';
            } else {
                $basePath = rtrim($scriptDir, '/');
            }
        }

        if ($path === '' || $path === '/') {
            return $basePath === '' ? '/' : $basePath . '/';
        }

        $cleanPath = '/' . ltrim($path, '/');
        return $basePath . $cleanPath;
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
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . url($path);
    }
}
