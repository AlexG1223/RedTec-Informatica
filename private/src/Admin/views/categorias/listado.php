<?php
/**
 * RedTec Informática - Vista de Listado de Categorías (Panel Admin)
 * 
 * @var array $categorias Lista de categorías con conteo de productos
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($categorias, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Categorías de Productos (<?= count($categorias) ?>)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Organización y agrupamiento de productos del catálogo público.
      </p>
    </div>
    <a href="<?= url('/admin/categorias/nuevo') ?>" class="btn btn-primary">
      + Nueva Categoría
    </a>
  </div>

  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 1rem; width: 60px;">Imagen</th>
            <th style="padding: 1rem;">Nombre de Categoría</th>
            <th style="padding: 1rem;">Descripción</th>
            <th style="padding: 1rem; text-align: center;">Destacada</th>
            <th style="padding: 1rem; text-align: center;">Productos Asociados</th>
            <th style="padding: 1rem; text-align: right;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($categorias)): ?>
            <tr>
              <td colspan="5" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
                No hay categorías registradas. <a href="<?= url('/admin/categorias/nuevo') ?>">Crear la primera</a>.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($categorias as $c): ?>
              <?php 
                $cId          = (int)$c['id'];
                $cName        = htmlspecialchars($c['name']);
                $cDesc        = htmlspecialchars(substr($c['description'] ?? '', 0, 90)) . (strlen($c['description'] ?? '') > 90 ? '...' : '');
                $productCount = (int)($c['total_products'] ?? 0);

                $rawImg       = !empty($c['image_url']) ? $c['image_url'] : null;
                $webRoot      = REDTEC_PRIVATE_DIR . '/../public';
                if ($rawImg && strpos($rawImg, 'http') !== 0 && !file_exists($webRoot . '/' . ltrim($rawImg, '/'))) {
                    $cImg = $fallbackImg;
                } else {
                    $cImg = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
                }
              ?>
              <tr style="border-bottom: 1px solid var(--color-border-light);">
                <td style="padding: 0.75rem 1rem;">
                  <?php if ($cImg): ?>
                    <img src="<?= $cImg ?>" alt="" style="width: 42px; height: 42px; object-fit: cover; background: #FFF; border: 1px solid var(--color-border-light); border-radius: var(--radius-sm);" onerror="this.src='<?= $fallbackImg ?>';">
                  <?php else: ?>
                    <div style="width: 42px; height: 42px; background: #F1F3F5; color: var(--color-text-muted); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 600;">
                      S/I
                    </div>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 1rem; font-family: var(--font-heading); font-weight: 700; color: var(--color-dark);">
                  <?= $cName ?>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-text-secondary); max-width: 350px;">
                  <?= $cDesc ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <?php if (!empty($c['is_featured'])): ?>
                    <span class="badge-stock" style="background: #FEF3C7; color: #D97706; font-weight: 800; border: 1px solid #F59E0B; font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                      ★ Destacada
                    </span>
                  <?php else: ?>
                    <span style="color: var(--color-text-muted); font-size: 0.8rem;">-</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <span class="badge-stock" style="background: <?= $productCount > 0 ? '#E0F2FE' : '#F3F4F6' ?>; color: <?= $productCount > 0 ? '#0369A1' : '#6B7280' ?>; font-weight: 700;">
                    <?= $productCount ?> producto<?= $productCount !== 1 ? 's' : '' ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <a href="<?= url('/admin/categorias/' . $cId . '/editar') ?>" class="btn btn-outline-dark btn-sm" title="Editar categoría">
                      Editar
                    </a>

                    <!-- Formulario de Eliminación Segura -->
                    <form action="<?= url('/admin/categorias/' . $cId . '/eliminar') ?>" method="POST" style="display: inline;" onsubmit="return confirm('¿Confirmás eliminar la categoría <?= $cName ?>?');">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <button type="submit" 
                              class="btn btn-sm" 
                              style="background: <?= $productCount > 0 ? '#9CA3AF' : '#EF4444' ?>; color: #FFF; border: none;"
                              <?= $productCount > 0 ? 'title="No se puede eliminar porque tiene productos asociados"' : 'title="Eliminar categoría"' ?>>
                        Eliminar
                      </button>
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
