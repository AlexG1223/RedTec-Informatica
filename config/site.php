<?php

/**
 * RedTec Informática - Configuración General del Sitio y Detección de Entorno
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
