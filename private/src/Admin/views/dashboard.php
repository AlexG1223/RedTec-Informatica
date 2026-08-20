<?php
/**
 * RedTec Informática - Vista Principal del Dashboard (Panel Admin)
 * 
 * @var int $totalProductos Cantidad de productos activos
 * @var int $totalCategorias Cantidad de categorías
 * @var int $totalServicios Cantidad de servicios técnicos
 * @var int $totalPlanes Cantidad de planes corporativos
 */

$content = function() use ($totalProductos, $totalCategorias, $totalServicios, $totalPlanes) {
?>
  <div style="margin-bottom: 2rem;">
    <h2 style="margin-bottom: 0.25rem; color: var(--color-dark); font-weight: 800;">Bienvenido al Panel de Control</h2>
    <p style="color: var(--color-text-secondary); margin-bottom: 0;">
      Resumen general del catálogo y accesos rápidos a las herramientas de gestión.
    </p>
  </div>

  <!-- TARJETAS DE ESTADÍSTICAS -->
  <div class="grid grid-4" style="gap: 1.5rem; margin-bottom: 2.5rem;">
    
    <div class="admin-card" style="border-left: 4px solid var(--color-primary);">
      <div style="font-size: 0.8rem; font-weight: 800; color: var(--color-primary); text-transform: uppercase; letter-spacing: 0.05em;">Productos Activos</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-dark); margin-top: 0.25rem;">
        <?= (int)$totalProductos ?>
      </div>
      <a href="<?= url('/admin/productos') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; display: inline-block; margin-top: 0.5rem;">Gestionar productos &rarr;</a>
    </div>

    <div class="admin-card" style="border-left: 4px solid #0D9488;">
      <div style="font-size: 0.8rem; font-weight: 800; color: #0D9488; text-transform: uppercase; letter-spacing: 0.05em;">Categorías</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-dark); margin-top: 0.25rem;">
        <?= (int)$totalCategorias ?>
      </div>
      <a href="<?= url('/admin/categorias') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; display: inline-block; margin-top: 0.5rem;">Gestionar categorías &rarr;</a>
    </div>

    <div class="admin-card" style="border-left: 4px solid #3B82F6;">
      <div style="font-size: 0.8rem; font-weight: 800; color: #3B82F6; text-transform: uppercase; letter-spacing: 0.05em;">Servicios Técnicos</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-dark); margin-top: 0.25rem;">
        <?= (int)$totalServicios ?>
      </div>
      <a href="<?= url('/admin/servicios') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; display: inline-block; margin-top: 0.5rem;">Gestionar servicios &rarr;</a>
    </div>

    <div class="admin-card" style="border-left: 4px solid #8B5CF6;">
      <div style="font-size: 0.8rem; font-weight: 800; color: #8B5CF6; text-transform: uppercase; letter-spacing: 0.05em;">Planes PyME</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--color-dark); margin-top: 0.25rem;">
        <?= (int)$totalPlanes ?>
      </div>
      <a href="<?= url('/admin/planes') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; display: inline-block; margin-top: 0.5rem;">Gestionar planes &rarr;</a>
    </div>

  </div>

  <!-- TARJETAS DE ACCESOS RÁPIDOS -->
  <div class="admin-card">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.2rem; color: var(--color-dark);">
      Acciones Rápidas
    </h3>

    <div class="grid grid-3" style="gap: 1.25rem;">
      <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-outline-dark" style="padding: 1.25rem; justify-content: flex-start; text-align: left;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>+ Nuevo Producto</span>
      </a>

      <a href="<?= url('/admin/importar') ?>" class="btn btn-outline-dark" style="padding: 1.25rem; justify-content: flex-start; text-align: left;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span>Importación Masiva (CSV)</span>
      </a>

      <a href="<?= url('/admin/productos/exportar') ?>" class="btn btn-outline-dark" style="padding: 1.25rem; justify-content: flex-start; text-align: left;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <span>Exportar Catálogo CSV</span>
      </a>
    </div>
  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
