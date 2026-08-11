<?php
/**
 * RedTec Informática - Vista de Servicios Técnicos e Infraestructura
 * 
 * @var array $services Lista de servicios técnicos activos
 */

$content = function() use ($services) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <!-- Banner Superior -->
  <section style="background-color: var(--color-dark); color: #FFFFFF; padding: 3rem 0; border-bottom: 4px solid var(--color-primary);">
    <div class="container">
      <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.08em;">Soluciones Profesionales</span>
      <h1 style="color: #FFFFFF; margin-top: 0.35rem; margin-bottom: 0.5rem; font-size: 2.2rem;">Servicios Técnicos e Infraestructura</h1>
      <p style="color: #B0B0B0; margin-bottom: 0; max-width: 680px;">
        Asistencia técnica especializada en sitio y remota para hogares, PyMEs y comercios en Atlántida y todo Uruguay.
      </p>
    </div>
  </section>

  <div class="container section-padding">

    <!-- INTRODUCCIÓN -->
    <div class="text-center" style="max-width: 750px; margin: 0 auto 3rem auto;">
      <h2 style="font-size: 1.8rem; margin-bottom: 1rem;">Infraestructura, Seguridad y Soporte TI</h2>
      <p style="font-size: 1.05rem; color: var(--color-text-secondary); line-height: 1.6;">
        En <strong>RedTec Informática</strong> contamos con personal técnico calificado para diseñar, instalar y mantener la infraestructura tecnológica de tu hogar o empresa. Desde videovigilancia hasta servidores de misión crítica.
      </p>
    </div>

    <!-- GRILLA DE SERVICIOS TÉCNICOS -->
    <div class="grid grid-2" style="gap: 2.5rem;">
      <?php foreach ($services as $service): ?>
        <?php 
          $sId          = (int)$service['id'];
          $sName        = htmlspecialchars($service['name']);
          $sDescription = !empty($service['description']) ? nl2br(htmlspecialchars($service['description'])) : 'Consulte con nuestros técnicos para conocer los detalles del servicio.';
          
          $rawImg       = !empty($service['image_url']) ? $service['image_url'] : null;
          $sImg         = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : null;

          $waText       = urlencode("Hola RedTec, quiero consultar por el servicio de {$service['name']}");
          $waUrl        = REDTEC_WHATSAPP_LINK . "?text={$waText}";
        ?>

        <div style="background: #FFFFFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; transition: transform var(--transition-normal), box-shadow var(--transition-normal);"
             onmouseenter="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-lg)';"
             onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-sm)';">
          
          <!-- Imagen de servicio o placeholder -->
          <div style="width: 100%; height: 200px; background: #F8F9FA; position: relative; overflow: hidden; border-bottom: 1px solid var(--color-border-light); display: flex; align-items: center; justify-content: center;">
            <?php if ($sImg): ?>
              <img src="<?= $sImg ?>" alt="<?= $sName ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F1F3F5; color: var(--color-text-muted); font-size: 0.85rem; font-weight: 600; flex-direction: column; gap: 0.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                <span>Servicios Técnicos RedTec</span>
              </div>
            <?php else: ?>
              <div style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; background: #F1F3F5; color: var(--color-text-muted); font-size: 0.85rem; font-weight: 600; flex-direction: column; gap: 0.5rem;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                <span>Servicios Técnicos RedTec</span>
              </div>
            <?php endif; ?>
          </div>

          <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
            <h3 style="font-size: 1.25rem; margin-bottom: 0.75rem; color: var(--color-dark);"><?= $sName ?></h3>
            <p style="font-size: 0.95rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 1.5rem; flex-grow: 1;">
              <?= $sDescription ?>
            </p>

            <a href="<?= $waUrl ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn btn-primary"
               style="background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); align-self: start;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662a11.87 11.87 0 005.71 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
              Consultar por WhatsApp
            </a>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- FRANJA DE CROSS-LINK HACIA PLANES CORPORATIVOS -->
    <div style="margin-top: 4rem; background: var(--color-dark); color: #FFFFFF; padding: 3rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem; border-left: 6px solid var(--color-primary);">
      <div style="max-width: 650px;">
        <span style="font-family: var(--font-heading); font-size: 0.8rem; font-weight: 700; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">Para Empresas y Comercio</span>
        <h3 style="color: #FFFFFF; font-size: 1.6rem; margin-top: 0.25rem; margin-bottom: 0.5rem;">¿Buscás soporte técnico mensual garantizado?</h3>
        <p style="color: #B0B0B0; margin-bottom: 0; font-size: 0.95rem;">
          Conoce nuestros abonos corporativos de mantenimiento preventivo, monitoreo de redes y asistencia técnica con respuesta prioritaria.
        </p>
      </div>
      <div>
        <a href="<?= url('/servicios-corporativos') ?>" class="btn btn-primary btn-lg">
          Conocé los Planes Corporativos &rarr;
        </a>
      </div>
    </div>

  </div>
<?php
};

require __DIR__ . '/../../../shared/Layout/layout.php';
