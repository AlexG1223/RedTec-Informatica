<?php
/**
 * RedTec Informática - Vista de Listado de Productos (Panel Admin con Selección Múltiple y Acciones Masivas)
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
        Ajuste rápido de stock en línea, selección múltiple y gestión de inventario en lote.
      </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
      <a href="<?= url('/admin/productos/nuevo') ?>" class="btn btn-primary">
        + Nuevo Producto
      </a>
    </div>
  </div>

  <!-- BARRA FLOTANTE DE ACCIONES MASIVAS EN LOTE -->
  <form id="bulkForm" action="<?= url('/admin/productos/accion-masiva') ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="hidden" name="action" id="bulkActionInput" value="">

    <div id="bulkActionBar" style="display: none; background: var(--color-dark); color: #FFFFFF; padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: var(--shadow-md); border-left: 4px solid var(--color-primary);">
      <div style="font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        <span id="selectedCount">0</span> producto(s) seleccionado(s)
      </div>
      
      <div style="display: flex; gap: 0.65rem; flex-wrap: wrap;">
        <button type="button" class="btn btn-sm" onclick="ejecutarAccionMasiva('baja')" style="background: #F59E0B; color: #FFFFFF; border: none; font-weight: 700;">
          Dar de Baja Seleccionados
        </button>
        <button type="button" class="btn btn-sm" onclick="ejecutarAccionMasiva('reactivar')" style="background: #10B981; color: #FFFFFF; border: none; font-weight: 700;">
          Reactivar Seleccionados
        </button>
        <button type="button" class="btn btn-sm" onclick="ejecutarAccionMasiva('eliminar')" style="background: #EF4444; color: #FFFFFF; border: none; font-weight: 700;">
          Eliminar Seleccionados
        </button>
      </div>
    </div>

    <div class="admin-card" style="padding: 0; overflow: hidden;">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
          <thead>
            <tr style="background-color: var(--color-dark); color: #FFFFFF; font-family: var(--font-heading); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em;">
              <th style="padding: 1rem; width: 40px; text-align: center;">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" style="width: 17px; height: 17px; cursor: pointer;" title="Seleccionar / Deseleccionar todos">
              </th>
              <th style="padding: 1rem; width: 60px;">Imagen</th>
              <th style="padding: 1rem;">Código</th>
              <th style="padding: 1rem;">Nombre del Producto</th>
              <th style="padding: 1rem;">Categoría</th>
              <th style="padding: 1rem; text-align: right;">Precio ($)</th>
              <th style="padding: 1rem; text-align: center; min-width: 130px;">Stock (Ajuste Rápido)</th>
              <th style="padding: 1rem; text-align: center;">Estado</th>
              <th style="padding: 1rem; text-align: right;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($productos)): ?>
              <tr>
                <td colspan="9" style="padding: 2.5rem; text-align: center; color: var(--color-text-muted);">
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
                  <td style="padding: 0.75rem 1rem; text-align: center;">
                    <input type="checkbox" name="product_ids[]" value="<?= $pId ?>" class="product-checkbox" onchange="actualizarSeleccion()" style="width: 17px; height: 17px; cursor: pointer;">
                  </td>
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
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </form>

  <script>
  function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    actualizarSeleccion();
  }

  function actualizarSeleccion() {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const bar = document.getElementById('bulkActionBar');
    const countSpan = document.getElementById('selectedCount');
    const master = document.getElementById('selectAll');
    const total = document.querySelectorAll('.product-checkbox');

    if (countSpan) countSpan.textContent = checked.length;
    if (bar) bar.style.display = checked.length > 0 ? 'flex' : 'none';
    if (master) master.checked = (checked.length === total.length && total.length > 0);
  }

  function ejecutarAccionMasiva(accion) {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    if (checked.length === 0) {
      alert('Por favor selecciona al menos un producto de la lista.');
      return;
    }

    let msg = '';
    if (accion === 'baja') {
      msg = `¿Confirmás dar de baja los ${checked.length} producto(s) seleccionado(s)?`;
    } else if (accion === 'reactivar') {
      msg = `¿Confirmás reactivar los ${checked.length} producto(s) seleccionado(s)?`;
    } else if (accion === 'eliminar') {
      msg = `¡ATENCIÓN! ¿Confirmás ELIMINAR PERMANENTEMENTE los ${checked.length} producto(s) seleccionado(s)? Esta acción borrará sus imágenes y datos y no se puede deshacer.`;
    }

    if (confirm(msg)) {
      document.getElementById('bulkActionInput').value = accion;
      document.getElementById('bulkForm').submit();
    }
  }

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
