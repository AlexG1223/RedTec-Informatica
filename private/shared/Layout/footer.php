<?php
/**
 * RedTec Informática - Partial Footer & WhatsApp Flotante
 */
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      
      <!-- Columna 1: Info e Isotipo -->
      <div class="footer-brand">
        <img src="<?= url('/assets/img/Logotipo PNG.png') ?>" alt="RedTec Informática" width="160" height="42">
        <p>
          Soluciones integrales de informática en Uruguay. Venta de equipamiento, instalación de infraestructura de red, sistemas de videovigilancia y soporte corporativo especializado.
        </p>
        <div class="footer-socials">
          <a href="<?= REDTEC_INSTAGRAM_URL ?>" target="_blank" rel="noopener noreferrer" class="social-link" title="Instagram" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="<?= REDTEC_THREADS_URL ?>" target="_blank" rel="noopener noreferrer" class="social-link" title="Threads" aria-label="Threads">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.186 24.004c-3.264 0-6.107-1.144-8.085-3.242-1.921-2.038-2.911-4.887-2.911-8.384 0-3.666 1.054-6.604 3.134-8.732C6.46 1.488 9.388.359 12.723.359c3.344 0 6.223 1.134 8.327 3.28 2.046 2.088 3.082 4.984 3.082 8.608 0 .524-.031 1.05-.091 1.564a.972.972 0 0 1-.968.857h-3.815c-.482 0-.889-.356-.96-.832a5.452 5.452 0 0 0-1.848-3.328 5.253 5.253 0 0 0-3.714-1.282c-1.503 0-2.827.525-3.73 1.48-.902.955-1.37 2.37-1.37 4.195 0 1.834.464 3.25 1.381 4.21.916.958 2.247 1.474 3.748 1.474 1.706 0 3.056-.632 3.864-1.802a4.992 4.992 0 0 0 .72-2.316.974.974 0 0 1 .97-.864h2.18c.538 0 .974.437.974.975 0 2.217-.822 4.204-2.313 5.594-1.815 1.69-4.27 2.583-7.11 2.583z"/></svg>
          </a>
          <a href="<?= REDTEC_WHATSAPP_LINK ?>" target="_blank" rel="noopener noreferrer" class="social-link" title="WhatsApp" aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
          </a>
        </div>
      </div>

      <!-- Columna 2: Enlaces Rápidos -->
      <div>
        <h4 class="footer-title">Navegación</h4>
        <div class="footer-links">
          <a href="<?= url('/') ?>">Inicio</a>
          <a href="<?= url('/tienda') ?>">Catálogo de Productos</a>
          <a href="<?= url('/servicios-tecnicos') ?>">Servicios Técnicos</a>
          <a href="<?= url('/servicios-corporativos') ?>">Planes Corporativos</a>
          <a href="<?= url('/contacto') ?>">Contacto</a>
        </div>
      </div>

      <!-- Columna 3: Servicios -->
      <div>
        <h4 class="footer-title">Servicios</h4>
        <div class="footer-links">
          <a href="<?= url('/servicios-tecnicos#cctv') ?>">Cámaras de Seguridad</a>
          <a href="<?= url('/servicios-tecnicos#redes') ?>">Cableado Estructurado</a>
          <a href="<?= url('/servicios-tecnicos#servidores') ?>">Servidores y NAS</a>
          <a href="<?= url('/servicios-corporativos') ?>">Abonos Mensuales PYME</a>
          <a href="<?= url('/admin/login') ?>">Acceso Administración</a>
        </div>
      </div>

      <!-- Columna 4: Contacto Uruguay -->
      <div>
        <h4 class="footer-title">Contacto</h4>
        
        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>Atlántida, Canelones, Uruguay</span>
        </div>

        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
          <a href="<?= REDTEC_WHATSAPP_LINK ?>" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: none;">WhatsApp: <?= REDTEC_WHATSAPP_DISPLAY ?></a>
        </div>

        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <a href="<?= REDTEC_PHONE_FIJO_LINK ?>" style="color: inherit; text-decoration: none;">Tel. Fijo: <?= REDTEC_PHONE_FIJO ?></a>
        </div>

        <div class="footer-contact-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>contacto@redtecinformatica.com</span>
        </div>

      </div>

    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <div>
        &copy; <?= date('Y') ?> <strong>RedTec Informática</strong>. Todos los derechos reservados.
      </div>
      <div>
        Atlántida, Canelones, Uruguay
      </div>
    </div>
  </div>
</footer>

<!-- Botón Flotante de WhatsApp -->
<a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20hacer%20una%20consulta" 
   class="whatsapp-float" 
   target="_blank" 
   rel="noopener noreferrer" 
   title="Consultar por WhatsApp">
  <span class="whatsapp-label">¿Consultas? ¡Chateá con nosotros!</span>
  <div class="whatsapp-icon-btn">
    <svg viewBox="0 0 24 24">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/>
    </svg>
  </div>
</a>
