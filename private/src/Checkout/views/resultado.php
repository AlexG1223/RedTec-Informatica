<?php

/**
 * RedTec Informática - Vista de Resultado de Checkout (Mercado Pago)
 * 
 * @var string $statusEstado ('success', 'pending', 'failure')
 * @var array|null $pedido Datos del pedido
 * @var string $orderNumber Código del pedido (ej: #00016)
 */

$content = function() use ($statusEstado, $pedido, $orderNumber) {
    $titleText = "Resultado de la Compra";
    $bgColor   = "#3B82F6";
    $iconSvg   = "";

    if ($statusEstado === 'success') {
        $titleText = "¡Pago Confirmado!";
        $bgColor   = "#10B981"; // Verde
        $iconSvg   = '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
    } elseif ($statusEstado === 'pending') {
        $titleText = "Pago Pendiente de Confirmación";
        $bgColor   = "#F59E0B"; // Amarillo/Naranja
        $iconSvg   = '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
    } else {
        $titleText = "Pago no Completado";
        $bgColor   = "#EF4444"; // Rojo
        $iconSvg   = '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    }
?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container text-center">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <a href="<?= url('/tienda') ?>" style="color: #B0B0B0;">Tienda</a> &rarr; 
        <span style="color: var(--color-primary); font-weight: 700;">Resultado de Compra</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0; font-weight: 800;"><?= htmlspecialchars($titleText) ?></h1>
    </div>
  </div>

  <section class="section-padding">
    <div class="container" style="max-width: 650px;">
      
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-md); overflow: hidden; text-align: center;">
        
        <div style="background-color: <?= $bgColor ?>; padding: 2.5rem 1.5rem; color: #FFFFFF; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem;">
          <div><?= $iconSvg ?></div>
          <h2 style="color: #FFFFFF; font-size: 1.6rem; margin: 0; font-weight: 800;"><?= htmlspecialchars($titleText) ?></h2>
          <?php if ($orderNumber): ?>
            <div style="background: rgba(0,0,0,0.2); padding: 0.35rem 1rem; border-radius: 20px; font-size: 0.95rem; font-weight: 700;">
              Pedido <?= htmlspecialchars($orderNumber) ?>
            </div>
          <?php endif; ?>
        </div>

        <div style="padding: 2.5rem 2rem;">
          <?php if ($statusEstado === 'success'): ?>
            <p style="font-size: 1.05rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
              ¡Tu pago fue procesado con éxito! Hemos registrado tu pedido y nuestro equipo en Atlántida ya está preparando tus productos. Te enviamos una confirmación por correo electrónico.
            </p>
          <?php elseif ($statusEstado === 'pending'): ?>
            <p style="font-size: 1.05rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
              Tu pago se encuentra en proceso de aprobación o en espera del abono en el punto de cobro seleccionado (Redpagos / Abitab). Tan pronto como Mercado Pago confirme el pago, procesaremos tu pedido automáticamente.
            </p>
          <?php else: ?>
            <p style="font-size: 1.05rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
              El pago no pudo ser completado o fue rechazado por el emisor. Podés reintentar el pago con otra tarjeta o elegir coordinar tu pedido directamente por WhatsApp.
            </p>
          <?php endif; ?>

          <?php if ($pedido): ?>
            <div style="background: #F9FAFB; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2rem; text-align: left; font-size: 0.9rem;">
              <div style="font-family: var(--font-heading); font-weight: 700; color: var(--color-dark); margin-bottom: 0.75rem; border-bottom: 1px solid #E5E7EB; padding-bottom: 0.5rem;">
                Resumen de la Operación
              </div>
              <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem; margin-bottom: 0.35rem;">
                <span style="color: var(--color-text-muted);">Cliente:</span>
                <strong><?= htmlspecialchars($pedido['client_name']) ?></strong>
              </div>
              <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem; margin-bottom: 0.35rem;">
                <span style="color: var(--color-text-muted);">Teléfono:</span>
                <span><?= htmlspecialchars($pedido['client_phone']) ?></span>
              </div>
              <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem; margin-bottom: 0.35rem;">
                <span style="color: var(--color-text-muted);">Dirección:</span>
                <span><?= htmlspecialchars($pedido['client_address']) ?></span>
              </div>
              <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem;">
                <span style="color: var(--color-text-muted);">Monto Total:</span>
                <strong style="color: var(--color-primary); font-size: 1.05rem;">$U <?= number_format((float)$pedido['total_amount'], 2, ',', '.') ?></strong>
              </div>
            </div>
          <?php endif; ?>

          <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= url('/tienda') ?>" class="btn btn-primary btn-lg">
              Volver a la Tienda
            </a>
            
            <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=<?= urlencode('Hola RedTec, consulto por mi pedido ' . ($orderNumber ?: '')) ?>" 
               target="_blank" 
               class="btn btn-outline-dark btn-lg" 
               style="display: inline-flex; align-items: center; gap: 0.5rem;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              Consultar por WhatsApp
            </a>
          </div>

        </div>

      </div>

    </div>
  </section>

  <!-- Si el pago fue exitoso, limpiar el carrito local JS -->
  <?php if ($statusEstado === 'success'): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      if (window.CartService && typeof window.CartService.clear === 'function') {
        window.CartService.clear();
      }
    });
  </script>
  <?php endif; ?>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
