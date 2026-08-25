<?php
/**
 * RedTec Informática - Vista de Servicios Técnicos e Infraestructura (Mobile Responsive)
 * 
 * @var array $servicios Lista de servicios técnicos traídos desde ServicioRepository
 */

$content = function () use ($servicios) {
  $fallbackImg = url('/assets/img/redtec.jpeg');
  if (empty($servicios)) {
    $servicios = [
      [
        'id'          => 1,
        'name'        => 'Seguridad Informática y Resguardos',
        'description' => 'Implementación de firewalls de red, antivirus corporativo administrado y planes de copia de seguridad.',
        'image_url'   => '/assets/img/redtec.jpeg',
        'active'      => 1
      ],
      [
        'id'          => 2,
        'name'        => 'Mantenimiento y Soporte Técnico In-Situ',
        'description' => 'Reparación de hardware, mantenimiento preventivo de equipamiento, limpieza técnica y asistencia presencial.',
        'image_url'   => null,
        'active'      => 1
      ],
      [
        'id'          => 3,
        'name'        => 'Redes y Conectividad',
        'description' => 'Cableado estructurado Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi empresariales Mesh.',
        'image_url'   => '/assets/img/redtec.jpeg',
        'active'      => 1
      ],
      [
        'id'          => 4,
        'name'        => 'Armado y Configuración de Servidores',
        'description' => 'Implementación de servidores de archivos, Active Directory, virtualización y sistemas de respaldo automatizados en unidades NAS.',
        'image_url'   => null,
        'active'      => 1
      ],
      [
        'id'          => 5,
        'name'        => 'Instalación de Cámaras de Seguridad (CCTV)',
        'description' => 'Diseño e instalación de sistemas de videovigilancia IP y analógicas HD para empresas y residencias con monitoreo remoto.',
        'image_url'   => '/assets/img/redtec.jpeg',
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
        <span style="color: var(--color-primary); font-weight: 700;">Servicios Técnicos</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.5rem; font-weight: 800; font-size: clamp(1.5rem, 4vw, 2.2rem);">
        Servicios Técnicos e Infraestructura
      </h1>
      <p style="color: #D2D2D2; margin-bottom: 0; font-size: 1rem; max-width: 700px; line-height: 1.5;">
        Instalación de cámaras de seguridad, servidores, redes informáticas y soporte especializado.
      </p>
    </div>
  </div>

  <section class="section-padding" style="overflow-x: hidden;">
    <div class="container">

      <?php if (empty($servicios)): ?>
        <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: 2.5rem 1.5rem; text-align: center;">
          <h3 style="color: var(--color-dark);">No hay servicios registrados</h3>
          <p style="color: var(--color-text-secondary);">Comunicate por WhatsApp para solicitar un presupuesto personalizado.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-3" style="gap: 1.5rem; width: 100%;">
          <?php foreach ($servicios as $s): ?>
            <?php
            $sTitle = htmlspecialchars($s['name'] ?? $s['title'] ?? '');
            $sDesc  = nl2br(htmlspecialchars($s['description'] ?? ''));
            $sPrice = !empty($s['price']) ? '$ ' . number_format((float) $s['price'], 2, '.', ',') : 'Consultar Presupuesto';
            $rawImg = !empty($s['image_url']) ? $s['image_url'] : null;
            $sImg   = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
            $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode("Hola RedTec, quisiera consultar por el servicio: " . ($s['name'] ?? $s['title'] ?? ''));
            ?>
            <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; width: 100%; box-sizing: border-box; transition: transform var(--transition-normal), box-shadow var(--transition-normal);">

              <div style="height: 180px; background: var(--color-dark); overflow: hidden; position: relative;">
                <img src="<?= $sImg ?>" alt="<?= $sTitle ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8;" onerror="this.src='<?= $fallbackImg ?>';">
                <div style="position: absolute; bottom: 12px; left: 12px; background: rgba(31, 19, 15, 0.85); color: #FFF; padding: 0.25rem 0.65rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.2);">
                  Soporte Especializado
                </div>
              </div>

              <div style="padding: 1.25rem; display: flex; flex-direction: column; flex-grow: 1;">
                <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem; color: var(--color-dark); font-weight: 800; line-height: 1.3;">
                  <?= $sTitle ?>
                </h3>

                <div style="font-size: 0.9rem; color: var(--color-text-secondary); line-height: 1.55; margin-bottom: 1.25rem; flex-grow: 1;">
                  <?= $sDesc ?>
                </div>

                <div style="padding-top: 0.85rem; border-top: 1px dashed var(--color-border-light); margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap;">
                  <span style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 800; color: var(--color-primary);">
                    <?= $sPrice ?>
                  </span>

                  <a href="<?= $waLink ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); white-space: normal; text-align: center;">
                    Consultar WhatsApp
                  </a>
                </div>
              </div>

            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- BANNER DIRECTO A PLANES CORPORATIVOS -->
      <div style="margin-top: 2.5rem; background: linear-gradient(135deg, var(--color-dark) 0%, #2A1D1A 100%); color: #FFF; padding: 2rem 1.5rem; border-radius: var(--radius-lg); border-left: 5px solid var(--color-primary); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem; width: 100%; box-sizing: border-box;">
        <div style="max-width: 600px;">
          <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--color-primary); letter-spacing: 0.06em; display: block; margin-bottom: 0.25rem;">
            ¿Buscás mantenimiento informático para tu empresa?
          </span>
          <h3 style="font-size: 1.35rem; margin: 0 0 0.5rem 0; color: #FFF; font-weight: 800; line-height: 1.3;">
            Conocé nuestros Planes de Soporte Corporativo para PyMEs
          </h3>
          <p style="margin: 0; color: #D2D2D2; font-size: 0.9rem; line-height: 1.4;">
            Abonos mensuales con asistencia técnica prioritaria, soporte in-situ y mantenimiento preventivo.
          </p>
        </div>
        <a href="<?= url('/servicios-corporativos') ?>" class="btn btn-primary btn-lg" style="white-space: normal; text-align: center; font-size: 0.95rem; padding: 0.75rem 1.25rem;">
          Ver Planes Corporativos &rarr;
        </a>
      </div>

    </div>
  </section>
  <?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';
