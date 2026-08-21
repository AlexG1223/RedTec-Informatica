<?php
/**
 * RedTec Informática - Vista de Listado de Planes Corporativos (Panel Admin)
 * 
 * @var array $planes Lista de paquetes/planes corporativos
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($planes, $csrfToken) {
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Planes de Soporte Corporativo (<?= count($planes) ?>)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Gestión de abonos mensuales para PyMEs y empresas.
      </p>
    </div>
    <a href="<?= url('/admin/planes/nuevo') ?>" class="btn btn-primary">
      + Nuevo Plan
    </a>
  </div>

  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 1rem;">Nombre del Plan</th>
            <th style="padding: 1rem;">Descripción / Cobertura</th>
            <th style="padding: 1rem; text-align: right;">Precio Mensual</th>
            <th style="padding: 1rem; text-align: center;">Estado</th>
            <th style="padding: 1rem; text-align: right;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($planes)): ?>
            <tr>
              <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
                No hay planes corporativos registrados. <a href="<?= url('/admin/planes/nuevo') ?>">Crear el primero</a>.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($planes as $p): ?>
              <?php 
                $pId      = (int)$p['id'];
                $pName    = htmlspecialchars($p['name']);
                $pDesc    = htmlspecialchars(substr($p['description'] ?? '', 0, 90)) . (strlen($p['description'] ?? '') > 90 ? '...' : '');
                $pPrice   = (!empty($p['price']) && (float)$p['price'] > 0) ? '$ ' . number_format((float)$p['price'], 2, '.', ',') : '<em style="color: var(--color-text-muted);">Consultar</em>';
                $isActive = (bool)$p['active'];
              ?>
              <tr style="border-bottom: 1px solid var(--color-border-light); <?= !$isActive ? 'background-color: #F9FAFB; opacity: 0.7;' : '' ?>">
                <td style="padding: 0.75rem 1rem; font-family: var(--font-heading); font-weight: 700; color: var(--color-dark);">
                  <?= $pName ?>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary); max-width: 380px;">
                  <?= $pDesc ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right; font-family: var(--font-heading); font-weight: 700; color: var(--color-primary);">
                  <?= $pPrice ?>
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
                    <a href="<?= url('/admin/planes/' . $pId . '/editar') ?>" class="btn btn-outline-dark btn-sm" title="Editar plan">
                      Editar
                    </a>

                    <!-- Formulario de Baja / Reactivación -->
                    <form action="<?= url('/admin/planes/' . $pId . '/baja') ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Confirmás <?= $isActive ? 'dar de baja' : 'reactivar' ?> este plan?');">
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
