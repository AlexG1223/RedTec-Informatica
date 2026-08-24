<?php
/**
 * RedTec Informática - Vista de Servicios Técnicos e Infraestructura
 * 
 * @var array $servicios Lista de servicios técnicos traídos desde ServicioRepository
 */

$content = function() use ($servicios) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <!-- CABECERA -->
  <div style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #B0B0B0; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #B0B0B0;">Inicio</a> &rarr; 
        <span style="color: var(--color-primary); font-weight: 700;">Servicios Técnicos</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.5rem; font-weight: 800;">Servicios Técnicos e Infraestructura</h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 1.05rem; max-width: 700px;">
        Instalación de cámaras de seguridad CCTV, servidores, redes informáticas y soporte especializado en Atlántida y Canelones.
      </p>
    </div>
  </div>

  <section class="section-padding">
    <div class="container">
      
      <?php if (empty($servicios)): ?>
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 3rem; text-align: center;">
          <h3 style="color: var(--color-dark);">No hay servicios registrados</h3>
          <p style="color: var(--color-text-secondary);">Comunicate por WhatsApp para solicitar un presupuesto personalizado.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-3" style="gap: 2rem;">
          <?php foreach ($servicios as $s): ?>
            <?php 
              $sTitle = htmlspecialchars($s['name'] ?? $s['title'] ?? '');
              $sDesc  = nl2br(htmlspecialchars($s['description'] ?? ''));
              $sPrice = !empty($s['price']) ? '$ ' . number_format((float)$s['price'], 2, '.', ',') : 'Consultar Presupuesto';
              $rawImg = !empty($s['image_url']) ? $s['image_url'] : null;
              $sImg   = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
              $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode("Hola RedTec, quisiera consultar por el servicio: " . ($s['name'] ?? $s['title'] ?? ''));
            ?>
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; transition: transform var(--transition-normal), box-shadow var(--transition-normal);"
                 onmouseenter="this.style.transform='translateY(-6px)'; this.style.boxShadow='var(--shadow-lg)'; this.style.borderColor='var(--color-primary)';"
                 onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--color-border-light)';">
              
              <div style="height: 180px; background: var(--color-dark); overflow: hidden; position: relative;">
                <img src="<?= $sImg ?>" alt="<?= $sTitle ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;" onerror="this.src='<?= $fallbackImg ?>';">
                <div style="position: absolute; bottom: 12px; left: 12px; background: rgba(31, 19, 15, 0.85); color: #FFF; padding: 0.25rem 0.65rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.2);">
                  Soporte Especializado
                </div>
              </div>

              <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: var(--color-dark); font-weight: 800;"><?= $sTitle ?></h3>
                
                <div style="font-size: 0.92rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1;">
                  <?= $sDesc ?>
                </div>

                <div style="padding-top: 1rem; border-top: 1px dashed var(--color-border-light); margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                  <span style="font-family: var(--font-heading); font-size: 1rem; font-weight: 800; color: var(--color-primary);">
                    <?= $sPrice ?>
                  </span>

                  <a href="<?= $waLink ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);">
                    Consultar WhatsApp
                  </a>
                </div>
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
