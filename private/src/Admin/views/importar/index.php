<?php
/**
 * RedTec Informática - Vista de Carga de Archivo de Importación (Panel Admin)
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($csrfToken) {
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Importación Masiva de Productos (CSV)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Subí tu archivo de catálogo para crear productos nuevos o actualizar precios y stock de existentes.
      </p>
    </div>
    <a href="<?= url('/admin/importar/historial') ?>" class="btn btn-outline-dark btn-sm">
      Ver Historial de Importaciones &rarr;
    </a>
  </div>

  <div class="grid grid-2" style="gap: 2rem; align-items: start;">
    
    <!-- CARD SUBIDA DE ARCHIVO -->
    <div class="admin-card">
      <h4 style="margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.5rem;">
        1. Seleccionar Archivo CSV
      </h4>

      <form action="<?= url('/admin/importar/previsualizar') ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div style="margin-bottom: 1.5rem; background: var(--color-bg); padding: 1.75rem; border-radius: var(--radius-md); border: 2px dashed var(--color-border-metallic); text-align: center;">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-primary); margin-bottom: 0.75rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          
          <label for="csv_file" style="display: block; font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.5rem;">
            Elegir archivo CSV de tu equipo
          </label>
          
          <input type="file" 
                 id="csv_file" 
                 name="csv_file" 
                 accept=".csv,text/csv,application/vnd.ms-excel" 
                 required 
                 style="font-size: 0.85rem; max-width: 300px; margin: 0 auto 0.75rem auto; display: block;">

          <small style="display: block; color: var(--color-text-muted); font-size: 0.78rem;">
            Codificación compatible: UTF-8 o ISO-8859-1. Separador por comas (,) o punto y coma (;).
          </small>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
          Previsualizar Cambios &rarr;
        </button>

      </form>
    </div>

    <!-- INSTRUCCIONES Y PLANTILLA -->
    <div class="admin-card" style="background: #F9FAFB;">
      <h4 style="margin-top: 0; margin-bottom: 1rem; color: var(--color-dark);">
        💡 Instrucciones del Archivo CSV
      </h4>

      <p style="font-size: 0.9rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 1.25rem;">
        Para asegurar una carga correcta, tu archivo CSV debe incluir las siguientes columnas en la primera fila (cabecera):
      </p>

      <ul style="font-size: 0.88rem; color: var(--color-text-main); padding-left: 1.25rem; line-height: 1.8; margin-bottom: 1.5rem;">
        <li><code>code</code> <strong>(Obligatorio)</strong>: Código de barra o SKU único del producto. Si ya existe en el sistema, se actualizará su precio y stock.</li>
        <li><code>name</code> <strong>(Obligatorio)</strong>: Nombre o título del producto.</li>
        <li><code>description</code>: Descripción detallada o especificaciones.</li>
        <li><code>category</code>: Nombre de la categoría. Si no existe, se creará automáticamente.</li>
        <li><code>price</code>: Precio en USD (ej: <code>49.90</code>).</li>
        <li><code>stock</code>: Cantidad disponible (ej: <code>15</code>).</li>
      </ul>

      <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
        <strong style="display: block; font-size: 0.85rem; color: var(--color-dark); margin-bottom: 0.5rem;">¿No tenés una plantilla?</strong>
        <a href="<?= url('/admin/importar/plantilla') ?>" class="btn btn-outline-dark btn-sm" style="width: 100%; text-align: center;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Descargar Plantilla de Ejemplo (.CSV)
        </a>
      </div>
    </div>

  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
