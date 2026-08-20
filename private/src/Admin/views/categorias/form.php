<?php
/**
 * RedTec Informática - Formulario de Alta / Edición de Categoría (Panel Admin)
 * 
 * @var array|null $categoria Datos de la categoría si se edita, o null si es nueva.
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$isEdit    = !empty($categoria['id']);
$formTitle = $isEdit ? "Editar Categoría: " . htmlspecialchars($categoria['name']) : "Nueva Categoría";
$actionUrl = $isEdit ? url('/admin/categorias/' . $categoria['id']) : url('/admin/categorias');

$content = function() use ($categoria, $isEdit, $formTitle, $actionUrl, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
    $rawImg      = !empty($categoria['image_url']) ? $categoria['image_url'] : null;
    $cImg        = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : null;
?>
  <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
      <a href="<?= url('/admin/categorias') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Volver al listado</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);"><?= $formTitle ?></h3>
    </div>
  </div>

  <div class="admin-card" style="max-width: 650px;">
    
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Nombre de la Categoría -->
      <div style="margin-bottom: 1.25rem;">
        <label for="name" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Nombre de la Categoría <span style="color: var(--color-primary);">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="<?= htmlspecialchars($categoria['name'] ?? '') ?>" 
               placeholder="Ej: Equipos y Notebooks, Redes y Conectividad" 
               required 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
      </div>

      <!-- Descripción -->
      <div style="margin-bottom: 1.25rem;">
        <label for="description" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Descripción Breve
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="3" 
                  placeholder="Detalle o bajada descriptiva de los productos que agrupa esta categoría..." 
                  style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);"><?= htmlspecialchars($categoria['description'] ?? '') ?></textarea>
      </div>

      <!-- Imagen representativa -->
      <div style="margin-bottom: 1.75rem; background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
        <label style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">
          Imagen Representativa (Opcional)
        </label>

        <?php if ($cImg): ?>
          <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <img src="<?= $cImg ?>" alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border-light);" onerror="this.src='<?= $fallbackImg ?>';">
            <div>
              <span style="font-size: 0.8rem; color: var(--color-text-muted); display: block;">Imagen actual:</span>
              <code style="font-size: 0.75rem; color: var(--color-dark);"><?= htmlspecialchars($categoria['image_url']) ?></code>
            </div>
          </div>
        <?php endif; ?>

        <!-- Subir archivo -->
        <div style="margin-bottom: 0.75rem;">
          <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark); display: block; margin-bottom: 0.25rem;">Opción 1: Subir desde tu equipo</span>
          <input type="file" name="imagen_archivo" accept="image/jpeg,image/png,image/webp,image/gif" style="font-size: 0.85rem; width: 100%;">
        </div>

        <!-- URL manual -->
        <div>
          <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark); display: block; margin-bottom: 0.25rem;">Opción 2: Especificar URL de imagen</span>
          <input type="text" 
                 name="image_url" 
                 value="<?= htmlspecialchars($categoria['image_url'] ?? '') ?>" 
                 placeholder="/assets/img/redtec.jpeg o https://..." 
                 style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block btn-lg">
        <?= $isEdit ? 'Guardar Cambios' : 'Crear Categoría' ?>
      </button>

    </form>

  </div>
<?php
};

require __DIR__ . '/../../../shared/Layout/admin-layout.php';
