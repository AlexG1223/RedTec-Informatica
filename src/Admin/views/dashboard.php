<?php
/**
 * RedTec Informática - Vista de Dashboard del Panel de Administración
 * 
 * @var array $stats Estadísticas con contadores del sistema
 */

$content = function() use ($stats) {
?>
  <!-- TARJETAS RESUMEN DE ESTADÍSTICAS -->
  <div class="grid grid-4" style="gap: 1.5rem; margin-bottom: 2.5rem;">
    
    <!-- Card Productos -->
    <div class="admin-card" style="border-left: 4px solid var(--color-primary);">
      <div style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase;">Productos en Tienda</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--color-dark); margin: 0.35rem 0;">
        <?= $stats['activos_productos'] ?> <span style="font-size: 1rem; font-weight: 500; color: var(--color-text-muted);">/ <?= $stats['total_productos'] ?></span>
      </div>
      <a href="<?= url('/admin/productos') ?>" style="font-size: 0.85rem; color: var(--color-primary); font-weight: 600; text-decoration: none;">
        Gestionar catálogo &rarr;
      </a>
    </div>

    <!-- Card Servicios -->
    <div class="admin-card" style="border-left: 4px solid #10B981;">
      <div style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase;">Servicios Técnicos</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--color-dark); margin: 0.35rem 0;">
        <?= $stats['activos_servicios'] ?> <span style="font-size: 1rem; font-weight: 500; color: var(--color-text-muted);">/ <?= $stats['total_servicios'] ?></span>
      </div>
      <a href="<?= url('/admin/servicios') ?>" style="font-size: 0.85rem; color: #10B981; font-weight: 600; text-decoration: none;">
        Gestionar servicios &rarr;
      </a>
    </div>

    <!-- Card Planes -->
    <div class="admin-card" style="border-left: 4px solid #3B82F6;">
      <div style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase;">Planes Corporativos</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--color-dark); margin: 0.35rem 0;">
        <?= $stats['activos_planes'] ?> <span style="font-size: 1rem; font-weight: 500; color: var(--color-text-muted);">/ <?= $stats['total_planes'] ?></span>
      </div>
      <a href="<?= url('/admin/planes') ?>" style="font-size: 0.85rem; color: #3B82F6; font-weight: 600; text-decoration: none;">
        Gestionar planes &rarr;
      </a>
    </div>

    <!-- Card Categorías -->
    <div class="admin-card" style="border-left: 4px solid #8B5CF6;">
      <div style="font-size: 0.85rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase;">Categorías Activas</div>
      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--color-dark); margin: 0.35rem 0;">
        <?= $stats['total_categorias'] ?>
      </div>
      <span style="font-size: 0.85rem; color: var(--color-text-muted);">Categorías en catálogo</span>
    </div>

  </div>

  <!-- SECCIÓN DE ACCESOS RÁPIDOS Y MÓDULOS -->
  <div class="grid grid-3" style="gap: 2rem;">
    
    <!-- Módulo Productos -->
    <div class="admin-card">
      <div style="width: 44px; height: 44px; background: var(--color-primary-light); color: var(--color-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      </div>
      <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Catálogo de Productos</h3>
      <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
        Creá, editá y gestioná productos del catálogo online, asignación de precios, stock e imágenes de galería.
      </p>
      <div style="display: flex; gap: 0.75rem;">
        <a href="<?= url('/admin/productos') ?>" class="btn btn-outline-dark btn-sm">Ver Productos</a>
        <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-primary btn-sm">+ Nuevo Producto</a>
      </div>
    </div>

    <!-- Módulo Servicios -->
    <div class="admin-card">
      <div style="width: 44px; height: 44px; background: #D1FAE5; color: #10B981; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
      </div>
      <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Servicios Técnicos</h3>
      <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
        Administrá los servicios de infraestructura (CCTV, Servidores, Redes) y su presencia institucional en la web.
      </p>
      <div style="display: flex; gap: 0.75rem;">
        <a href="<?= url('/admin/servicios') ?>" class="btn btn-outline-dark btn-sm">Ver Servicios</a>
        <a href="<?= url('/admin/servicios/nuevo') ?>" class="btn btn-primary btn-sm" style="background-color: #10B981; border-color: #10B981;">+ Nuevo Servicio</a>
      </div>
    </div>

    <!-- Módulo Planes Corporativos -->
    <div class="admin-card">
      <div style="width: 44px; height: 44px; background: #DBEAFE; color: #3B82F6; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
      </div>
      <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Planes Corporativos</h3>
      <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 1.5rem;">
        Gestioná los abonos mensuales de soporte PyME, sus descripciones y precios de cotización.
      </p>
      <div style="display: flex; gap: 0.75rem;">
        <a href="<?= url('/admin/planes') ?>" class="btn btn-outline-dark btn-sm">Ver Planes</a>
        <a href="<?= url('/admin/planes/nuevo') ?>" class="btn btn-primary btn-sm" style="background-color: #3B82F6; border-color: #3B82F6;">+ Nuevo Plan</a>
      </div>
    </div>

  </div>
<?php
};

require __DIR__ . '/../../shared/Layout/admin-layout.php';
