<?php
/**
 * RedTec Informática - Formulario de Alta / Edición de Producto (Panel Admin)
 * 
 * @var array|null $producto Datos del producto si se edita, o null si es nuevo.
 * @var array $categorias Lista de categorías activas.
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$isEdit    = !empty($producto['id']);
$formTitle = $isEdit ? "Editar Producto: " . htmlspecialchars($producto['name']) : "Nuevo Producto";
$actionUrl = $isEdit ? url('/admin/productos/' . $producto['id']) : url('/admin/productos');

$content = function() use ($producto, $categorias, $isEdit, $formTitle, $actionUrl, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
?>
  <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
      <a href="<?= url('/admin/productos') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Volver al listado</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);"><?= $formTitle ?></h3>
    </div>
  </div>

  <div class="grid grid-2" style="gap: 2rem; align-items: start;">
    
    <!-- DATOS DEL PRODUCTO -->
    <div class="admin-card">
      <h4 style="margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.5rem;">
        1. Información Principal
      </h4>

      <form action="<?= $actionUrl ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <!-- Código del Producto -->
          <div>
            <label for="code" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Código <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="text" 
                   id="code" 
                   name="code" 
                   value="<?= htmlspecialchars($producto['code'] ?? '') ?>" 
                   placeholder="Ej: SW-TP-G108" 
                   required 
                   style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
          </div>

          <!-- Categoría -->
          <div>
            <label for="category_id" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Categoría <span style="color: var(--color-primary);">*</span>
            </label>
            <select id="category_id" 
                    name="category_id" 
                    required 
                    style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); background: #FFF;">
              <option value="">-- Seleccionar Categoría --</option>
              <?php foreach ($categorias as $cat): ?>
                <?php $selected = (!empty($producto['category_id']) && (int)$producto['category_id'] === (int)$cat['id']) ? 'selected' : ''; ?>
                <option value="<?= $cat['id'] ?>" <?= $selected ?>>
                  <?= htmlspecialchars($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Nombre del Producto -->
        <div style="margin-bottom: 1rem;">
          <label for="name" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
            Nombre del Producto <span style="color: var(--color-primary);">*</span>
          </label>
          <input type="text" 
                 id="name" 
                 name="name" 
                 value="<?= htmlspecialchars($producto['name'] ?? '') ?>" 
                 placeholder="Ej: Switch TP-Link 8 Puertos Gigabit" 
                 required 
                 style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <!-- Precio $ -->
          <div>
            <label for="price" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Precio ($) <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="number" 
                   step="0.01" 
                   min="0" 
                   id="price" 
                   name="price" 
                   value="<?= htmlspecialchars($producto['price'] ?? '0.00') ?>" 
                   required 
                   style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
          </div>

          <!-- Stock -->
          <div>
            <label for="stock" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Stock Disponible <span style="color: var(--color-primary);">*</span>
            </label>
            <input type="number" 
                   min="0" 
                   id="stock" 
                   name="stock" 
                   value="<?= htmlspecialchars($producto['stock'] ?? '0') ?>" 
                   required 
                   style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
          </div>
        </div>

        <!-- Descripción -->
        <div style="margin-bottom: 1.5rem;">
          <label for="description" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
            Descripción del Producto
          </label>
          <textarea id="description" 
                    name="description" 
                    rows="4" 
                    placeholder="Especificaciones técnicas, puertos, características..." 
                    style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);"><?= htmlspecialchars($producto['description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          <?= $isEdit ? 'Guardar Cambios' : 'Crear Producto' ?>
        </button>

      </form>
    </div>

    <!-- GALERÍA DE IMÁGENES (Solo activa en Edición) -->
    <div>
      <?php if ($isEdit): ?>
        <div class="admin-card">
          <h4 style="margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-bg); padding-bottom: 0.5rem;">
            2. Galería de Imágenes
          </h4>

          <!-- Formulario de Subida -->
          <form action="<?= url('/admin/productos/' . $producto['id'] . '/imagenes/subir') ?>" method="POST" enctype="multipart/form-data" style="margin-bottom: 1.5rem; background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            
            <label for="imagen" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
              Subir Nueva Imagen
            </label>
            <input type="file" 
                   id="imagen" 
                   name="imagen" 
                   accept="image/jpeg,image/png,image/webp,image/gif" 
                   required 
                   style="margin-bottom: 0.85rem; font-size: 0.85rem; width: 100%;">
            
            <button type="submit" class="btn btn-outline-dark btn-sm" style="width: 100%;">
              Subir Imagen a Galería
            </button>
            <small style="display: block; color: var(--color-text-muted); font-size: 0.75rem; margin-top: 0.5rem;">
              Archivos permitidos: JPG, PNG, WEBP, GIF (Máx 5MB).
            </small>
          </form>

          <!-- Grilla de Imágenes Subidas -->
          <h5 style="margin-bottom: 0.75rem; font-size: 0.9rem; color: var(--color-dark);">Imágenes en Galería:</h5>
          <?php 
            $images = $producto['images'] ?? [];
          ?>
          <?php if (empty($images)): ?>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); text-align: center; padding: 1rem; border: 1px dashed var(--color-border-metallic); border-radius: var(--radius-md);">
              Este producto aún no tiene imágenes asociadas.
            </p>
          <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem;">
              <?php foreach ($images as $img): ?>
                <?php 
                  $imgId     = (int)$img['id'];
                  $isPrimary = !empty($img['is_primary']);
                  $rawPath   = $img['image_url'];
                  $imgSrc    = strpos($rawPath, 'http') === 0 ? htmlspecialchars($rawPath) : url($rawPath);
                ?>
                <div style="background: #FFF; border: 2px solid <?= $isPrimary ? 'var(--color-primary)' : 'var(--color-border-light)' ?>; border-radius: var(--radius-sm); overflow: hidden; position: relative; display: flex; flex-direction: column;">
                  
                  <?php if ($isPrimary): ?>
                    <span style="position: absolute; top: 4px; left: 4px; background: var(--color-primary); color: #FFF; font-size: 0.65rem; font-weight: 800; padding: 2px 6px; border-radius: var(--radius-sm); z-index: 2; box-shadow: var(--shadow-sm);">
                      ★ Principal
                    </span>
                  <?php endif; ?>

                  <img src="<?= $imgSrc ?>" alt="" style="width: 100%; height: 95px; object-fit: contain; padding: 4px; background: #FFF;" onerror="this.src='<?= $fallbackImg ?>';">
                  
                  <div style="margin-top: auto; display: flex; flex-direction: column;">
                    <?php if (!$isPrimary): ?>
                      <form action="<?= url('/admin/productos/' . $producto['id'] . '/imagenes/' . $imgId . '/principal') ?>" method="POST" style="margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <button type="submit" style="width: 100%; background: var(--color-dark); color: #FFF; border: none; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 0.35rem 0.25rem; font-size: 0.72rem; font-weight: 700; cursor: pointer;" title="Establecer como imagen principal del producto">
                          ★ Marcar Principal
                        </button>
                      </form>
                    <?php endif; ?>

                    <form action="<?= url('/admin/productos/' . $producto['id'] . '/imagenes/' . $imgId . '/eliminar') ?>" method="POST" onsubmit="return confirm('¿Eliminar esta imagen de la galería?');" style="margin: 0;">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                      <button type="submit" style="width: 100%; background: #EF4444; color: #FFF; border: none; padding: 0.35rem 0.25rem; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                        Eliminar
                      </button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </div>
      <?php else: ?>
        <div class="admin-card" style="background: #F9FAFB; text-align: center; color: var(--color-text-muted); padding: 2rem;">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.5rem;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <h4 style="margin-bottom: 0.5rem;">Galería de Imágenes</h4>
          <p style="font-size: 0.85rem; margin-bottom: 0;">
            Primero guardá la información principal del producto para habilitar la sección de subida de imágenes.
          </p>
        </div>
      <?php endif; ?>
    </div>

  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
