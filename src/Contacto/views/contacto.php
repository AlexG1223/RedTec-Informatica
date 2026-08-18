<?php
/**
 * RedTec Informática - Vista de la Página de Contacto
 */

$content = function() {
?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <span style="color: var(--color-primary); font-weight: 700;">Contacto</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.5rem; font-weight: 800;">Contactanos</h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 1.05rem; max-width: 700px;">
        Visitá nuestro local comercial en Atlántida o comunicate directo con nuestros asesores por WhatsApp.
      </p>
    </div>
  </div>

  <section class="section-padding">
    <div class="container">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;" class="grid-contact-layout">
        
        <!-- FORMULARIO DE CONSULTA -->
        <div style="background: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
          <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.75rem; color: var(--color-dark);">
            Enviar una Consulta Directa
          </h2>

          <form action="<?= url('/contacto') ?>" method="POST">
            
            <div style="margin-bottom: 1.25rem;">
              <label for="nombre" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                Nombre Completo <span style="color: var(--color-primary);">*</span>
              </label>
              <input type="text" 
                     id="nombre" 
                     name="nombre" 
                     required 
                     placeholder="Tu nombre y apellido" 
                     style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
            </div>

            <div style="margin-bottom: 1.25rem;">
              <label for="telefono" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                Teléfono / WhatsApp <span style="color: var(--color-primary);">*</span>
              </label>
              <input type="tel" 
                     id="telefono" 
                     name="telefono" 
                     required 
                     placeholder="099 000 000" 
                     style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
            </div>

            <div style="margin-bottom: 1.5rem;">
              <label for="mensaje" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.35rem;">
                Consulta o Mensaje <span style="color: var(--color-primary);">*</span>
              </label>
              <textarea id="mensaje" 
                        name="mensaje" 
                        rows="4" 
                        required 
                        placeholder="Escribí aquí tu consulta sobre productos o servicios..." 
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
              Enviar Mensaje por WhatsApp
            </button>

          </form>
        </div>

        <!-- TARJETAS DE INFORMACIÓN DE CONTACTO -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
          
          <div style="background: var(--color-dark); color: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border-left: 4px solid var(--color-primary);">
            <h3 style="color: #FFFFFF; margin-bottom: 1rem; font-size: 1.2rem;">Atención Presencial en Local</h3>
            <p style="color: #B0B0B0; margin-bottom: 0.75rem; font-size: 0.95rem;">
              <strong>Ubicación:</strong> Atlántida, Canelones, Uruguay.
            </p>
            <p style="color: #B0B0B0; margin-bottom: 0; font-size: 0.95rem;">
              <strong>Horarios:</strong> Lunes a Viernes de 09:00 a 19:00 hs. Sábados de 09:00 a 13:00 hs.
            </p>
          </div>

          <div style="background: #FFFFFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
            <h3 style="color: var(--color-dark); margin-bottom: 1rem; font-size: 1.2rem;">Contacto Directo por WhatsApp</h3>
            <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem; font-size: 0.95rem;">
              ¿Querés una respuesta inmediata? Hablá en vivo con nuestro equipo técnico.
            </p>
            <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20hacer%20una%20consulta" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn btn-primary btn-block btn-lg" 
               style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              Iniciar Chat en WhatsApp
            </a>
          </div>

        </div>

      </div>

    </div>
  </section>

  <style>
  @media (max-width: 991px) {
    .grid-contact-layout {
      grid-template-columns: 1fr !important;
    }
  }
  </style>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
