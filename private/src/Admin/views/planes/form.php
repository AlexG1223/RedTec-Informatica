<?php
/**
 * RedTec Informática - Formulario de Alta / Edición de Plan Corporativo (Panel Admin)
 * 
 * @var array|null $plan Datos del plan si se edita, o null si es nuevo.
 */

use RedTec\Admin\AdminGuard;
$csrfToken = AdminGuard::csrfToken();

$isEdit    = !empty($plan['id']);
$formTitle = $isEdit ? "Editar Plan: " . htmlspecialchars($plan['name']) : "Nuevo Plan Corporativo";
$actionUrl = $isEdit ? url('/admin/planes/' . $plan['id']) : url('/admin/planes');

$content = function() use ($plan, $isEdit, $formTitle, $actionUrl, $csrfToken) {
?>
  <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
      <a href="<?= url('/admin/planes') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Volver al listado</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);"><?= $formTitle ?></h3>
    </div>
  </div>

  <div class="admin-card" style="max-width: 650px;">
    
    <form action="<?= $actionUrl ?>" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Nombre del Plan -->
      <div style="margin-bottom: 1.25rem;">
        <label for="name" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Nombre del Plan Corporativo <span style="color: var(--color-primary);">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="<?= htmlspecialchars($plan['name'] ?? '') ?>" 
               placeholder="Ej: Empresarial, Pyme Pro, Premium" 
               required 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
      </div>

      <!-- Precio Mensual USD -->
      <div style="margin-bottom: 1.25rem;">
        <label for="price" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Precio Estimado en USD ($)
        </label>
        <input type="number" 
               step="0.01" 
               min="0" 
               id="price" 
               name="price" 
               value="<?= (!empty($plan['price']) && (float)$plan['price'] > 0) ? htmlspecialchars($plan['price']) : '' ?>" 
               placeholder="Ej: 150.00" 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);">
        <small style="display: block; color: var(--color-primary); font-size: 0.8rem; margin-top: 0.35rem; font-weight: 500;">
          💡 <strong>Aclaración:</strong> Dejar vacío para mostrar <em>"Consultar"</em> en el sitio público.
        </small>
      </div>

      <!-- Descripción y Coberturas -->
      <div style="margin-bottom: 1.75rem;">
        <label for="description" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">
          Descripción y Cobertura del Plan
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="5" 
                  placeholder="Detalle de servicios incluidos: número de equipos, horas in-situ, tiempo de respuesta SLA, soporte 24/7..." 
                  style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body);"><?= htmlspecialchars($plan['description'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-block btn-lg">
        <?= $isEdit ? 'Guardar Cambios' : 'Crear Plan Corporativo' ?>
      </button>

    </form>

  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';
