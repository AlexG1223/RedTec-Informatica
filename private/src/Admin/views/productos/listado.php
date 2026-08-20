<?php
/**
 * RedTec Informática - Vista de Listado de Productos (Panel Admin con Stock Inline & Alerta de Stock Bajo)
 * 
 * @var array $productos Lista de productos completos (activos e inactivos)
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$content = function() use ($productos, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
    $lowStockThreshold = LOW_STOCK_THRESHOLD;
?>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h3 style="margin: 0; color: var(--color-dark);">Catálogo de Productos (<?= count($productos) ?>)</h3>
      <p style="margin: 0.25rem 0 0 0; font-size: 0.88rem; color: var(--color-text-secondary);">
        Ajuste rápido de stock en línea, edición completa y exportación de inventario.
      </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
      <a href="<?= url('/admin/productos/exportar') ?>" class="btn btn-outline-dark btn-sm" title="Descargar catálogo completo en formato CSV">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Exportar Catálogo (.CSV)
      </a>
      
      <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-primary">
        + Nuevo Producto
      </a>
    </div>
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
            <th style="padding: 1rem; text-align: center; min-width: 130px;">Stock (Ajuste Rápido)</th>
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
                $pId        = (int)$p['id'];
                $pCode      = htmlspecialchars($p['code']);
                $pName      = htmlspecialchars($p['name']);
                $pCat       = htmlspecialchars($p['category_name'] ?? 'Sin categoría');
                $pPrice     = number_format((float)$p['price'], 2, '.', ',');
                $pStock     = (int)$p['stock'];
                $isActive   = (bool)$p['active'];
                $isLowStock = ($pStock <= $lowStockThreshold);

                $rawImg     = !empty($p['primary_image']) ? $p['primary_image'] : null;
                $pImg       = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : $fallbackImg;
                
                // Fondo resaltado si hay stock bajo
                $rowBg      = !$isActive ? 'background-color: #F9FAFB; opacity: 0.7;' : ($isLowStock ? 'background-color: #FEF3C7;' : '');
              ?>
              <tr id="prod-row-<?= $pId ?>" style="border-bottom: 1px solid var(--color-border-light); <?= $rowBg ?> transition: background-color 0.3s;">
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

                <!-- AJUSTE RÁPIDO DE STOCK INLINE -->
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <div style="display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
                    <input type="number" 
                           value="<?= $pStock ?>" 
                           min="0" 
                           data-id="<?= $pId ?>" 
                           onchange="guardarStockInline(this)" 
                           onfocus="this.dataset.orig = this.value"
                           style="width: 65px; padding: 0.3rem 0.4rem; font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem; text-align: center; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-sm); outline: none;"
                           id="stock-input-<?= $pId ?>">
                    
                    <span id="low-stock-tag-<?= $pId ?>" class="badge-stock" style="font-size: 0.68rem; padding: 0.15rem 0.4rem; background: #D97706; color: #FFF; display: <?= $isLowStock ? 'inline-block' : 'none' ?>;" title="Stock bajo (<= <?= $lowStockThreshold ?> unidades)">
                      Bajo
                    </span>
                  </div>
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

  <script>
  function guardarStockInline(inputEl) {
    const prodId = inputEl.dataset.id;
    const newStock = parseInt(inputEl.value, 10);
    const csrfToken = "<?= htmlspecialchars($csrfToken) ?>";
    const lowStockThreshold = <?= $lowStockThreshold ?>;

    if (isNaN(newStock) || newStock < 0) {
      alert("Ingrese un stock válido (número mayor o igual a 0).");
      inputEl.value = inputEl.dataset.orig || 0;
      return;
    }

    inputEl.style.borderColor = 'var(--color-primary)';

    const formData = new FormData();
    formData.append('stock', newStock);
    formData.append('csrf_token', csrfToken);

    fetch("<?= url('/admin/productos/') ?>" + prodId + "/stock", {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        inputEl.style.borderColor = '#10B981';
        inputEl.dataset.orig = newStock;
        
        const rowEl = document.getElementById('prod-row-' + prodId);
        const tagEl = document.getElementById('low-stock-tag-' + prodId);

        if (data.is_low_stock) {
          if (rowEl && !rowEl.style.opacity) rowEl.style.backgroundColor = '#FEF3C7';
          if (tagEl) tagEl.style.display = 'inline-block';
        } else {
          if (rowEl && !rowEl.style.opacity) rowEl.style.backgroundColor = '';
          if (tagEl) tagEl.style.display = 'none';
        }

        setTimeout(() => { inputEl.style.borderColor = 'var(--color-border-metallic)'; }, 1500);
      } else {
        alert(data.message || "Error al actualizar stock.");
        inputEl.style.borderColor = '#EF4444';
        inputEl.value = inputEl.dataset.orig || 0;
      }
    })
    .catch(err => {
      alert("Error de conexión al servidor.");
      inputEl.style.borderColor = '#EF4444';
      inputEl.value = inputEl.dataset.orig || 0;
    });
  }
  </script>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
