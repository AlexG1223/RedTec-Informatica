<?php

namespace RedTec\Checkout;

/**
 * Controlador para la vista de Checkout / Finalización de Pedido
 */
class CheckoutController
{
    /**
     * Muestra la página de checkout.
     */
    public function index(): void
    {
        $pageTitle       = "Finalizar Pedido — RedTec Informática";
        $pageDescription = "Completá tus datos de contacto para enviar tu pedido directamente por WhatsApp a nuestros asesores técnicos en Atlántida.";
        $currentPage     = 'checkout';

        require __DIR__ . '/views/checkout.php';
    }
}
