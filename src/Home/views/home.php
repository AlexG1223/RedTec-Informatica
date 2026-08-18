<?php
/**
 * RedTec Informática - Vista de la Página de Inicio (Home)
 * Diseño inspirado en DK Computers adaptado con la Paleta de Marca RedTec
 * 
 * @var array $categories Lista de categorías traídas de la BD.
 * @var array $faqs Preguntas frecuentes para usuarios y buscadores.
 */

$content = function() use ($categories, $faqs) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <!-- ============================================================================
       1. HERO BANNER PROMOCIONAL (ESTILO DK COMPUTERS DELL / LENOVO PROMO)
       ============================================================================ -->
  <section class="hero-dk-promo" style="background: linear-gradient(135deg, #170E0C 0%, #291512 50%, #3B1617 100%); color: #FFFFFF; position: relative; overflow: hidden; border-bottom: 4px solid var(--color-primary); padding: 3.5rem 0;">
    
    <!-- Resplandor Rojo RedTec de Fondo -->
    <div style="position: absolute; right: -5%; top: -20%; width: 550px; height: 550px; background: radial-gradient(circle, rgba(227, 69, 73, 0.22) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="container">
      <div style="display: grid; grid-template-columns: 1fr; gap: 2.5rem; align-items: center;" class="grid-hero-promo">
        
        <!-- Contenido Texto Hero -->
        <div style="z-index: 2;">
          <span style="display: inline-block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.12em; background: rgba(227, 69, 73, 0.15); padding: 0.4rem 0.95rem; border-radius: var(--radius-full); margin-bottom: 1.25rem; border: 1px solid rgba(227, 69, 73, 0.35);">
            DELL LATITUDE &bull; LENOVO THINKPAD
          </span>

          <h1 style="color: #FFFFFF; font-size: clamp(2.1rem, 4.2vw, 3.4rem); line-height: 1.1; margin-bottom: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: -0.01em;">
            DISEÑADAS PARA DESTACAR
          </h1>

          <p style="color: #E0E0E0; font-size: clamp(1.05rem, 1.8vw, 1.25rem); line-height: 1.5; margin-bottom: 1.75rem; max-width: 620px;">
            Portabilidad elegante y rendimiento inteligente para profesionales y empresas. Equipos de alta gama testeados con garantía.
          </p>

          <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; margin-bottom: 2rem;">
            <span style="font-family: var(--font-heading); font-size: 0.82rem; font-weight: 700; color: #B0B0B0; text-transform: uppercase; letter-spacing: 0.05em; border-right: 1px solid rgba(255,255,255,0.2); padding-right: 0.75rem;">Modelos Destacados:</span>
            <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 4px;">5300</span>
            <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 4px;">5310</span>
            <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 4px;">5320</span>
            <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 4px;">5410</span>
            <span style="font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; color: #FFFFFF; background: rgba(255,255,255,0.08); padding: 0.2rem 0.6rem; border-radius: 4px;">7480</span>
          </div>

          <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <a href="<?= url('/tienda?buscar=Notebook') ?>" class="btn btn-primary btn-lg">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
              Ver Notebooks
            </a>
            <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20consultar%20por%20las%20notebooks%20Dell/Lenovo" target="_blank" rel="noopener noreferrer" class="btn btn-outline btn-lg" style="color: #FFFFFF; border-color: rgba(255,255,255,0.6);">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              Consulta Directa
            </a>
          </div>
        </div>

        <!-- Imagen Banner Producto -->
        <div style="position: relative; text-align: center;">
          <img src="<?= url('/assets/img/banner_notebooks_redtec.jpg') ?>" 
               alt="Dell Latitude & Lenovo Thinkpad RedTec" 
               style="width: 100%; max-width: 580px; height: auto; border-radius: var(--radius-lg); box-shadow: 0 12px 36px rgba(0,0,0,0.6); border: 2px solid rgba(227, 69, 73, 0.4);"
               onerror="this.src='<?= $fallbackImg ?>';">
        </div>

      </div>
    </div>
  </section>

  <!-- ============================================================================
       2. TARJETAS DE CATEGORÍA CON OVERLAY VISUAL (ESTILO DK COMPUTERS)
       ============================================================================ -->
  <section style="background-color: var(--color-dark); padding: 1.5rem 0 3.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
      
      <div class="grid grid-3" style="gap: 1.25rem;">
        
        <!-- CARD OVERLAY 1: NOTEBOOKS -->
        <a href="<?= url('/tienda?buscar=Notebook') ?>" class="category-dk-overlay-card">
          <div class="category-dk-bg" style="background-image: url('<?= url('/assets/img/cat_overlay_notebooks.jpg') ?>');"></div>
          <div class="category-dk-gradient"></div>
          <div class="category-dk-content">
            <h2 class="category-dk-title">NOTEBOOKS</h2>
            <span class="category-dk-link">Ver catálogo &rarr;</span>
          </div>
        </a>

        <!-- CARD OVERLAY 2: GABINETES & EQUIPOS -->
        <a href="<?= url('/tienda?buscar=Equipos') ?>" class="category-dk-overlay-card">
          <div class="category-dk-bg" style="background-image: url('<?= url('/assets/img/cat_overlay_gabinetes.jpg') ?>');"></div>
          <div class="category-dk-gradient"></div>
          <div class="category-dk-content">
            <h2 class="category-dk-title">GABINETES & GAMING</h2>
            <span class="category-dk-link">Ver catálogo &rarr;</span>
          </div>
        </a>

        <!-- CARD OVERLAY 3: CÁMARAS CCTV & SEGURIDAD -->
        <a href="<?= url('/tienda?buscar=Camara') ?>" class="category-dk-overlay-card">
          <div class="category-dk-bg" style="background-image: url('<?= url('/assets/img/cat_overlay_seguridad.jpg') ?>');"></div>
          <div class="category-dk-gradient"></div>
          <div class="category-dk-content">
            <h2 class="category-dk-title">SEGURIDAD & CCTV</h2>
            <span class="category-dk-link">Ver catálogo &rarr;</span>
          </div>
        </a>

      </div>

    </div>
  </section>

  <!-- ============================================================================
       3. CATEGORÍAS DESTACADAS (CONECTADAS A BASE DE DATOS)
       ============================================================================ -->
  <section class="section-padding">
    <div class="container">
      <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">Explorá nuestra tienda</span>
          <h2 style="margin-bottom: 0;">Todas las Categorías</h2>
        </div>
        <a href="<?= url('/tienda') ?>" class="btn btn-outline-dark btn-sm">Ver catálogo completo &rarr;</a>
      </div>

      <div class="grid grid-4">
        <?php foreach ($categories as $category): ?>
          <?php 
            $catId   = (int)$category['id'];
            $catName = htmlspecialchars($category['name']);
            $rawImg  = !empty($category['image_url']) ? $category['image_url'] : '/assets/img/redtec.jpeg';
            $catImg  = strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg);
            $catLink = url('/tienda?categoria=' . $catId);
          ?>
          <a href="<?= $catLink ?>" class="category-card" style="display: block; text-decoration: none; color: inherit;">
            <div style="background: var(--color-card-bg); border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform var(--transition-normal), box-shadow var(--transition-normal), border-color var(--transition-normal);"
                 onmouseenter="this.style.transform='translateY(-6px)'; this.style.boxShadow='var(--shadow-lg)'; this.style.borderColor='var(--color-primary)';"
                 onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--color-border-light)';">
              
              <div style="height: 170px; background: #FFFFFF; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid var(--color-border-light);">
                <img src="<?= $catImg ?>" 
                     alt="<?= $catName ?>" 
                     style="width: 100%; height: 100%; object-fit: cover; transition: transform var(--transition-normal);"
                     onerror="this.src='<?= $fallbackImg ?>';">
              </div>

              <div style="padding: 1.25rem; text-align: center;">
                <h3 style="font-size: 1.05rem; margin-bottom: 0.35rem; color: var(--color-dark); font-weight: 700;"><?= $catName ?></h3>
                <span style="font-size: 0.85rem; color: var(--color-primary); font-weight: 600;">Ver productos &rarr;</span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============================================================================
       4. SERVICIOS INSTITUCIONALES (PREVIEW)
       ============================================================================ -->
  <section style="background-color: #FFFFFF; border-top: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light);" class="section-padding">
    <div class="container">
      <div class="text-center" style="max-width: 680px; margin: 0 auto 3rem auto;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">Soluciones Profesionales</span>
        <h2>Servicios Técnicos e Infraestructura</h2>
        <p>Brindamos asistencia técnica especializada en sitio y remota para pequeñas, medianas y grandes empresas.</p>
      </div>

      <div class="grid grid-3">
        <!-- Servicio 1 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column;">
          <div style="width: 52px; height: 52px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Cámaras de Seguridad (CCTV)</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Diseño e instalación de sistemas de videovigilancia IP y analógicos de alta definición con monitoreo remoto en smartphone.
          </p>
          <a href="<?= url('/servicios#cctv') ?>" style="font-weight: 600; font-size: 0.9rem; color: var(--color-primary);">Más información &rarr;</a>
        </div>

        <!-- Servicio 2 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column;">
          <div style="width: 52px; height: 52px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Servidores & Almacenamiento</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Implementación de servidores de dominio, sistemas de respaldo automatizado en NAS y virtualización para PyMEs.
          </p>
          <a href="<?= url('/servicios#servidores') ?>" style="font-weight: 600; font-size: 0.9rem; color: var(--color-primary);">Más información &rarr;</a>
        </div>

        <!-- Servicio 3 -->
        <div style="background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); display: flex; flex-direction: column;">
          <div style="width: 52px; height: 52px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
          </div>
          <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem;">Redes & Cableado Estructurado</h3>
          <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1;">
            Cableado UTP Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi Mesh de alta cobertura.
          </p>
          <a href="<?= url('/servicios#redes') ?>" style="font-weight: 600; font-size: 0.9rem; color: var(--color-primary);">Más información &rarr;</a>
        </div>
      </div>

      <div class="text-center" style="margin-top: 2.5rem;">
        <a href="<?= url('/servicios') ?>" class="btn btn-primary btn-lg">Conocé nuestros servicios</a>
      </div>
    </div>
  </section>

  <!-- ============================================================================
       5. PREGUNTAS FRECUENTES (FAQ & SEO / GEO)
       ============================================================================ -->
  <section class="section-padding">
    <div class="container" style="max-width: 800px;">
      <div class="text-center" style="margin-bottom: 3rem;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">Preguntas Frecuentes</span>
        <h2 style="margin-bottom: 0.5rem;">¿Tenés dudas sobre cómo comprar o solicitar un servicio?</h2>
        <p style="color: var(--color-text-secondary);">Respuestas rápidas a las consultas más habituales de nuestros clientes.</p>
      </div>

      <div style="display: flex; flex-direction: column; gap: 1rem;">
        <?php foreach ($faqs as $faq): ?>
          <details style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 1.25rem; cursor: pointer; box-shadow: var(--shadow-sm);">
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
       6. FRANJA DE CONTACTO RÁPIDO Y UBICACIÓN
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

<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
