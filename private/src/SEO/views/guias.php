<?php
/**
 * RedTec Informática - Vista Individual de Guías de Ayuda SEO
 * 
 * @var array $guia Datos de la guía activa
 */

$content = function() use ($guia) {
?>
  <article class="section-padding">
    <div class="container" style="max-width: 860px;">
      
      <!-- Migas de Pan -->
      <nav aria-label="Breadcrumb" style="margin-bottom: 1.5rem; font-size: 0.88rem; color: var(--color-text-secondary);">
        <a href="<?= url('/') ?>" style="color: inherit; text-decoration: none;">Inicio</a> &rsaquo; 
        <a href="<?= url('/guias') ?>" style="color: inherit; text-decoration: none;">Guías</a> &rsaquo; 
        <span style="color: var(--color-dark); font-weight: 600;"><?= htmlspecialchars($guia['h1']) ?></span>
      </nav>

      <!-- Encabezado Principal Único H1 -->
      <header style="margin-bottom: 2.5rem;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">
          Guía de Asesoramiento Técnico &bull; RedTec Atlántida
        </span>
        <h1 style="font-size: 2.25rem; font-weight: 800; line-height: 1.25; margin-top: 0.5rem; margin-bottom: 1rem; color: var(--color-dark);">
          <?= htmlspecialchars($guia['h1']) ?>
        </h1>
        <p style="font-size: 1.125rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 0;">
          <?= htmlspecialchars($guia['summary']) ?>
        </p>
      </header>

      <!-- Contenido de Secciones -->
      <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 3rem;">
        <?php foreach ($guia['sections'] as $sec): ?>
          <section style="background: #FFFFFF; padding: 1.75rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light); box-shadow: var(--shadow-sm);">
            <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.85rem;">
              <?= htmlspecialchars($sec['h2']) ?>
            </h2>
            <p style="font-size: 1rem; color: var(--color-text-main); line-height: 1.7; margin-bottom: 0;">
              <?= htmlspecialchars($sec['p']) ?>
            </p>
          </section>
        <?php endforeach; ?>
      </div>

      <!-- Preguntas Frecuentes si existen -->
      <?php if (!empty($guia['faqs'])): ?>
        <section style="margin-bottom: 3rem; background: var(--color-bg); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--color-border-light);">
          <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--color-dark); margin-bottom: 1.25rem;">
            Preguntas Frecuentes Relacionadas
          </h2>
          <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($guia['faqs'] as $faq): ?>
              <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.5rem;">
                  <?= htmlspecialchars($faq['question']) ?>
                </h3>
                <p style="font-size: 0.95rem; color: var(--color-text-secondary); margin-bottom: 0; line-height: 1.6;">
                  <?= htmlspecialchars($faq['answer']) ?>
                </p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <!-- Banner Call To Action / Enlazado Interno -->
      <div style="background: var(--color-dark); color: #FFFFFF; padding: 2.5rem; border-radius: var(--radius-lg); text-align: center;">
        <h3 style="color: #FFFFFF; font-size: 1.5rem; margin-bottom: 0.75rem; font-weight: 800;">
          <?= htmlspecialchars($guia['cta_text']) ?>
        </h3>
        <p style="color: #B0B0B0; margin-bottom: 1.5rem; font-size: 1rem;">
          Estamos en Atlántida, Canelones. Asesorate con nuestros técnicos especializados por WhatsApp o en nuestro local.
        </p>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
          <a href="<?= url($guia['cta_link']) ?>" class="btn btn-primary btn-lg">
            <?= htmlspecialchars($guia['cta_label']) ?>
          </a>
          <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20le%C3%AD%20la%20gu%C3%ADa%20sobre%20<?= urlencode($guia['slug']) ?>%20y%20quisiera%20consultar" 
             target="_blank" 
             rel="noopener noreferrer" 
             class="btn btn-outline-dark btn-lg" 
             style="color: #FFFFFF; border-color: rgba(255,255,255,0.4);">
            Consultar por WhatsApp
          </a>
        </div>
      </div>

    </div>
  </article>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
