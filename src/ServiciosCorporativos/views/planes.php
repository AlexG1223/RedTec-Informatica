<?php
/**
 * RedTec Informática - Vista de Planes Corporativos de Soporte Mensual
 * 
 * @var array $packages Lista de planes/abonos de soporte técnico
 */

$content = function() use ($packages) {
?>
  <!-- Banner Superior -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Soluciones para Empresas</span>
      <h1 style="color: #FFFFFF; margin-top: 0.35rem; margin-bottom: 0.5rem; font-size: 2.2rem;">Planes de Soporte Técnico Corporativo</h1>
      <p style="color: #B0B0B0; margin-bottom: 0; max-width: 680px;">
        Abonos mensuales de mantenimiento informático, monitoreo preventivo y atención prioritaria para asegurar la continuidad de tu negocio.
      </p>
    </div>
  </section>

  <div class="container section-padding">

    <!-- INTRODUCCIÓN EMPRESARIAL -->
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
      <h2 style="font-size: 1.8rem; margin-bottom: 1rem;">Mantenimiento Preventivo y Asistencia Prioritaria</h2>
      <p style="font-size: 1.05rem; color: var(--color-text-secondary); line-height: 1.6;">
        Diseñamos planes a la medida de cada PyME o institución. Externalizá el soporte técnico de tus equipos, servidores y redes con una cuota mensual fija y tiempos de respuesta garantizados.
      </p>
    </div>

    <!-- CARDS DE PLANES CORPORATIVOS (3 COLUMNAS) -->
    <div class="grid grid-3" style="gap: 2rem; align-items: stretch;">
      <?php foreach ($packages as $idx => $pkg): ?>
        <?php 
          $pName        = htmlspecialchars($pkg['name']);
          $pDescription = !empty($pkg['description']) ? nl2br(htmlspecialchars($pkg['description'])) : 'Consulte las coberturas de este plan con nuestros asesores.';
          $pPrice       = (!empty($pkg['price']) && (float)$pkg['price'] > 0) ? 'USD $' . number_format((float)$pkg['price'], 2, '.', ',') : 'Consultar';
          
          $isFeatured   = ($idx === 1); // Plan del medio destacado
          
          $waText       = urlencode("Hola RedTec, quiero contratar el plan {$pkg['name']} de soporte mensual para mi empresa.");
          $waUrl        = REDTEC_WHATSAPP_LINK . "?text={$waText}";
        ?>

        <div style="background: #FFFFFF; border: <?= $isFeatured ? '2px solid var(--color-primary)' : '1px solid var(--color-border-light)' ?>; border-radius: var(--radius-lg); overflow: hidden; box-shadow: <?= $isFeatured ? 'var(--shadow-lg)' : 'var(--shadow-sm)' ?>; display: flex; flex-direction: column; position: relative; transition: transform var(--transition-normal);"
             onmouseenter="this.style.transform='translateY(-6px)';"
             onmouseleave="this.style.transform='translateY(0)';">
          
          <?php if ($isFeatured): ?>
            <div style="background: var(--color-primary); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; text-align: center; padding: 0.35rem 0; letter-spacing: 0.08em;">
              ★ Más Solicitado PyME
            </div>
          <?php endif; ?>

          <div style="padding: 2rem; display: flex; flex-direction: column; flex-grow: 1;">
            
            <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--color-dark); text-align: center; font-weight: 700;">
              Plan <?= $pName ?>
            </h3>

            <!-- Precio / Estado Cotización -->
            <div style="text-align: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed var(--color-border-light);">
              <span style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: var(--color-primary);">
                <?= $pPrice ?>
              </span>
              <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                Abono mensual a medida
              </div>
            </div>

            <!-- Descripción de Cobertura -->
            <div style="font-size: 0.95rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 2rem; flex-grow: 1;">
              <?= $pDescription ?>
            </div>

            <!-- Botón de Contratación / Consulta -->
            <a href="<?= $waUrl ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn <?= $isFeatured ? 'btn-primary' : 'btn-outline-dark' ?> btn-block btn-lg"
               style="<?= $isFeatured ? 'background-color: var(--color-whatsapp); border-color: var(--color-whatsapp);' : '' ?>">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              Quiero este plan
            </a>

          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- NOTA PIE -->
    <div style="text-align: center; margin-top: 3rem; font-size: 0.9rem; color: var(--color-text-secondary);">
      💡 <em>¿Necesitás una propuesta técnica personalizada para tu infraestructura? <a href="<?= REDTEC_WHATSAPP_LINK ?>?text=Hola%20RedTec,%20quisiera%20solicitar%20un%20presupuesto%20a%20medida%20para%20mi%20empresa" target="_blank" rel="noopener noreferrer" style="color: var(--color-primary); font-weight: 600;">Contactá a nuestros ingenieros técnicos por WhatsApp</a>.</em>
    </div>

  </div>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
