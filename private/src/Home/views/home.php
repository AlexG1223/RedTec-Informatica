<?php
/**
 * RedTec Informática - Vista de la Página de Inicio (Home)
 * Rediseñada con Carrusel Interactivo de Alto Impacto y Mapa de Ubicación Oficial en Atlántida
 * 
 * @var array $categories Lista de categorías traídas de la BD.
 * @var array $faqs Preguntas frecuentes para usuarios y buscadores.
 */

$content = function() use ($categories, $faqs) {
    // Mapa interactivo de Google Maps apuntando exactamente a RedTec Informática (-34.774475, -55.7614383)
    $googleMapsLink = "https://www.google.com/maps/place/RedTec+Inform%C3%A1tica/@-34.7751434,-55.7631209,15.64z/data=!4m6!3m5!1s0x959ff42bd91771af:0x7e7339dbaf53099!8m2!3d-34.774475!4d-55.7614383";
    $embedMapUrl    = "https://maps.google.com/maps?q=-34.774475,-55.7614383&hl=es&z=16&output=embed";
?>
  <!-- ============================================================================
       1. HERO CAROUSEL INTERACTIVO DE ALTO IMPACTO (ESTILO DK COMPUTERS)
       ============================================================================ -->
  <section class="hero-carousel-container" id="heroCarousel">
    
    <!-- Botones de Navegación Flechas -->
    <button type="button" class="carousel-nav-btn prev" id="carouselPrev" aria-label="Slide anterior">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button type="button" class="carousel-nav-btn next" id="carouselNext" aria-label="Siguiente slide">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    </button>

    <!-- Puntos de Indicador -->
    <div class="carousel-dots" id="carouselDots">
      <button type="button" class="carousel-dot active" data-slide="0" aria-label="Ir al slide 1"></button>
      <button type="button" class="carousel-dot" data-slide="1" aria-label="Ir al slide 2"></button>
      <button type="button" class="carousel-dot" data-slide="2" aria-label="Ir al slide 3"></button>
    </div>

    <!-- Contenedor de Slides -->
    <div class="hero-carousel-slides" id="carouselSlides">
      
      <!-- SLIDE 1: TECNOLOGÍA & EQUIPOS -->
      <div class="hero-slide">
        <div class="container">
          <div style="max-width: 780px;">
            <span class="hero-spotlight-badge">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
              Atlántida &bull; Canelones &bull; Uruguay
            </span>
            
            <h1 class="hero-title-dk">
              TECNOLOGÍA Y EQUIPAMIENTO <span class="hero-title-highlight">DISEÑADOS PARA DESTACAR</span>
            </h1>

            <p class="hero-subtitle-dk">
              Notebooks de alto rendimiento, Mini PCs y periféricos corporativos de primeras marcas con garantía oficial y soporte personalizado en Atlántida.
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
              <a href="<?= url('/tienda') ?>" class="btn btn-primary btn-lg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Explorar Catálogo
              </a>
              <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20consultar%20por%20un%20equipo" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-outline-dark btn-lg" 
                 style="color: #FFFFFF; border-color: rgba(255,255,255,0.4);">
                Consultar por WhatsApp
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- SLIDE 2: SEGURIDAD & CÁMARAS CCTV -->
      <div class="hero-slide">
        <div class="container">
          <div style="max-width: 780px;">
            <span class="hero-spotlight-badge" style="color: #60A5FA; background: rgba(59, 130, 246, 0.2); border-color: rgba(96, 165, 250, 0.3);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Seguridad & Videovigilancia IP
            </span>
            
            <h1 class="hero-title-dk">
              PROTEGÉ LO QUE MÁS IMPORTA CON <span class="hero-title-highlight" style="background: linear-gradient(135deg, #3B82F6 0%, #60A5FA 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CÁMARAS CCTV HD</span>
            </h1>

            <p class="hero-subtitle-dk">
              Diseño, instalación y mantenimiento de sistemas de videovigilancia con visión nocturna y monitoreo en vivo desde tu celular en todo Canelones.
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
              <a href="<?= url('/servicios#cctv') ?>" class="btn btn-primary btn-lg" style="background-color: #3B82F6; border-color: #3B82F6;">
                Ver Sistemas CCTV
              </a>
              <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20un%20presupuesto%20de%20c%C3%A1maras%20CCTV" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-outline-dark btn-lg" 
                 style="color: #FFFFFF; border-color: rgba(255,255,255,0.4);">
                Solicitar Cotización
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- SLIDE 3: REDES & SOPORTE CORPORATIVO -->
      <div class="hero-slide">
        <div class="container">
          <div style="max-width: 780px;">
            <span class="hero-spotlight-badge" style="color: #A78BFA; background: rgba(139, 92, 246, 0.2); border-color: rgba(167, 139, 250, 0.3);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
              Infraestructura & Abonos PyME
            </span>
            
            <h1 class="hero-title-dk">
              INFRAESTRUCTURA DE RED Y <span class="hero-title-highlight" style="background: linear-gradient(135deg, #8B5CF6 0%, #C4B5FD 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SOPORTE TÉCNICO PYME</span>
            </h1>

            <p class="hero-subtitle-dk">
              Cableado estructurado Cat6, servidores de datos NAS, Wi-Fi Mesh empresarial y abonos de mantenimiento técnico preventivo para comercios y PyMEs.
            </p>

            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
              <a href="<?= url('/servicios-corporativos') ?>" class="btn btn-primary btn-lg" style="background-color: #8B5CF6; border-color: #8B5CF6;">
                Ver Planes Corporativos
              </a>
              <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20consultar%20por%20un%20abono%20mensual" 
                 target="_blank" 
                 rel="noopener noreferrer" 
                 class="btn btn-outline-dark btn-lg" 
                 style="color: #FFFFFF; border-color: rgba(255,255,255,0.4);">
                Consultar con un Técnico
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>

  </section>

  <!-- ============================================================================
       2. CATEGORÍAS PRINCIPALES EN TARJETAS DE OVERLAY (ESTILO DK COMPUTERS)
       ============================================================================ -->
  <section class="section-padding">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Líneas de Producto</span>
          <h2 style="margin-bottom: 0;">Categorías Destacadas</h2>
        </div>
        <a href="<?= url('/tienda') ?>" class="btn btn-outline-dark btn-sm">Ver Catálogo Completo &rarr;</a>
      </div>

      <div class="category-overlay-grid">
        <?php foreach ($categories as $category): ?>
          <?php 
            $catId   = (int)$category['id'];
            $catName = mb_strtoupper(htmlspecialchars($category['name']), 'UTF-8');
            $rawImg  = !empty($category['image_url']) ? $category['image_url'] : '/assets/img/redtec.jpeg';
            $catImg  = strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg);
            $catLink = url('/tienda?categoria=' . $catId);
            $fallbackImg = url('/assets/img/redtec.jpeg');
          ?>
          <a href="<?= $catLink ?>" class="category-overlay-card">
            <img src="<?= $catImg ?>" alt="<?= $catName ?>" class="category-overlay-bg" onerror="this.src='<?= $fallbackImg ?>';">
            <div class="category-overlay-gradient"></div>
            <div class="category-overlay-content">
              <h3 class="category-overlay-title"><?= $catName ?></h3>
              <span class="category-overlay-subtitle">
                Ver Equipos 
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============================================================================
       3. SERVICIOS INSTITUCIONALES (PREVIEW)
       ============================================================================ -->
  <section style="background-color: #FFFFFF; border-top: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light);" class="section-padding">
    <div class="container">
      <div class="text-center" style="max-width: 680px; margin: 0 auto 3rem auto;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Soluciones Profesionales</span>
        <h2>Servicios Técnicos e Infraestructura</h2>
        <p>Brindamos asistencia técnica especializada en sitio y remota para pequeñas, medianas y grandes empresas.</p>
      </div>

      <div class="grid grid-3">
        <!-- Servicio 1 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column; transition: transform var(--transition-normal);"
             onmouseenter="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--color-primary)';"
             onmouseleave="this.style.transform='translateY(0)'; this.style.borderColor='var(--color-border-light)';">
          <div style="width: 54px; height: 54px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Cámaras de Seguridad (CCTV)</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Diseño e instalación de sistemas de videovigilancia IP y analógicos de alta definición con monitoreo remoto en smartphone.
          </p>
          <a href="<?= url('/servicios#cctv') ?>" style="font-weight: 700; font-size: 0.88rem; color: var(--color-primary); text-transform: uppercase;">Más información &rarr;</a>
        </div>

        <!-- Servicio 2 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column; transition: transform var(--transition-normal);"
             onmouseenter="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--color-primary)';"
             onmouseleave="this.style.transform='translateY(0)'; this.style.borderColor='var(--color-border-light)';">
          <div style="width: 54px; height: 54px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Servidores & Almacenamiento</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Implementación de servidores de dominio, sistemas de respaldo automatizado en NAS y virtualización para PyMEs.
          </p>
          <a href="<?= url('/servicios#servidores') ?>" style="font-weight: 700; font-size: 0.88rem; color: var(--color-primary); text-transform: uppercase;">Más información &rarr;</a>
        </div>

        <!-- Servicio 3 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column; transition: transform var(--transition-normal);"
             onmouseenter="this.style.transform='translateY(-4px)'; this.style.borderColor='var(--color-primary)';"
             onmouseleave="this.style.transform='translateY(0)'; this.style.borderColor='var(--color-border-light)';">
          <div style="width: 54px; height: 54px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Redes & Cableado Estructurado</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Cableado UTP Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi Mesh de alta cobertura.
          </p>
          <a href="<?= url('/servicios#redes') ?>" style="font-weight: 700; font-size: 0.88rem; color: var(--color-primary); text-transform: uppercase;">Más información &rarr;</a>
        </div>
      </div>

      <div class="text-center" style="margin-top: 2.5rem;">
        <a href="<?= url('/servicios') ?>" class="btn btn-primary btn-lg">Conocé Todos Nuestros Servicios</a>
      </div>
    </div>
  </section>

  <!-- ============================================================================
       4. SECCIÓN UBICACIÓN Y MAPA CON MARCADOR OFICIAL DE REDTEC INFORMÁTICA
       ============================================================================ -->
  <section class="section-padding">
    <div class="container">
      
      <div style="margin-bottom: 2rem;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Atención Presencial</span>
        <h2 style="margin-bottom: 0.5rem;">Nuestra Ubicación en Atlántida</h2>
        <p style="color: var(--color-text-secondary);">Visitá nuestro local comercial para asesoramiento técnico en persona o retiro de compras.</p>
      </div>

      <div class="location-map-card">
        
        <!-- Información de Local y Horarios -->
        <div class="map-info-side">
          <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem;">
            <div style="width: 42px; height: 42px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
              <h3 style="font-size: 1.25rem; margin-bottom: 0; color: var(--color-dark);">RedTec Informática</h3>
              <span style="font-size: 0.85rem; color: var(--color-primary); font-weight: 700;">Atlántida, Canelones, Uruguay</span>
            </div>
          </div>

          <div style="font-size: 0.95rem; color: var(--color-text-secondary); line-height: 1.7; margin-bottom: 1.5rem;">
            <p style="margin-bottom: 0.5rem;">
              <strong>Dirección:</strong> Atlántida, Departamento de Canelones, Uruguay.
            </p>
            <p style="margin-bottom: 0.5rem;">
              <strong>Teléfono / WhatsApp:</strong> <a href="<?= REDTEC_WHATSAPP_LINK ?>" style="font-weight: 700; color: var(--color-dark);">+598 91 633 699</a>
            </p>
            <p style="margin-bottom: 0;">
              <strong>Horarios:</strong> Lunes a Viernes de 09:00 a 19:00 hs &bull; Sábados de 09:00 a 13:00 hs.
            </p>
          </div>

          <div style="display: flex; flex-wrap: wrap; gap: 0.85rem;">
            <a href="<?= $googleMapsLink ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn btn-primary">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              Abrir en Google Maps
            </a>
            
            <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20consultar%20la%20ubicaci%C3%B3n" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn btn-outline-dark"
               style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); color: #FFFFFF;">
              Consultar por WhatsApp
            </a>
          </div>
        </div>

        <!-- Contenedor del Mapa Embebido con Marcador en Atlántida -->
        <div class="map-iframe-wrap">
          <iframe src="<?= $embedMapUrl ?>" 
                  title="Ubicación de RedTec Informática en Google Maps" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

      </div>

    </div>
  </section>

  <!-- ============================================================================
       5. PREGUNTAS FRECUENTES (FAQ)
       ============================================================================ -->
  <section class="section-padding" style="background-color: #FFFFFF; border-top: 1px solid var(--color-border-light);">
    <div class="container" style="max-width: 800px;">
      <div class="text-center" style="margin-bottom: 3rem;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Preguntas Frecuentes</span>
        <h2 style="margin-bottom: 0.5rem;">¿Tenés dudas sobre cómo comprar o solicitar un servicio?</h2>
        <p style="color: var(--color-text-secondary);">Respuestas rápidas a las consultas más habituales de nuestros clientes.</p>
      </div>

      <div style="display: flex; flex-direction: column; gap: 1rem;">
        <?php foreach ($faqs as $faq): ?>
          <details style="background: var(--color-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 1.25rem; cursor: pointer; box-shadow: var(--shadow-sm);">
            <summary style="font-family: var(--font-heading); font-weight: 700; font-size: 1.05rem; color: var(--color-dark); outline: none;">
              <?= htmlspecialchars($faq['question']) ?>
            </summary>
            <p style="font-size: 0.95rem; color: var(--color-text-secondary); line-height: 1.6; margin-top: 0.85rem; margin-bottom: 0;">
              <?= htmlspecialchars($faq['answer']) ?>
            </p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============================================================================
       6. FRANJA DE CONTACTO RÁPIDO
       ============================================================================ -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 3.5rem 0;" class="section-padding">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem; background: var(--color-dark-surface); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08);">
        <div>
          <h2 style="color: #FFFFFF; margin-bottom: 0.5rem; font-size: 1.6rem;">¿Necesitás asesoramiento o un presupuesto?</h2>
          <p style="color: #B0B0B0; margin-bottom: 0;">
            Visitá nuestro local en <strong>Atlántida, Uruguay</strong> o comunicate directo con nuestros técnicos vía WhatsApp.
          </p>
        </div>
        <div>
          <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20consultar%20por%20un%20producto%20o%20servicio" 
             target="_blank" 
             rel="noopener noreferrer" 
             class="btn btn-primary btn-lg"
             style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
            Contactar por WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- SCRIPT PARA EL FUNCIONAMIENTO DEL CARRUSEL (AUTO-PLAY & INTERACTIVO) -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const slidesContainer = document.getElementById('carouselSlides');
    const prevBtn         = document.getElementById('carouselPrev');
    const nextBtn         = document.getElementById('carouselNext');
    const dots            = document.querySelectorAll('.carousel-dot');
    const totalSlides     = 3;
    let currentSlide      = 0;
    let autoPlayTimer     = null;

    function goToSlide(index) {
      if (index < 0) index = totalSlides - 1;
      if (index >= totalSlides) index = 0;
      currentSlide = index;

      if (slidesContainer) {
        slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
      }

      dots.forEach((dot, idx) => {
        if (idx === currentSlide) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoPlayTimer = setInterval(function() {
        goToSlide(currentSlide + 1);
      }, 5500);
    }

    function stopAutoPlay() {
      if (autoPlayTimer) clearInterval(autoPlayTimer);
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function() {
        goToSlide(currentSlide - 1);
        startAutoPlay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', function() {
        goToSlide(currentSlide + 1);
        startAutoPlay();
      });
    }

    dots.forEach((dot, idx) => {
      dot.addEventListener('click', function() {
        goToSlide(idx);
        startAutoPlay();
      });
    });

    const carouselContainer = document.getElementById('heroCarousel');
    if (carouselContainer) {
      carouselContainer.addEventListener('mouseenter', stopAutoPlay);
      carouselContainer.addEventListener('mouseleave', startAutoPlay);
    }

    startAutoPlay();
  });
  </script>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
