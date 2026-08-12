<?php
/**
 * RedTec Informática - Vista de Previsualización de Importación CSV (Panel Admin)
 * 
 * @var array $pageTitle Título de la página
 * @var array $_SESSION['import_preview'] Datos procesados del CSV
 */

use RedTec\Admin\AdminGuard;
$csrfToken   = AdminGuard::csrfToken();
$previewData = $_SESSION['import_preview'] ?? [];
$summary     = $previewData['summary'] ?? ['total' => 0, 'to_create' => 0, 'to_update' => 0, 'errors' => 0];
$rows        = $previewData['rows'] ?? [];
$fileName    = htmlspecialchars($previewData['filename'] ?? 'archivo.csv');

$content = function() use ($summary, $rows, $fileName, $csrfToken) {
?>
  <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
      <a href="<?= url('/admin/importar') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Cancelar y volver</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);">Previsualización: <?= $fileName ?></h3>
    </div>

    <?php if ($summary['to_create'] > 0 || $summary['to_update'] > 0): ?>
      <form action="<?= url('/admin/importar/confirmar') ?>" method="POST" onsubmit="return confirm('¿Confirmás aplicar los cambios procesados en la base de datos?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="btn btn-primary btn-lg">
          Confirmar e Importar (<?= $summary['to_create'] + $summary['to_update'] ?> productos) &rarr;
        </button>
      </form>
    <?php endif; ?>
  </div>

  <!-- TARJETAS DE RESUMEN DE CAMBIOS -->
  <div class="grid grid-4" style="gap: 1.25rem; margin-bottom: 2rem;">
    <div class="admin-card" style="padding: 1.25rem;">
      <div style="font-size: 0.8rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase;">Filas Procesadas</div>
      <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: var(--color-dark); margin-top: 0.25rem;">
        <?= $summary['total'] ?>
      </div>
    </div>

    <div class="admin-card" style="padding: 1.25rem; border-left: 4px solid #10B981;">
      <div style="font-size: 0.8rem; color: #10B981; font-weight: 600; text-transform: uppercase;">Productos A Crear</div>
      <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: #10B981; margin-top: 0.25rem;">
        +<?= $summary['to_create'] ?>
      </div>
    </div>

    <div class="admin-card" style="padding: 1.25rem; border-left: 4px solid #3B82F6;">
      <div style="font-size: 0.8rem; color: #3B82F6; font-weight: 600; text-transform: uppercase;">Productos A Actualizar</div>
      <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: #3B82F6; margin-top: 0.25rem;">
        ↻ <?= $summary['to_update'] ?>
      </div>
    </div>

    <div class="admin-card" style="padding: 1.25rem; border-left: 4px solid #EF4444;">
      <div style="font-size: 0.8rem; color: #EF4444; font-weight: 600; text-transform: uppercase;">Filas Con Error</div>
      <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: #EF4444; margin-top: 0.25rem;">
        ✕ <?= $summary['errors'] ?>
      </div>
    </div>
  </div>

  <!-- TABLA DE DETALLE POR FILA -->
  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 0.85rem 1rem; width: 60px; text-align: center;">Fila</th>
            <th style="padding: 0.85rem 1rem;">Acción Prevista</th>
            <th style="padding: 0.85rem 1rem;">Código</th>
            <th style="padding: 0.85rem 1rem;">Nombre del Producto</th>
            <th style="padding: 0.85rem 1rem;">Categoría</th>
            <th style="padding: 0.85rem 1rem; text-align: right;">Precio (USD)</th>
            <th style="padding: 0.85rem 1rem; text-align: center;">Stock</th>
            <th style="padding: 0.85rem 1rem;">Observaciones / Errores</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <?php 
              $action = $r['action'];
              $bg = '#FFFFFF';
              if ($action === 'create') $bg = '#F0FDF4';
              elseif ($action === 'update') $bg = '#EFF6FF';
              elseif ($action === 'error') $bg = '#FEF2F2';
            ?>
            <tr style="border-bottom: 1px solid var(--color-border-light); background-color: <?= $bg ?>;">
              <td style="padding: 0.75rem 1rem; text-align: center; color: var(--color-text-muted); font-weight: 600;">
                #<?= $r['row_num'] ?>
              </td>

              <td style="padding: 0.75rem 1rem;">
                <?php if ($action === 'create'): ?>
                  <span class="badge-stock" style="background: #10B981; color: #FFF;">+ Crear Nuevo</span>
                <?php elseif ($action === 'update'): ?>
                  <span class="badge-stock" style="background: #3B82F6; color: #FFF;">↻ Actualizar (ID #<?= $r['existing_id'] ?>)</span>
                <?php else: ?>
                  <span class="badge-stock" style="background: #EF4444; color: #FFF;">✕ Omitir Error</span>
                <?php endif; ?>
              </td>

              <td style="padding: 0.75rem 1rem; font-family: var(--font-heading); font-weight: 600;">
                <?= htmlspecialchars($r['code']) ?>
              </td>

              <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--color-dark);">
                <?= htmlspecialchars($r['name']) ?>
              </td>

              <td style="padding: 0.75rem 1rem;">
                <?= htmlspecialchars($r['category_name']) ?>
                <?php if ($r['cat_status'] === 'se_creara'): ?>
                  <small style="display: block; color: #D97706; font-weight: 600;">(Se creará nueva)</small>
                <?php elseif ($r['cat_status'] === 'sin_categoria'): ?>
                  <small style="display: block; color: var(--color-text-muted);">(Sin categoría)</small>
                <?php endif; ?>
              </td>

              <td style="padding: 0.75rem 1rem; text-align: right; font-family: var(--font-heading); font-weight: 700; color: var(--color-primary);">
                $<?= number_format((float)$r['price'], 2, '.', ',') ?>
              </td>

              <td style="padding: 0.75rem 1rem; text-align: center; font-weight: 700;">
                <?= (int)$r['stock'] ?>
              </td>

              <td style="padding: 0.75rem 1rem; font-size: 0.8rem;">
                <?php if (!empty($r['errors'])): ?>
                  <span style="color: #DC2626; font-weight: 600;">
                    <?= implode(', ', array_map('htmlspecialchars', $r['errors'])) ?>
                  </span>
                <?php else: ?>
                  <span style="color: #059669;">✓ Válido</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php
};

require __DIR__ . '/../../../shared/Layout/admin-layout.php';
