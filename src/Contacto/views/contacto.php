<?php
/**
 * RedTec Informática - Vista de Contacto
 */

$content = function() {
    $waDisplay = defined('REDTEC_WHATSAPP_DISPLAY') ? REDTEC_WHATSAPP_DISPLAY : '+598 91 633 699';
?>
  <!-- Banner Header Contacto -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Atención al Cliente</span>
      <h1 style="color: #FFFFFF; margin-top: 0.35rem; margin-bottom: 0.5rem; font-size: 2.3rem;">
        Contacto & Asesoramiento Técnico
      </h1>
      <p style="color: #B0B0B0; margin-bottom: 0; max-width: 650px;">
        Estamos ubicados en Atlántida, Canelones. Ponete en contacto con nuestros técnicos para presupuestos, ventas o soporte corporativo.
      </p>
    </div>
  </section>

  <div class="container section-padding">
    <div class="grid grid-2" style="gap: 2.5rem; align-items: start;">
      
      <!-- COLUMNA 1: FORMULARIO DE CONTACTO -->
      <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
        <h2 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1.5rem; color: var(--color-dark);">
          Envianos tu Consulta
        </h2>
        <p style="font-size: 0.92rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
          Completá tus datos y enviá tu mensaje directamente a nuestro canal oficial de WhatsApp para una respuesta rápida.
        </p>

        <form action="<?= url('/contacto') ?>" method="POST">
          
          <div style="margin-bottom: 1.25rem;">
            <label for="nombre" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Nombre Completo <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="text" 
                   id="nombre" 
                   name="nombre" 
                   required 
                   placeholder="Ej: Juan Pérez" 
                   style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); outline: none;"
                   onfocus="this.style.borderColor='var(--color-primary)';"
                   onblur="this.style.borderColor='var(--color-border-metallic)';">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
              <label for="email" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
                Correo Electrónico
              </label>
              <input type="email" 
                     id="email" 
                     name="email" 
                     placeholder="ejemplo@correo.com" 
                     style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); outline: none;"
                     onfocus="this.style.borderColor='var(--color-primary)';"
                     onblur="this.style.borderColor='var(--color-border-metallic)';">
            </div>

            <div>
              <label for="telefono" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
                Teléfono de Contacto
              </label>
              <input type="tel" 
                     id="telefono" 
                     name="telefono" 
                     placeholder="099 123 456" 
                     style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); outline: none;"
                     onfocus="this.style.borderColor='var(--color-primary)';"
                     onblur="this.style.borderColor='var(--color-border-metallic)';">
            </div>
          </div>

          <div style="margin-bottom: 1.25rem;">
            <label for="asunto" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Asunto o Motivo
            </label>
            <select id="asunto" 
                    name="asunto" 
                    style="width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); outline: none; background: #FFF;">
              <option value="Consulta sobre Productos">Consulta sobre Productos / Notebooks / Hardware</option>
              <option value="Presupuesto Cámaras CCTV">Presupuesto de Cámaras de Seguridad (CCTV)</option>
              <option value="Servicio Técnico e Infraestructura">Servicio Técnico & Reparaciones</option>
              <option value="Planes Mensuales PyME">Planes Mensuales Corporativos</option>
              <option value="Otra consulta">Otra consulta general</option>
            </select>
          </div>

          <div style="margin-bottom: 1.75rem;">
            <label for="mensaje" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Mensaje o Detalle <span style="color: var(--color-primary);">*</span>
            </label>
            <textarea id="mensaje" 
                      name="mensaje" 
                      rows="4" 
                      required 
                      placeholder="Escribí aquí los detalles de lo que necesitas consultar..." 
                      style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); outline: none;"
                      onfocus="this.style.borderColor='var(--color-primary)';"
                      onblur="this.style.borderColor='var(--color-border-metallic)';"></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-block btn-lg" style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
            Enviar Consulta por WhatsApp
          </button>

        </form>
      </div>

      <!-- COLUMNA 2: INFORMACIÓN DE CONTACTO & ATENCIÓN -->
      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- CARD WHATSAPP DIRECTO -->
        <div style="background: linear-gradient(135deg, #170E0C 0%, #291512 100%); color: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--color-primary); box-shadow: var(--shadow-md);">
          <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: rgba(37, 211, 102, 0.15); color: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
            </div>
            <div>
              <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-primary); font-weight: 700;">Atención Inmediata</span>
              <h3 style="color: #FFFFFF; margin: 0; font-size: 1.3rem;">WhatsApp Oficial</h3>
            </div>
          </div>
          <p style="color: #C0C0C0; font-size: 0.93rem; margin-bottom: 1.25rem;">
            Chateá directo con nuestros asesores para consultas de stock, cotizaciones y atención técnica.
          </p>
          <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20hacer%20una%20consulta" 
             target="_blank" 
             rel="noopener noreferrer" 
             style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800; color: #25D366; text-decoration: none;">
            <?= $waDisplay ?>
          </a>
        </div>

        <!-- DETALLES DE UBICACIÓN Y ATENCIÓN -->
        <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
          <h3 style="margin-top: 0; margin-bottom: 1.25rem; font-size: 1.2rem; color: var(--color-dark);">
            Información de Contacto
          </h3>

          <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            
            <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
              <div style="width: 36px; height: 36px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              </div>
              <div>
                <strong style="display: block; font-size: 0.9rem; color: var(--color-dark);">Local Físico & Ubicación:</strong>
                <span style="font-size: 0.9rem; color: var(--color-text-secondary);">Atlántida, Canelones, Uruguay</span>
              </div>
            </div>

            <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
              <div style="width: 36px; height: 36px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </div>
              <div>
                <strong style="display: block; font-size: 0.9rem; color: var(--color-dark);">Correo Electrónico:</strong>
                <a href="mailto:contacto@redtecinformatica.com" style="font-size: 0.9rem; color: var(--color-primary); text-decoration: none;">contacto@redtecinformatica.com</a>
              </div>
            </div>

            <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
              <div style="width: 36px; height: 36px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div>
                <strong style="display: block; font-size: 0.9rem; color: var(--color-dark);">Horarios de Atención:</strong>
                <span style="font-size: 0.88rem; color: var(--color-text-secondary); display: block;">Lunes a Viernes: 09:00 a 19:00 hs</span>
                <span style="font-size: 0.88rem; color: var(--color-text-secondary); display: block;">Sábados: 09:00 a 13:00 hs</span>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
  </div>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
