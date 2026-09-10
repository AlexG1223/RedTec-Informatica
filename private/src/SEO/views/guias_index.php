<?php
/**
 * RedTec Informática - Vista de Índice General de Guías de Ayuda SEO
 * 
 * @var array $guias Lista de guías disponibles
 */

$content = function() use ($guias) {
?>
  <section class="section-padding">
    <div class="container" style="max-width: 900px;">
      
      <header style="margin-bottom: 2.5rem; text-center">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">
          Centro de Ayuda & Asesoramiento
        </span>
        <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--color-dark); margin-top: 0.5rem; margin-bottom: 0.75rem;">
          Guías de Ayuda y Diagnóstico Técnico
        </h1>
        <p style="font-size: 1.1rem; color: var(--color-text-secondary); margin-bottom: 0;">
          Soluciones prácticas para problemas habituales de computadoras, optimizaciones y recomendaciones de repuestos en Atlántida, Uruguay.
        </p>
      </header>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.5rem;">
        <?php foreach ($guias as $slug => $item): ?>
          <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 1.75rem; display: flex; flex-direction: column; box-shadow: var(--shadow-sm); transition: transform var(--transition-normal);"
               onmouseenter="this.style.transform='translateY(-3px)';"
               onmouseleave="this.style.transform='translateY(0)';">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.75rem; line-height: 1.35;">
              <?= htmlspecialchars($item['h1']) ?>
            </h2>
            <p style="font-size: 0.9375rem; color: var(--color-text-secondary); margin-bottom: 1.5rem; flex-grow: 1; line-height: 1.6;">
              <?= htmlspecialchars($item['summary']) ?>
            </p>
            <a href="<?= url('/guias/' . $slug) ?>" class="btn btn-outline-dark btn-sm" style="align-self: flex-start;">
              Leer Guía Completa &rarr;
            </a>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
