<?php

namespace RedTec\Contacto;

use RedTec\SEO\StructuredDataBuilder;

/**
 * Controlador de la Página de Contacto Institucional
 */
class ContactoController
{
    /**
     * Muestra la página de contacto con formulario e información de atención.
     */
    public function index(): void
    {
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => '/'],
            ['name' => 'Contacto', 'url' => '/contacto']
        ];

        $jsonLdData = [
            StructuredDataBuilder::buildLocalBusiness(),
            StructuredDataBuilder::buildBreadcrumbList($breadcrumbItems)
        ];

        $pageTitle       = "Contacto — RedTec Informática | Atlántida, Canelones";
        $pageDescription = "Comunicate con RedTec Informática en Atlántida, Canelones, Uruguay. Consultas sobre productos, servicio técnico, cámaras de seguridad y redes.";
        $currentPage     = "contacto";
        $canonicalUrl    = absolute_url('/contacto');

        require __DIR__ . '/views/contacto.php';
    }

    /**
     * Procesa el formulario de contacto y redirige a WhatsApp con la consulta formateada.
     */
    public function enviar(): void
    {
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $asunto   = trim($_POST['asunto'] ?? 'Consulta desde la web');
        $mensaje  = trim($_POST['mensaje'] ?? '');

        if (empty($nombre) || empty($mensaje)) {
            $_SESSION['flash_error'] = 'Por favor complete su nombre y mensaje.';
            header('Location: ' . url('/contacto'));
            exit;
        }

        // Construir mensaje amigable para WhatsApp
        $text  = "Hola RedTec! 👋 Quisiera hacer una consulta:\n\n";
        $text .= "👤 *Nombre:* {$nombre}\n";
        if (!empty($email)) {
            $text .= "✉️ *Email:* {$email}\n";
        }
        if (!empty($telefono)) {
            $text .= "📞 *Teléfono:* {$telefono}\n";
        }
        $text .= "📌 *Asunto:* {$asunto}\n\n";
        $text .= "💬 *Mensaje:*\n{$mensaje}";

        $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode($text);

        header('Location: ' . $waLink);
        exit;
    }
}
