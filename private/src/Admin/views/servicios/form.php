<?php
/**
 * RedTec Informática - Formulario de Alta / Edición de Servicio Técnico (Panel Admin)
 * 
 * @var array|null $servicio Datos del servicio si se edita, o null si es nuevo.
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$isEdit    = !empty($servicio['id']);
$formTitle = $isEdit ? "Editar Servicio: " . htmlspecialchars($servicio['name']) : "Nuevo Servicio Técnico";
$actionUrl = $isEdit ? url('/admin/servicios/' . $servicio['id']) : url('/admin/servicios');

$content = function() use ($servicio, $isEdit, $formTitle, $actionUrl, $csrfToken) {
    $fallbackImg = url('/assets/img/redtec.jpeg');
    $rawImg      = !empty($servicio['image_url']) ? $servicio['image_url'] : null;
    $sImg        = $rawImg ? (strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg)) : null;
?>
  <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
      <a href="<?= url('/admin/servicios') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Volver al listado</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);"><?= $formTitle ?></h3>
    </div>
  </div>

  <div class="admin-card" style="max-width: 650px;">
    
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Nombre del Servicio -->
      <div style="margin-bottom: 1.25rem;">
        <label for="name" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Nombre del Servicio <span style="color: var(--color-primary);">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="<?= htmlspecialchars($servicio['name'] ?? '') ?>" 
               placeholder="Ej: Instalación de Cámaras de Seguridad (CCTV)" 
               required 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
      </div>

      <!-- Descripción del Servicio -->
      <div style="margin-bottom: 1.25rem;">
        <label for="description" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Descripción del Servicio
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="5" 
                  placeholder="Alcance del servicio técnico, equipamiento cubierto, atención presencial o remota..." 
                  style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);"><?= htmlspecialchars($servicio['description'] ?? '') ?></textarea>
      </div>

      <!-- Imagen del Servicio -->
      <div style="margin-bottom: 1.75rem; background: var(--color-bg); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--color-border-light);">
        <label style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">
          Imagen del Servicio (Opcional)
        </label>

        <?php if ($sImg): ?>
          <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <img src="<?= $sImg ?>" alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border-light);" onerror="this.src='<?= $fallbackImg ?>';">
            <div>
              <span style="font-size: 0.8rem; color: var(--color-text-muted); display: block;">Imagen actual:</span>
              <code style="font-size: 0.75rem; color: var(--color-dark);"><?= htmlspecialchars($servicio['image_url']) ?></code>
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
                 value="<?= htmlspecialchars($servicio['image_url'] ?? '') ?>" 
                 placeholder="/assets/img/redtec.jpeg o https://..." 
                 style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
        </div>
        <small style="display: block; color: var(--color-text-muted); font-size: 0.75rem; margin-top: 0.5rem;">
          Si no subís ni especificás ninguna imagen, se utilizará un marcador genérico de servicio técnico.
        </small>
      </div>

      <button type="submit" class="btn btn-primary btn-block btn-lg">
        <?= $isEdit ? 'Guardar Cambios' : 'Crear Servicio' ?>
      </button>

    </form>

  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
