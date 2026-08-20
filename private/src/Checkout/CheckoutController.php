<?php

namespace RedTec\Checkout;

/**
 * Controlador de Checkout y Confirmación de Pedido por WhatsApp
 */
class CheckoutController
{
    /**
     * Muestra la vista de checkout para finalizar la compra.
     */
    public function index(): void
    {
        $pageTitle       = "Finalizar Pedido — RedTec Informática";
        $pageDescription = "Confirmá tu pedido de productos y coordiná la entrega y pago directo por WhatsApp con RedTec Informática.";
        $currentPage     = "checkout";
        $metaRobots      = "noindex, nofollow"; // No indexar páginas del flujo de checkout
        $canonicalUrl    = absolute_url('/checkout');

        require __DIR__ . '/views/checkout.php';
    }
}
