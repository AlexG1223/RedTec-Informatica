<?php

namespace RedTec\Contacto;

/**
 * Controlador de la Página de Contacto
 */
class ContactoController
{
    /**
     * Muestra la vista de formulario y mapa de contacto.
     */
    public function index(): void
    {
        $pageTitle       = "Contacto — RedTec Informática en Atlántida";
        $pageDescription = "Comunicate con RedTec Informática. Local comercial en Atlántida, Canelones, Uruguay. Asesoramiento técnico y cotizaciones por WhatsApp.";
        $currentPage     = "contacto";
        $canonicalUrl    = absolute_url('/contacto');

        require __DIR__ . '/views/contacto.php';
    }

    /**
     * Procesa la solicitud de contacto directa.
     */
    public function enviar(): void
    {
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $mensaje  = trim($_POST['mensaje'] ?? '');

        if (!empty($mensaje)) {
            $waText = "Hola RedTec, mi nombre es {$nombre} ({$telefono}). {$mensaje}";
            header('Location: ' . REDTEC_WHATSAPP_LINK . '?text=' . urlencode($waText));
            exit;
        }

        header('Location: ' . url('/contacto'));
        exit;
    }
}
