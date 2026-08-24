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



  <!-- TARJETAS DE ACCESOS RÁPIDOS -->
  <div class="admin-card">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.2rem; color: var(--color-dark);">
      Acciones Rápidas
    </h3>

    <div style="display: flex; gap: 1.25rem; flex-wrap: wrap;">
      <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-outline-dark" style="padding: 1.25rem; justify-content: flex-start; text-align: left;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>+ Nuevo Producto</span>
      </a>
    </div>
  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
