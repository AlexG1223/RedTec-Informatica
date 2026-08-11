<?php
/**
 * RedTec Informática - Partial Cart Drawer (Panel Deslizante del Carrito)
 */
?>
<!-- Fondo Desenfocado (Backdrop) -->
<div class="cart-drawer-backdrop" id="cartDrawerBackdrop"></div>

<!-- Panel Lateral Deslizante -->
<aside class="cart-drawer" id="cartDrawer" aria-label="Carrito de compras">
  
  <!-- Encabezado del Drawer -->
  <div class="cart-drawer-header">
    <h3>
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      Tu Carrito de Compras
    </h3>
    <button type="button" class="cart-drawer-close" id="cartDrawerClose" aria-label="Cerrar carrito">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>

  <!-- Cuerpo / Contenido del Carrito -->
  <div class="cart-drawer-body">
    
    <!-- Lista de Items (Se puebla dinámicamente con CartUI.js) -->
    <div class="cart-drawer-items" id="cartDrawerItems"></div>

    <!-- Estado Vacío -->
    <div id="cartDrawerEmpty" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; flex-grow: 1; padding: 2rem;">
      <div style="width: 64px; height: 64px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      </div>
      <h4 style="margin-bottom: 0.5rem; color: var(--color-dark);">Tu carrito está vacío</h4>
      <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
        ¡Explorá nuestro catálogo de productos y sumá los artículos que necesites!
      </p>
      <a href="<?= url('/tienda') ?>" class="btn btn-primary btn-sm" onclick="if(window.CartUI) window.CartUI.closeDrawer();">
        Ver Productos
      </a>
    </div>

  </div>

  <!-- Pie del Drawer (Subtotal y Checkout) -->
  <div class="cart-drawer-footer" id="cartDrawerFooter" style="display: none;">
    
    <div class="cart-notice">
      <strong>Información de envío:</strong> El costo de envío y el método de pago se coordinan directamente por WhatsApp al finalizar el pedido.
    </div>

    <div class="cart-summary-row">
      <span style="font-weight: 600; color: var(--color-dark);">Subtotal estimado:</span>
      <span class="cart-summary-total">USD $<span id="cartDrawerSubtotal">0.00</span></span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;">
      <a href="<?= url('/checkout') ?>" class="btn btn-primary btn-block btn-lg" onclick="if(window.CartUI) window.CartUI.closeDrawer();">
        Finalizar pedido
      </a>
      <button type="button" class="btn btn-outline-dark btn-block btn-sm" onclick="if(window.CartUI) window.CartUI.closeDrawer();">
        Seguir comprando
      </button>
    </div>


  </div>

</aside>
