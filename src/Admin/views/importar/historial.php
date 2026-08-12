<?php
/**
 * RedTec Informática - Vista de Historial de Importaciones (Panel Admin)
 * 
 * @var array $logs Historial de registros de import_logs
 */

$content = function() use ($logs) {
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Historial de Importaciones Masivas</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Registro de auditoría de cargas de catálogo ejecutadas desde el panel de administración.
      </p>
    </div>
    <a href="<?= url('/admin/importar') ?>" class="btn btn-primary">
      + Nueva Importación
    </a>
  </div>

  <div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
        <thead>
          <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
            <th style="padding: 1rem; width: 60px;">ID</th>
            <th style="padding: 1rem;">Fecha y Hora</th>
            <th style="padding: 1rem;">Archivo Subido</th>
            <th style="padding: 1rem;">Administrador</th>
            <th style="padding: 1rem; text-align: center;">Procesados</th>
            <th style="padding: 1rem; text-align: center;">Creados</th>
            <th style="padding: 1rem; text-align: center;">Actualizados</th>
            <th style="padding: 1rem; text-align: center;">Fallidos</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($logs)): ?>
            <tr>
              <td colspan="8" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
                Aún no se han ejecutado importaciones en el sistema.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($logs as $l): ?>
              <?php 
                $dateStr = date('d/m/Y H:i', strtotime($l['imported_at']));
                $admin   = htmlspecialchars($l['admin_name'] ?? 'Admin');
              ?>
              <tr style="border-bottom: 1px solid var(--color-border-light);">
                <td style="padding: 0.75rem 1rem; font-family: var(--font-heading); font-weight: 700; color: var(--color-text-muted);">
                  #<?= (int)$l['id'] ?>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 500;">
                  <?= $dateStr ?>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--color-dark);">
                  📄 <?= htmlspecialchars($l['filename']) ?>
                </td>
                <td style="padding: 0.75rem 1rem;">
                  <?= $admin ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center; font-weight: 700;">
                  <?= (int)$l['total_processed'] ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center; color: #10B981; font-weight: 700;">
                  +<?= (int)$l['total_created'] ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center; color: #3B82F6; font-weight: 700;">
                  ↻ <?= (int)$l['total_updated'] ?>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center; color: #EF4444; font-weight: 700;">
                  <?= (int)$l['total_failed'] ?>
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
