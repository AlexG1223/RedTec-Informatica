<?php
/**
 * RedTec Informática - Vista de Listado de Productos (Panel Admin)
 * 
 * @var array $productos Lista de productos completos (activos e inactivos)
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($productos, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Catálogo de Productos (<?= count($productos) ?>)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Listado general de artículos en inventario, precios, stock y estado de disponibilidad pública.
      </p>
    </div>
    <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-primary">
      + Nuevo Producto
    </a>
  </div>

  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 1rem; width: 60px;">Imagen</th>
            <th style="padding: 1rem;">Código</th>
            <th style="padding: 1rem;">Nombre del Producto</th>
            <th style="padding: 1rem;">Categoría</th>
            <th style="padding: 1rem; text-align: right;">Precio (USD)</th>
            <th style="padding: 1rem; text-align: center;">Stock</th>
            <th style="padding: 1rem; text-align: center;">Estado</th>
            <th style="padding: 1rem; text-align: right;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($productos)): ?>
            <tr>
              <td colspan="8" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
                No hay productos registrados en el sistema. <a href="<?= url('/admin/productos/nuevo') ?>">Crear el primero</a>.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($productos as $p): ?>
              <?php 
                $pId      = (int)$p['id'];
                $pCode    = htmlspecialchars($p['code']);
                $pName    = htmlspecialchars($p['name']);
                $pCat     = htmlspecialchars($p['category_name'] ?? 'Sin categoría');
                $pPrice   = number_format((float)$p['price'], 2, '.', ',');
                $pStock   = (int)$p['stock'];
                $isActive = (bool)$p['active'];

                $rawImg   = !empty($p['primary_image']) ? $p['primary_image'] : null;
                $pImg     = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
              ?>
              <tr style="border-bottom: 1px solid var(--color-border-light); <?= !$isActive ? 'background-color: #F9FAFB; opacity: 0.7;' : '' ?>">
                <td style="padding: 0.75rem 1rem;">
                  <img src="<?= $pImg ?>" alt="" style="width: 42px; height: 42px; object-fit: contain; background: #FFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-sm);" onerror="this.src='<?= $fallbackImg ?>';">
                </td>
                <td style="padding: 0.75rem 1rem; font-family: var(--font-heading); font-weight: 600; color: var(--color-text-muted);">
                  <?= $pCode ?>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--color-dark);">
                  <?= $pName ?>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary);">
                  <?= $pCat ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right; font-family: var(--font-heading); font-weight: 700; color: var(--color-primary);">
                  $<?= $pPrice ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <span style="font-weight: 700; color: <?= $pStock > 0 ? 'var(--color-dark)' : 'var(--color-stock-out)' ?>;">
                    <?= $pStock ?>
                  </span>
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
                    <a href="<?= url('/admin/productos/' . $pId . '/editar') ?>" class="btn btn-outline-dark btn-sm" title="Editar producto e imágenes">
                      Editar
                    </a>

                    <!-- Formulario de Baja / Reactivación -->
                    <form action="<?= url('/admin/productos/' . $pId . '/baja') ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Confirmás <?= $isActive ? 'dar de baja' : 'reactivar' ?> este producto?');">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <?php if ($isActive): ?>
                        <button type="submit" class="btn btn-sm" style="background: #EF4444; color: #FFF; border: none;" title="Dar de baja">
                          Dar de Baja
                        </button>
                      <?php else: ?>
                        <button type="submit" class="btn btn-sm" style="background: #10B981; color: #FFF; border: none;" title="Reactivar">
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

require __DIR__ . '/../../../shared/Layout/admin-layout.php';
