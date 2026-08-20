<?php
/**
 * RedTec Informática - Vista de Listado de Servicios (Panel Admin)
 * 
 * @var array $servicios Lista de servicios técnicos
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($servicios, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Servicios Técnicos e Infraestructura (<?= count($servicios) ?>)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Gestión de servicios ofrecidos en la sección pública del sitio.
      </p>
    </div>
    <a href="<?= url('/admin/servicios/nuevo') ?>" class="btn btn-primary">
      + Nuevo Servicio
    </a>
  </div>

  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 1rem; width: 60px;">Imagen</th>
            <th style="padding: 1rem;">Nombre del Servicio</th>
            <th style="padding: 1rem;">Descripción</th>
            <th style="padding: 1rem; text-align: center;">Estado</th>
            <th style="padding: 1rem; text-align: right;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($servicios)): ?>
            <tr>
              <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
                No hay servicios registrados en el sistema. <a href="<?= url('/admin/servicios/nuevo') ?>">Crear el primero</a>.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($servicios as $s): ?>
              <?php 
                $sId      = (int)$s['id'];
                $sName    = htmlspecialchars($s['name']);
                $sDesc    = htmlspecialchars(substr($s['description'] ?? '', 0, 90)) . (strlen($s['description'] ?? '') > 90 ? '...' : '');
                $isActive = (bool)$s['active'];

                $rawImg   = !empty($s['image_url']) ? $s['image_url'] : null;
                $sImg     = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : null;
              ?>
              <tr style="border-bottom: 1px solid var(--color-border-light); <?= !$isActive ? 'background-color: #F9FAFB; opacity: 0.7;' : '' ?>">
                <td style="padding: 0.75rem 1rem;">
                  <?php if ($sImg): ?>
                    <img src="<?= $sImg ?>" alt="" style="width: 42px; height: 42px; object-fit: cover; background: #FFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-sm);" onerror="this.src='<?= $fallbackImg ?>';">
                  <?php else: ?>
                    <div style="width: 42px; height: 42px; background: #F1F3F5; color: var(--color-text-muted); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 600;">
                      S/I
                    </div>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--color-dark);">
                  <?= $sName ?>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary); max-width: 350px;">
                  <?= $sDesc ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <?php if ($isActive): ?>
                    <span class="badge-stock in-stock" style="font-size: 0.75rem; padding: 0.2rem 0.6rem;">Activo</span>
                  <?php else: ?>
                    <span class="badge-stock out-of-stock" style="font-size: 0.75rem; padding: 0.2rem 0.6rem; background: #6B7280; border-color: #4B5563;">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <a href="<?= url('/admin/servicios/' . $sId . '/editar') ?>" class="btn btn-outline-dark btn-sm" title="Editar servicio">
                      Editar
                    </a>

                    <!-- Formulario de Baja / Reactivación -->
                    <form action="<?= url('/admin/servicios/' . $sId . '/baja') ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Confirmás <?= $isActive ? 'dar de baja' : 'reactivar' ?> este servicio?');">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <?php if ($isActive): ?>
                        <button type="submit" class="btn btn-sm" style="background: #EF4444; color: #FFF; border: none;">
                          Dar de Baja
                        </button>
                      <?php else: ?>
                        <button type="submit" class="btn btn-sm" style="background: #10B981; color: #FFF; border: none;">
                          Reactivar
                        </button>
                      <?php endif; ?>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
