<?php
/**
 * RedTec Informática - Vista de Planes de Soporte Corporativo para PyMEs
 * 
 * @var array $planes Lista de paquetes corporativos traídos desde ServicioPackageRepository
 */

$content = function () use ($planes) {
  $defaultPlanes = [
    [
      'id'          => 1,
      'number'      => 'PLAN 1',
      'name'        => 'ESENCIAL',
      'subtitle'    => 'Nos ocupamos de que tu tecnología funcione para que vos puedas dedicarte a tu empresa.',
      'includes_tag'=> 'INCLUYE',
      'features'    => [
        'Soporte remoto y asistencia por WhatsApp / Correo',
        'Mantenimiento preventivo',
        'Monitoreo básico de equipos',
        'Visitas planificadas',
        'Informe mensual básico'
      ],
      'footer_phrase' => 'TRANQUILIDAD PARA TU DÍA A DÍA | SOPORTE CONFIABLE, SIEMPRE.',
      'accent_color'  => '#E11D48',
      'bg_gradient'   => 'linear-gradient(145deg, #18181B 0%, #09090B 100%)',
      'border_color'  => 'rgba(225, 29, 72, 0.4)'
    ],
    [
      'id'          => 2,
      'number'      => 'PLAN 2',
      'name'        => 'PROFESIONAL',
      'subtitle'    => 'Más control, más prevención, más eficiencia para que tu empresa no se detenga.',
      'includes_tag'=> 'INCLUYE TODO DEL PLAN 1, MÁS:',
      'features'    => [
        'Mayor prioridad en la atención',
        'Visitas técnicas (según plan)',
        'Monitoreo activo de sistemas y red',
        'Supervisión de respaldos',
        'Actualizaciones y parches',
        'Reportes mensuales detallados',
        'Capacitación básica para tu equipo'
      ],
      'footer_phrase' => 'MÁS PREVENCIÓN, MENOS PROBLEMAS | TU EMPRESA SIEMPRE UN PASO ADELANTE.',
      'accent_color'  => '#EF4444',
      'bg_gradient'   => 'linear-gradient(145deg, #1F1924 0%, #0F0D15 100%)',
      'border_color'  => 'rgba(239, 68, 68, 0.6)'
    ],
    [
      'id'          => 3,
      'number'      => 'PLAN 3',
      'name'        => 'CONTINUIDAD 360°',
      'subtitle'    => 'La solución integral para empresas que no pueden detenerse.',
      'banner'      => 'SOPORTE INTEGRAL: REDES, SERVIDORES, WIFI, CÁMARAS, ACCESOS, RESPALDOS, ETC.',
      'includes_tag'=> 'INCLUYE TODO DEL PLAN 2, MÁS:',
      'features'    => [
        'Atención de emergencias 24/7',
        'Respuesta inmediata ante incidentes críticos',
        'Soporte integral (redes, servidores, WiFi, cámaras, accesos, respaldos, etc.)',
        'Planificación y optimización de infraestructura',
        'Gestión proactiva y mejoras continuas',
        'Equipos de respaldo en caso de fallas',
        'Reuniones periódicas de seguimiento estratégico'
      ],
      'footer_phrase' => 'MÁXIMA CONTINUIDAD, MÁXIMA TRANQUILIDAD | TU DEPARTAMENTO IT EXTERNO.',
      'accent_color'  => '#EAB308',
      'bg_gradient'   => 'linear-gradient(145deg, #261D11 0%, #120E08 100%)',
      'border_color'  => 'rgba(234, 179, 8, 0.6)'
    ]
  ];

  // Si vienen planes desde BD, mapear sus campos dinámicamente
  $mappedPlanes = [];
  if (!empty($planes)) {
    foreach ($planes as $idx => $p) {
      $idNum = $idx + 1;
      $refSample = $defaultPlanes[$idx] ?? $defaultPlanes[0];
      
      $badge = !empty($p['badge']) ? trim($p['badge']) : ('PLAN ' . $idNum);
      $name  = !empty($p['name'])  ? trim($p['name'])  : $refSample['name'];
      $subtitle = !empty($p['description']) ? trim($p['description']) : $refSample['subtitle'];
      $banner   = !empty($p['banner'])      ? trim($p['banner'])      : ($refSample['banner'] ?? null);
      $includesTag = !empty($p['includes_tag']) ? trim($p['includes_tag']) : $refSample['includes_tag'];

      $features = [];
      $rawIncludes = trim($p['includes'] ?? '');
      if (!empty($rawIncludes)) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $rawIncludes))));
        foreach ($lines as $line) {
          $features[] = ltrim($line, '*-• ');
        }
      }

      $mappedPlanes[] = [
        'id'            => $p['id'],
        'number'        => strtoupper($badge),
        'name'          => strtoupper($name),
        'price'         => $p['price'] ?? null,
        'subtitle'      => $subtitle,
        'banner'        => $banner,
        'includes_tag'  => $includesTag,
        'features'      => !empty($features) ? $features : $refSample['features'],
        'footer_phrase' => $refSample['footer_phrase'],
        'accent_color'  => $refSample['accent_color'],
        'bg_gradient'   => $refSample['bg_gradient'],
        'border_color'  => $refSample['border_color']
      ];
    }
  } else {
    $mappedPlanes = $defaultPlanes;
  }
  ?>
  
  <!-- HERO BANNER SERVICIOS CORPORATIVOS -->
  <div style="background: linear-gradient(135deg, #0B0F19 0%, #111827 100%); color: #FFFFFF; padding: 3rem 0; border-bottom: 3px solid var(--color-primary);">
    <div class="container">
      <div style="font-size: 0.85rem; color: #9CA3AF; margin-bottom: 0.5rem;">
        <a href="<?= url('/') ?>" style="color: #9CA3AF; text-decoration: none;">Inicio</a> &rarr;
        <span style="color: var(--color-primary); font-weight: 700;">Planes Corporativos</span>
      </div>
      <h1 style="color: #FFFFFF; margin-bottom: 0.75rem; font-weight: 800; font-size: clamp(1.8rem, 4vw, 2.5rem); text-transform: uppercase; letter-spacing: 0.02em;">
        Planes de Soporte Técnico e Infraestructura PyME
      </h1>
      <p style="color: #D1D5DB; margin-bottom: 0; font-size: 1.05rem; max-width: 800px; line-height: 1.6;">
        Soluciones integrales de mantenimiento informático preventivo y reactivo. Nos ocupamos de tu tecnología para que tu empresa opere con total tranquilidad y continuidad.
      </p>
    </div>
  </div>

  <!-- SECCIÓN DE PLANES -->
  <section style="background-color: #0A0D14; padding: 4rem 0; color: #FFFFFF;">
    <div class="container">
      
      <div style="display: flex; flex-direction: column; gap: 2.5rem;">
        <?php foreach ($mappedPlanes as $p): ?>
          <?php
          $waLink = REDTEC_WHATSAPP_LINK . '?text=' . urlencode("Hola RedTec, quisiera pedir una propuesta personalizada para el " . $p['number'] . ": " . $p['name']);
          $hasPrice = (!empty($p['price']) && (float)$p['price'] > 0);
          ?>
          <div style="background: <?= $p['bg_gradient'] ?>; border: 2px solid <?= $p['border_color'] ?>; border-radius: 16px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5); position: relative; overflow: hidden;">
            
            <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 2rem; align-items: start;" class="grid-plan-row">
              
              <!-- Columna Izquierda: Título y Subtítulo -->
              <div style="display: flex; flex-direction: column; height: 100%;">
                
                <!-- Badge Plan Number -->
                <div style="align-self: flex-start; background: <?= $p['accent_color'] ?>; color: #FFFFFF; font-weight: 900; font-size: 0.85rem; padding: 0.35rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                  <?= htmlspecialchars($p['number']) ?>
                </div>

                <h2 style="font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 900; color: #FFFFFF; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em;">
                  <?= htmlspecialchars($p['name']) ?>
                </h2>

                <p style="color: #D1D5DB; font-size: 1rem; line-height: 1.5; margin-bottom: 1.25rem;">
                  <?= htmlspecialchars($p['subtitle']) ?>
                </p>

                <?php if (!empty($p['banner'])): ?>
                  <div style="background: rgba(234, 179, 8, 0.15); border-left: 4px solid #EAB308; padding: 0.75rem 1rem; border-radius: 4px; font-weight: 800; font-size: 0.82rem; color: #FDE047; text-transform: uppercase; margin-bottom: 1.25rem; line-height: 1.4;">
                    <?= htmlspecialchars($p['banner']) ?>
                  </div>
                <?php endif; ?>

                <div style="margin-top: auto; padding-top: 1rem;">
                  <img src="<?= url('/assets/img/redtecfondonegro.png') ?>" alt="RedTec Integración Tecnológica" style="height: 38px; width: auto; opacity: 0.9;">
                </div>

              </div>

              <!-- Columna Derecha: Incluye & CTA -->
              <div style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column;">
                
                <!-- Encabezado de la lista -->
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; font-weight: 800; font-size: 0.92rem; color: <?= $p['accent_color'] ?>; text-transform: uppercase; letter-spacing: 0.05em;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                  <span><?= htmlspecialchars($p['includes_tag']) ?></span>
                </div>

                <!-- Lista de Ítems -->
                <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem 0; display: flex; flex-direction: column; gap: 0.75rem;">
                  <?php foreach ($p['features'] as $item): ?>
                    <li style="display: flex; align-items: flex-start; gap: 0.65rem; color: #F3F4F6; font-size: 0.92rem; line-height: 1.4;">
                      <span style="color: <?= $p['accent_color'] ?>; flex-shrink: 0; margin-top: 2px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                      </span>
                      <span><?= htmlspecialchars($item) ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <!-- Bloque CTA Consultar o Precio -->
                <div style="border-top: 1px dashed rgba(255, 255, 255, 0.15); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                  <div>
                    <?php if ($hasPrice): ?>
                      <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; color: #FFFFFF; letter-spacing: 0.02em;">
                        $ <?= number_format((float)$p['price'], 2, '.', ',') ?>
                      </div>
                      <div style="font-size: 0.78rem; color: <?= $p['accent_color'] ?>; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        ABONO MENSUAL
                      </div>
                    <?php else: ?>
                      <div style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 900; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.04em;">
                        CONSULTAR
                      </div>
                      <div style="font-size: 0.78rem; color: #9CA3AF; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        PIDE TU PROPUESTA PERSONALIZADA
                      </div>
                    <?php endif; ?>
                  </div>

                  <a href="<?= $waLink ?>" target="_blank" rel="noopener noreferrer" class="btn" style="background-color: var(--color-whatsapp); color: #FFFFFF; border-color: var(--color-whatsapp); font-weight: 800; padding: 0.75rem 1.4rem; border-radius: 8px; text-transform: uppercase; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3); transition: transform 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                    <span><?= $hasPrice ? 'Solicitar Plan' : 'Solicitar Propuesta' ?></span>
                  </a>
                </div>

              </div>

            </div>

            <!-- Footer Badge de cada Plan -->
            <div style="margin-top: 1.5rem; padding-top: 0.85rem; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.82rem; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
              <span style="color: <?= $p['accent_color'] ?>;">&#9733;</span>
              <span><?= htmlspecialchars($p['footer_phrase']) ?></span>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <style>
    @media (max-width: 991px) {
      .grid-plan-row {
        grid-template-columns: 1fr !important;
      }
    }
  </style>

  <?php
};

require REDTEC_SHARED_DIR . '/Layout/layout.php';

