<?php
/**
 * RedTec Informática - Vista de Checkout / Pago Online Mercado Pago & WhatsApp
 */

$content = function() {
?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <a href="<?= url('/tienda') ?>" style="color: #B0B0B0;">Tienda</a> &rarr; 
        <span style="color: var(--color-primary); font-weight: 700;">Checkout</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0; font-weight: 800;">Finalizar Pedido</h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 0.95rem;">
        Completá tus datos de entrega y elegí tu método de pago preferido: Pago Online Seguro con Mercado Pago o Coordinar por WhatsApp.
      </p>
    </div>
  </div>

  <section class="section-padding">
    <div class="container">
      
      <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2.5rem; align-items: start;" class="grid-checkout-layout">
        
        <!-- FORMULARIO DE DATOS DEL CLIENTE Y SELECCIÓN DE PAGO -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
          
          <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
            
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.5rem;">
              <span style="background: var(--color-primary); color: #FFF; border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">1</span>
              Datos de Contacto y Envío
            </h2>

            <form id="checkoutForm" onsubmit="return false;">
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;" class="grid-2-cols">
                <div style="margin-bottom: 1.25rem;">
                  <label for="clientName" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                    Nombre y Apellido <span style="color: var(--color-primary);">*</span>
                  </label>
                  <input type="text" 
                         id="clientName" 
                         required 
                         placeholder="Ej: Juan Pérez" 
                         style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
                </div>

                <div style="margin-bottom: 1.25rem;">
                  <label for="clientPhone" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                    Teléfono de Contacto (WhatsApp) <span style="color: var(--color-primary);">*</span>
                  </label>
                  <input type="tel" 
                         id="clientPhone" 
                         required 
                         placeholder="Ej: 099 123 456" 
                         style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
                </div>
              </div>

              <div style="margin-bottom: 1.25rem;">
                <label for="clientEmail" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                  Correo Electrónico (para recibir comprobante)
                </label>
                <input type="email" 
                       id="clientEmail" 
                       placeholder="Ej: juan.perez@email.com" 
                       style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
              </div>

              <div style="margin-bottom: 1.25rem;">
                <label for="clientAddress" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                  Dirección de Entrega / Localidad <span style="color: var(--color-primary);">*</span>
                </label>
                <input type="text" 
                       id="clientAddress" 
                       required 
                       placeholder="Ej: Calle 11 entre 22 y 24, Atlántida" 
                       style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
              </div>

              <div style="margin-bottom: 0;">
                <label for="clientNotes" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                  Notas Adicionales (Opcional)
                </label>
                <textarea id="clientNotes" 
                          rows="2" 
                          placeholder="Ej: Horario de preferencia para recibir, aclaración de factura con RUT..." 
                          style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;"></textarea>
              </div>

            </form>

          </div>

          <!-- OPCIONES DE PAGO -->
          <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
            
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.5rem;">
              <span style="background: var(--color-primary); color: #FFF; border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">2</span>
              Seleccioná la Forma de Pago
            </h2>

            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.75rem;">
              
              <!-- Opción Mercado Pago -->
              <label id="labelOptionMP" style="display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem; border: 2px solid var(--color-primary); border-radius: var(--radius-md); background-color: #F0FDF4; cursor: pointer; transition: all 0.2s;">
                <input type="radio" name="paymentMethodOption" id="methodMP" value="mercadopago" checked style="margin-top: 0.25rem; transform: scale(1.2); accent-color: var(--color-primary);">
                <div style="flex-grow: 1;">
                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                    <strong style="font-family: var(--font-heading); font-size: 1rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.5rem;">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="#009EE3"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.5h-2v-2h2v2zm0-4h-2V7h2v5.5z"/></svg>
                      Mercado Pago (Pago Online Inmediato)
                    </strong>
                    <span style="background: #009EE3; color: #FFF; font-size: 0.7rem; font-weight: 800; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">Recomendado</span>
                  </div>
                  <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin: 0; line-height: 1.4;">
                    Tarjetas de crédito (hasta en cuotas), tarjetas de débito, dinero en cuenta de Mercado Pago o abono presencial en <strong>Redpagos / Abitab</strong>.
                  </p>
                </div>
              </label>

              <!-- Opción WhatsApp -->
              <label id="labelOptionWA" style="display: flex; align-items: flex-start; gap: 1rem; padding: 1.25rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); background-color: #FFFFFF; cursor: pointer; transition: all 0.2s;">
                <input type="radio" name="paymentMethodOption" id="methodWA" value="whatsapp" style="margin-top: 0.25rem; transform: scale(1.2); accent-color: var(--color-whatsapp);">
                <div style="flex-grow: 1;">
                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                    <strong style="font-family: var(--font-heading); font-size: 1rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.5rem;">
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--color-whatsapp)"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                      Coordinar Compra por WhatsApp
                    </strong>
                  </div>
                  <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin: 0; line-height: 1.4;">
                    Se armará un mensaje preconfigurado para conversar directamente con nuestro equipo técnico y coordinar transferencia bancaria o retiro en local.
                  </p>
                </div>
              </label>

            </div>

            <!-- BOTÓN MERCADO PAGO -->
            <button type="button" 
                    id="btnSubmitMP" 
                    class="btn btn-primary btn-lg btn-block" 
                    style="background-color: #009EE3; border-color: #009EE3; font-size: 1.05rem; font-weight: 700; height: 52px; display: flex; align-items: center; justify-content: center; gap: 0.65rem;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l7 4.5-7 4.5z"/></svg>
              <span>Pagar con Mercado Pago</span>
            </button>

            <!-- BOTÓN WHATSAPP -->
            <button type="button" 
                    id="btnSubmitWA" 
                    class="btn btn-primary btn-lg btn-block" 
                    style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); font-size: 1.05rem; font-weight: 700; height: 52px; display: none; align-items: center; justify-content: center; gap: 0.65rem;">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              <span>Enviar Pedido por WhatsApp</span>
            </button>

          </div>

        </div>

        <!-- RESUMEN DEL PEDIDO -->
        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm); position: sticky; top: 100px;">
          
          <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; color: var(--color-dark);">
            Resumen del Pedido
          </h2>

          <div id="checkoutItemsList" style="display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.5rem; max-height: 300px; overflow-y: auto;">
            <!-- Se puebla dinámicamente con JavaScript -->
          </div>

          <div style="border-top: 1px solid var(--color-border-light); padding-top: 1rem; margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 1.2rem; font-weight: 800; color: var(--color-dark);">
              <span>Total Estimado:</span>
              <span style="color: var(--color-primary); font-family: var(--font-heading); font-size: 1.5rem;">
                $U <span id="checkoutTotalAmount">0.00</span>
              </span>
            </div>
          </div>

          <div class="cart-notice" style="font-size: 0.85rem; color: var(--color-text-muted); background: #F8FAFC; padding: 0.75rem 1rem; border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
            🔒 Pago 100% encriptado y protegido por Mercado Pago o coordinación directa vía WhatsApp.
          </div>

        </div>

      </div>

    </div>
  </section>

  <!-- SCRIPT CONSTRUCTOR DE MENSAJE WHATSAPP Y CHECKOUT MERCADO PAGO -->
  <script src="<?= url('/assets/js/checkout/whatsapp-message-builder.js') ?>" defer></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    
    // Renderizado del Resumen de Compra
    function renderCheckoutSummary() {
      const itemsContainer = document.getElementById('checkoutItemsList');
      const totalContainer = document.getElementById('checkoutTotalAmount');
      if (!itemsContainer || !window.CartService) return;

      const items = window.CartService.getItems();
      const subtotal = window.CartService.getSubtotal();

      if (items.length === 0) {
        itemsContainer.innerHTML = '<p style="color: var(--color-text-muted); text-align: center;">El carrito está vacío. <a href="<?= url('/tienda') ?>">Ir a la tienda</a>.</p>';
        if (totalContainer) totalContainer.textContent = '0.00';
        return;
      }

      let html = '';
      items.forEach(item => {
        const itemTotal = (item.price * item.quantity).toFixed(2);
        html += `
          <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; padding-bottom: 0.5rem; border-bottom: 1px dashed var(--color-border-light);">
            <div>
              <strong style="color: var(--color-dark); display: block;">${item.name}</strong>
              <span style="color: var(--color-text-muted); font-size: 0.8rem;">Cant: ${item.quantity} x $U ${item.price.toFixed(2)}</span>
            </div>
            <span style="font-weight: 700; color: var(--color-dark);">$U ${itemTotal}</span>
          </div>
        `;
      });

      itemsContainer.innerHTML = html;
      if (totalContainer) totalContainer.textContent = subtotal.toFixed(2);
    }

    renderCheckoutSummary();

    // Alternar entre visibilidad de opciones Mercado Pago / WhatsApp
    const radioMP = document.getElementById('methodMP');
    const radioWA = document.getElementById('methodWA');
    const labelMP = document.getElementById('labelOptionMP');
    const labelWA = document.getElementById('labelOptionWA');
    const btnMP   = document.getElementById('btnSubmitMP');
    const btnWA   = document.getElementById('btnSubmitWA');

    function togglePaymentMethodUI() {
      if (radioMP.checked) {
        labelMP.style.borderColor = 'var(--color-primary)';
        labelMP.style.backgroundColor = '#F0FDF4';
        labelWA.style.borderColor = 'var(--color-border-metallic)';
        labelWA.style.backgroundColor = '#FFFFFF';
        btnMP.style.display = 'flex';
        btnWA.style.display = 'none';
      } else {
        labelWA.style.borderColor = 'var(--color-whatsapp)';
        labelWA.style.backgroundColor = '#F0FDF4';
        labelMP.style.borderColor = 'var(--color-border-metallic)';
        labelMP.style.backgroundColor = '#FFFFFF';
        btnWA.style.display = 'flex';
        btnMP.style.display = 'none';
      }
    }

    if (radioMP && radioWA) {
      radioMP.addEventListener('change', togglePaymentMethodUI);
      radioWA.addEventListener('change', togglePaymentMethodUI);
    }

    // Procesar Pago Mercado Pago
    if (btnMP) {
      btnMP.addEventListener('click', function() {
        const name    = document.getElementById('clientName').value.trim();
        const phone   = document.getElementById('clientPhone').value.trim();
        const email   = document.getElementById('clientEmail').value.trim();
        const address = document.getElementById('clientAddress').value.trim();
        const notes   = document.getElementById('clientNotes').value.trim();

        if (!name || !phone || !address) {
          alert('Por favor complete los campos obligatorios (*) Nombre, Teléfono y Dirección.');
          return;
        }

        const items = window.CartService ? window.CartService.getItems() : [];
        if (items.length === 0) {
          alert('Tu carrito está vacío. Agregá productos antes de realizar el pedido.');
          return;
        }

        // Estado cargando
        btnMP.disabled = true;
        btnMP.innerHTML = '<span>Procesando Preferencia...</span>';

        fetch('<?= url('/checkout/crear-preferencia') ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            name: name,
            phone: phone,
            email: email,
            address: address,
            notes: notes,
            items: items
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success' && data.init_point) {
            // Redireccionar al Checkout Pro de Mercado Pago
            window.location.href = data.init_point;
          } else {
            alert(data.message || 'Ocurrió un error al conectar con Mercado Pago. Intentelo nuevamente.');
            btnMP.disabled = false;
            btnMP.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l7 4.5-7 4.5z"/></svg><span>Pagar con Mercado Pago</span>';
          }
        })
        .catch(err => {
          console.error(err);
          alert('Error de comunicación con el servidor. Por favor verifique su conexión.');
          btnMP.disabled = false;
          btnMP.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l7 4.5-7 4.5z"/></svg><span>Pagar con Mercado Pago</span>';
        });
      });
    }

    // Procesar Pedido por WhatsApp
    if (btnWA) {
      btnWA.addEventListener('click', function() {
        const name    = document.getElementById('clientName').value.trim();
        const phone   = document.getElementById('clientPhone').value.trim();
        const email   = document.getElementById('clientEmail').value.trim();
        const address = document.getElementById('clientAddress').value.trim();
        const notes   = document.getElementById('clientNotes').value.trim();

        if (!name || !phone || !address) {
          alert('Por favor complete los campos obligatorios (*) Nombre, Teléfono y Dirección.');
          return;
        }

        const items = window.CartService ? window.CartService.getItems() : [];
        if (items.length === 0) {
          alert('Tu carrito está vacío. Agregá productos antes de realizar el pedido.');
          return;
        }

        btnWA.disabled = true;

        fetch('<?= url('/checkout/crear-pedido-whatsapp') ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, phone, email, address, notes, items })
        })
        .then(res => res.json())
        .then(data => {
          if (window.WhatsAppMessageBuilder) {
            const waUrl = window.WhatsAppMessageBuilder.buildUrl({
              name, phone, address, notes, items,
              total: window.CartService.getSubtotal()
            });
            window.open(waUrl, '_blank');
          }
          btnWA.disabled = false;
        })
        .catch(() => {
          if (window.WhatsAppMessageBuilder) {
            const waUrl = window.WhatsAppMessageBuilder.buildUrl({
              name, phone, address, notes, items,
              total: window.CartService.getSubtotal()
            });
            window.open(waUrl, '_blank');
          }
          btnWA.disabled = false;
        });
      });
    }

  });
  </script>

  <style>
  @media (max-width: 991px) {
    .grid-checkout-layout {
      grid-template-columns: 1fr !important;
    }
    .grid-2-cols {
      grid-template-columns: 1fr !important;
    }
  }
  </style>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
