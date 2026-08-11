<?php
/**
 * RedTec Informática - Vista de Checkout (Finalización de Pedido vía WhatsApp)
 */

$content = function() {
?>
  <!-- Script específico para armar el mensaje de WhatsApp -->
  <script src="/assets/js/checkout/whatsapp-message-builder.js" defer></script>

  <!-- Header Banner Checkout -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Paso Final</span>
      <h1 style="color: #FFFFFF; margin-top: 0.35rem; margin-bottom: 0.5rem; font-size: 2.2rem;">Finalizar Pedido</h1>
      <p style="color: #B0B0B0; margin-bottom: 0; max-width: 640px;">
        Ingresá tus datos de contacto para enviar tu orden por WhatsApp y coordinar el pago y la entrega con nuestro equipo técnico.
      </p>
    </div>
  </section>

  <div class="container section-padding">

    <!-- CONTENEDOR SI EL CARRITO ESTÁ VACÍO (Oculto por defecto, activado por JS) -->
    <div id="checkoutEmptyState" style="display: none; background: #FFFFFF; padding: 4rem 2rem; border-radius: var(--radius-lg); border: 1px dashed var(--color-border-metallic); text-align: center; max-width: 650px; margin: 2rem auto;">
      <div style="width: 64px; height: 64px; background: var(--color-primary-light); color: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      </div>
      <h3 style="margin-bottom: 0.75rem;">Tu carrito está vacío</h3>
      <p style="color: var(--color-text-secondary); margin-bottom: 1.75rem;">
        No hay productos guardados en tu pedido. Para realizar una compra, primero explorá nuestro catálogo y agregá artículos al carrito.
      </p>
      <a href="/tienda" class="btn btn-primary btn-lg">Volver a la Tienda</a>
    </div>

    <!-- CONTENEDOR PRINCIPAL DE CHECKOUT (2 COLUMNAS) -->
    <div id="checkoutMainContent" class="grid grid-2" style="gap: 3rem; align-items: start;">
      
      <!-- COLUMNA IZQUIERDA: FORMULARIO DE DATOS -->
      <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        
        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          1. Datos de Contacto y Envío
        </h3>

        <form id="checkoutForm" novalidate>
          
          <!-- Nombre Completo -->
          <div style="margin-bottom: 1.25rem;">
            <label for="nombre" style="display: block; font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600; color: var(--color-dark); margin-bottom: 0.35rem;">
              Nombre y Apellido <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   placeholder="Ej: Juan Pérez" 
                   required 
                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem; outline: none;"
                   onfocus="this.style.borderColor='var(--color-primary)';"
                   onblur="this.style.borderColor='var(--color-border-metallic)';">
          </div>

          <!-- Teléfono / WhatsApp -->
          <div style="margin-bottom: 1.25rem;">
            <label for="telefono" style="display: block; font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600; color: var(--color-dark); margin-bottom: 0.35rem;">
              Teléfono / WhatsApp de Contacto <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="tel" 
                   id="telefono" 
                   name="telefono" 
                   placeholder="Ej: 099 123 456" 
                   required 
                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem; outline: none;"
                   onfocus="this.style.borderColor='var(--color-primary)';"
                   onblur="this.style.borderColor='var(--color-border-metallic)';">
          </div>

          <!-- Dirección de Envío -->
          <div style="margin-bottom: 1.75rem;">
            <label for="direccion" style="display: block; font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600; color: var(--color-dark); margin-bottom: 0.35rem;">
              Dirección de Envío <span style="font-weight: 400; color: var(--color-text-muted);">(Opcional)</span>
            </label>
            <input type="text" 
                   id="direccion" 
                   name="direccion" 
                   placeholder="Calle, número de puerta, localidad o ciudad..." 
                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem; outline: none;"
                   onfocus="this.style.borderColor='var(--color-primary)';"
                   onblur="this.style.borderColor='var(--color-border-metallic)';">
            <small style="display: block; color: var(--color-text-muted); font-size: 0.8rem; margin-top: 0.35rem;">
              💡 <em>Aclaración: Dejar vacío si preferís retirar tu pedido personalmente en nuestro local de Atlántida.</em>
            </small>
          </div>

          <!-- Botón de Confirmación -->
          <button type="submit" 
                  class="btn btn-primary btn-lg btn-block"
                  style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
            Confirmar pedido y enviar a WhatsApp
          </button>

        </form>

      </div>

      <!-- COLUMNA DERECHA: RESUMEN DE COMPRA -->
      <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        
        <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          2. Resumen del Pedido
        </h3>

        <!-- Lista de Productos en el Pedido -->
        <div id="checkoutSummaryList" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; max-height: 380px; overflow-y: auto; padding-right: 0.5rem;"></div>

        <!-- Aviso Informativo -->
        <div class="cart-notice" style="margin-bottom: 1.25rem;">
          📱 <strong>Atención:</strong> Al hacer click en enviar, serás redirigido a WhatsApp con el detalle formateado de tu pedido. Un técnico de RedTec te confirmará la disponibilidad y las opciones de pago.
        </div>

        <!-- Total de Compra -->
        <div style="background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light); display: flex; justify-content: space-between; align-items: center;">
          <span style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; color: var(--color-dark);">Total Estimado:</span>
          <span style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: var(--color-primary);">
            USD $<span id="checkoutSummaryTotal">0.00</span>
          </span>
        </div>

      </div>

    </div>

  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const emptyState   = document.getElementById('checkoutEmptyState');
    const mainContent  = document.getElementById('checkoutMainContent');
    const summaryList  = document.getElementById('checkoutSummaryList');
    const summaryTotal = document.getElementById('checkoutSummaryTotal');
    const checkoutForm = document.getElementById('checkoutForm');

    function renderCheckoutPage() {
      if (!window.CartService) return;

      const items = window.CartService.getItems();
      const subtotal = window.CartService.getSubtotal();

      if (!items || items.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
        if (mainContent) mainContent.style.display = 'none';
        return;
      }

      if (emptyState) emptyState.style.display = 'none';
      if (mainContent) mainContent.style.display = 'grid';

      // Renderizar resumen de items
      if (summaryList) {
        summaryList.innerHTML = '';

        items.forEach(function(item) {
          const itemEl = document.createElement('div');
          itemEl.style.cssText = 'display: flex; gap: 1rem; align-items: center; padding-bottom: 0.85rem; border-bottom: 1px dashed var(--color-border-light);';

          const imgSrc  = item.image_url ? item.image_url : '/assets/img/redtec.jpeg';
          const lineSub = (parseFloat(item.price) * parseInt(item.quantity, 10)).toFixed(2);
          const codeStr = item.code ? ` <small style="color: var(--color-text-muted);">(${item.code})</small>` : '';

          itemEl.innerHTML = `
            <img src="${imgSrc}" alt="" style="width: 54px; height: 54px; object-fit: contain; background: #FFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-sm); padding: 2px; flex-shrink: 0;" onerror="this.src='/assets/img/redtec.jpeg';">
            <div style="flex-grow: 1;">
              <div style="font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600; color: var(--color-dark); margin-bottom: 0.2rem;">
                ${item.name}${codeStr}
              </div>
              <div style="font-size: 0.85rem; color: var(--color-text-secondary);">
                ${item.quantity} x USD $${parseFloat(item.price).toFixed(2)}
              </div>
            </div>
            <div style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--color-primary); flex-shrink: 0;">
              USD $${lineSub}
            </div>
          `;

          summaryList.appendChild(itemEl);
        });
      }

      if (summaryTotal) {
        summaryTotal.textContent = subtotal.toFixed(2);
      }
    }

    // Renderizado Inicial
    renderCheckoutPage();

    // Re-renderizar si cambia el carrito
    window.addEventListener('cart:updated', renderCheckoutPage);

    // Procesamiento del Formulario
    if (checkoutForm) {
      checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const inputNombre    = document.getElementById('nombre');
        const inputTelefono  = document.getElementById('telefono');
        const inputDireccion = document.getElementById('direccion');

        const nombre    = inputNombre ? inputNombre.value.trim() : '';
        const telefono  = inputTelefono ? inputTelefono.value.trim() : '';
        const direccion = inputDireccion ? inputDireccion.value.trim() : '';

        let hasError = false;

        if (!nombre) {
          inputNombre.style.borderColor = 'var(--color-stock-out)';
          hasError = true;
        } else {
          inputNombre.style.borderColor = 'var(--color-border-metallic)';
        }

        if (!telefono) {
          inputTelefono.style.borderColor = 'var(--color-stock-out)';
          hasError = true;
        } else {
          inputTelefono.style.borderColor = 'var(--color-border-metallic)';
        }

        if (hasError) {
          if (window.CartUI) {
            window.CartUI.showToast('Por favor completá tu Nombre y Teléfono para enviar el pedido.', 'error');
          } else {
            alert('Por favor completá los campos obligatorios (*).');
          }
          return;
        }

        const items = window.CartService.getItems();
        const subtotal = window.CartService.getSubtotal();

        if (!items || items.length === 0) {
          alert('Tu carrito está vacío.');
          return;
        }

        // Armar el mensaje con WhatsAppMessageBuilder
        if (!window.WhatsAppMessageBuilder) {
          alert('Error al cargar la herramienta de mensajes.');
          return;
        }

        const messageText = window.WhatsAppMessageBuilder.build(
          { name: nombre, phone: telefono, address: direccion },
          items,
          subtotal
        );

        const encodedMsg = encodeURIComponent(messageText);
        const targetUrl  = 'https://wa.me/<?= REDTEC_WHATSAPP_NUMBER ?>?text=' + encodedMsg;

        // Vaciar el carrito antes de redirigir
        window.CartService.clear();

        // Redirigir a WhatsApp
        window.location.href = targetUrl;
      });
    }
  });
  </script>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
