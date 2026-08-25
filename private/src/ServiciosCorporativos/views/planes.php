<?php
/**
 * RedTec Informática - Vista de Planes de Soporte Corporativo para PyMEs (Mobile Responsive)
 * 
 * @var array $planes Lista de paquetes corporativos traídos desde ServicioPackageRepository
 */

$content = function () use ($planes) {
  if (empty($planes)) {
    $planes = [
      [
        'id'          => 1,
        'name'        => 'Esencial',
        'description' => 'Soporte técnico reactivo remoto y presencial con tiempo de respuesta estándar para pequeñas oficinas y negocios (hasta 5 equipos).',
        'price'       => null,
        'active'      => 1
      ],
      [
        'id'          => 2,
        'name'        => 'Empresarial',
        'description' => 'Soporte prioritario, mantenimiento preventivo mensual, monitoreo de infraestructura y asistencia in-situ para PyMEs (hasta 15 equipos).',
        'price'       => null,
        'active'      => 1
      ],
      [
        'id'          => 3,
        'name'        => 'Premium',
        'description' => 'Soporte prioritario 24/7, servidor y red monitoreados en tiempo real, tiempo de respuesta SLA garantizado y técnico dedicado.',
        'price'       => null,
        'active'      => 1
      ]
    ];
  }
  ?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 2.5rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr;
        <span style="color: var(--color-primary); font-weight: 700;">Planes Corporativos</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.5rem; font-weight: 800; font-size: clamp(1.5rem, 4vw, 2.2rem);">
        Abonos Mensuales de Mantenimiento para PyMEs
      </h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 1rem; max-width: 750px; line-height: 1.5;">
        Tranquilidad informática garantizada. Asistencia técnica preventiva, soporte in-situ y respuesta prioritaria para PyMEs y empresas.
      </p>
    </div>
  </div>

  <section class="section-padding" style="overflow-x: hidden;">
    <div class="container">

      <?php if (empty($planes)): ?>
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 2.5rem 1.5rem; text-align: center;">
          <h3 style="color: var(--color-dark);">No hay planes corporativos registrados</h3>
          <p style="color: var(--color-text-secondary);">Comunicate por WhatsApp para solicitar un abono a la medida de tu empresa.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-3" style="gap: 1.5rem; width: 100%;">
          <?php foreach ($planes as $p): ?>
            <?php
            $pTitle = htmlspecialchars($p['name']);
            $pDesc  = nl2br(htmlspecialchars($p['description']));
            $pPrice = !empty($p['price']) ? '$ ' . number_format((float) $p['price'], 2, '.', ',') . ' / mes' : 'Consultar';
            $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode("Hola RedTec, quisiera consultar por el plan corporativo: " . $p['name']);
            ?>
            <div class="plan-card" style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 1.75rem 1.25rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%; box-sizing: border-box; transition: transform var(--transition-normal), box-shadow var(--transition-normal);">

              <div style="margin-bottom: 1.25rem; text-align: center; border-bottom: 2px solid var(--color-bg); padding-bottom: 1rem;">
                <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--color-primary); letter-spacing: 0.06em; background: var(--color-primary-light); padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); display: inline-block;">
                  Abono Mensual PyME
                </span>
                <h3 style="font-size: 1.35rem; margin-top: 0.65rem; margin-bottom: 0.35rem; color: var(--color-dark); font-weight: 800;">
                  <?= $pTitle ?>
                </h3>

                <div style="font-family: var(--font-heading); font-size: 1.65rem; font-weight: 900; color: var(--color-primary); margin-top: 0.4rem;">
                  <?= $pPrice ?>
                </div>
              </div>

              <div style="font-size: 0.9rem; color: var(--color-text-secondary); line-height: 1.55; margin-bottom: 1.5rem; flex-grow: 1;">
                <?= $pDesc ?>
              </div>

              <div style="margin-top: auto; width: 100%;">
                <a href="<?= $waLink ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-block" style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); white-space: normal; text-align: center; padding: 0.75rem 1rem; font-size: 0.88rem; line-height: 1.3; width: 100%; box-sizing: border-box;">
                  Solicitar Plan por WhatsApp
                </a>
              </div>

            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </section>
  <?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
