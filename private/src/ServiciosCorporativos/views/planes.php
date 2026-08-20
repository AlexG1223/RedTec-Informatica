<?php
/**
 * RedTec Informática - Vista de Planes de Soporte Corporativo para PyMEs
 * 
 * @var array $planes Lista de paquetes corporativos traídos desde ServicioPackageRepository
 */

$content = function() use ($planes) {
?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <span style="color: var(--color-primary); font-weight: 700;">Planes Corporativos</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.5rem; font-weight: 800;">Abonos Mensuales de Mantenimiento para PyMEs</h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 1.05rem; max-width: 750px;">
        Tranquilidad informática garantizada. Asistencia técnica preventiva, soporte in-situ en Canelones y respuesta prioritaria.
      </p>
    </div>
  </div>

  <section class="section-padding">
    <div class="container">
      
      <?php if (empty($planes)): ?>
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 3rem; text-align: center;">
          <h3 style="color: var(--color-dark);">No hay planes corporativos registrados</h3>
          <p style="color: var(--color-text-secondary);">Comunicate por WhatsApp para solicitar un abono a la medida de tu empresa.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-3" style="gap: 2rem;">
          <?php foreach ($planes as $p): ?>
            <?php 
              $pTitle = htmlspecialchars($p['name']);
              $pDesc  = nl2br(htmlspecialchars($p['description']));
              $pPrice = !empty($p['price']) ? 'USD $' . number_format((float)$p['price'], 2, '.', ',') . ' / mes' : 'Consultar';
              $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode("Hola RedTec, quisiera consultar por el plan corporativo: " . $p['name']);
            ?>
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; position: relative; transition: transform var(--transition-normal), box-shadow var(--transition-normal);"
                 onmouseenter="this.style.transform='translateY(-6px)'; this.style.boxShadow='var(--shadow-lg)'; this.style.borderColor='var(--color-primary)';"
                 onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--color-border-light)';">
              
              <div style="margin-bottom: 1.5rem; text-align: center; border-bottom: 2px solid var(--color-bg); padding-bottom: 1.25rem;">
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--color-primary); letter-spacing: 0.08em; background: var(--color-primary-light); padding: 0.25rem 0.65rem; border-radius: var(--radius-sm);">
                  Abono Mensual PyME
                </span>
                <h3 style="font-size: 1.4rem; margin-top: 0.75rem; margin-bottom: 0.5rem; color: var(--color-dark); font-weight: 800;"><?= $pTitle ?></h3>
                
                <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; color: var(--color-primary); margin-top: 0.5rem;">
                  <?= $pPrice ?>
                </div>
              </div>

              <div style="font-size: 0.92rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
                <?= $pDesc ?>
              </div>

              <div style="margin-top: auto;">
                <a href="<?= $waLink ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-block btn-lg" style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
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
