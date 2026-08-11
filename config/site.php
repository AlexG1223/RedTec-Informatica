<?php

/**
 * RedTec Informática - Configuración General del Sitio
 */

// TODO: Reemplazar con el número real de WhatsApp de RedTec cuando el cliente (Michael) lo confirme
if (!defined('REDTEC_WHATSAPP_NUMBER')) {
    define('REDTEC_WHATSAPP_NUMBER', '59899000000');
}

if (!defined('REDTEC_WHATSAPP_LINK')) {
    define('REDTEC_WHATSAPP_LINK', 'https://wa.me/' . REDTEC_WHATSAPP_NUMBER);
}
