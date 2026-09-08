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
      <a href="<?= url('/admin/planes') ?>" style="font-size: 0.85rem; color: var(--color-text-muted); text-decoration: none;">&larr; Volver al listado de planes</a>
      <h3 style="margin: 0.25rem 0 0 0; color: var(--color-dark);"><?= $formTitle ?></h3>
    </div>
  </div>

  <div class="admin-card" style="max-width: 750px;">
    
    <form action="<?= $actionUrl ?>" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1.25rem;">
        <!-- Etiqueta del Plan -->
        <div>
          <label for="badge" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
            Etiqueta Superior <span style="color: var(--color-primary);">*</span>
          </label>
          <input type="text" 
                 id="badge" 
                 name="badge" 
                 value="<?= htmlspecialchars($plan['badge'] ?? 'PLAN') ?>" 
                 placeholder="Ej: PLAN 1, PLAN 2, PLAN 3" 
                 required 
                 style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
        </div>

        <!-- Nombre del Plan -->
        <div>
          <label for="name" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
            Título del Plan Corporativo <span style="color: var(--color-primary);">*</span>
          </label>
          <input type="text" 
                 id="name" 
                 name="name" 
                 value="<?= htmlspecialchars($plan['name'] ?? '') ?>" 
                 placeholder="Ej: ESENCIAL, PROFESIONAL, CONTINUIDAD 360°" 
                 required 
                 style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
        </div>
      </div>

      <!-- Precio Mensual $ -->
      <div style="margin-bottom: 1.25rem;">
        <label for="price" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
          Precio Mensual Fijo ($ USD)
        </label>
        <input type="number" 
               step="0.01" 
               min="0" 
               id="price" 
               name="price" 
               value="<?= (!empty($plan['price']) && (float)$plan['price'] > 0) ? htmlspecialchars($plan['price']) : '' ?>" 
               placeholder="Ej: 150.00 (Dejar vacío para mostrar CONSULTAR)" 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.95rem;">
        <small style="display: block; color: var(--color-primary); font-size: 0.8rem; margin-top: 0.35rem; font-weight: 600;">
          💡 <strong>Nota:</strong> Si dejás el campo de precio vacío, la web mostrará <em>"CONSULTAR - PIDE TU PROPUESTA PERSONALIZADA"</em> con botón a WhatsApp.
        </small>
      </div>

      <!-- Descripción / Subtítulo del Plan -->
      <div style="margin-bottom: 1.25rem;">
        <label for="description" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
          Descripción Corta / Subtítulo del Plan
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="3" 
                  placeholder="Ej: Nos ocupamos de que tu tecnología funcione para que vos puedas dedicarte a tu empresa." 
                  style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem; line-height: 1.5;"><?= htmlspecialchars($plan['description'] ?? '') ?></textarea>
      </div>

      <!-- Banner / Frase Destacada Abajo de Descripción -->
      <div style="margin-bottom: 1.25rem;">
        <label for="banner" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
          Banner / Frase Destacada Abajo de Descripción (Opcional)
        </label>
        <input type="text" 
               id="banner" 
               name="banner" 
               value="<?= htmlspecialchars($plan['banner'] ?? '') ?>" 
               placeholder="Ej: SOPORTE INTEGRAL: REDES, SERVIDORES, WIFI, CÁMARAS, ACCESOS..." 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
      </div>

      <!-- Encabezado de la lista de ítems -->
      <div style="margin-bottom: 1.25rem;">
        <label for="includes_tag" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
          Título de la Lista de Incluye (Encabezado de Cobertura)
        </label>
        <input type="text" 
               id="includes_tag" 
               name="includes_tag" 
               value="<?= htmlspecialchars($plan['includes_tag'] ?? 'INCLUYE:') ?>" 
               placeholder="Ej: INCLUYE:, INCLUYE TODO DEL PLAN 1, MÁS:, INCLUYE TODO DEL PLAN 2, MÁS:" 
               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem;">
      </div>

      <!-- Lo que Incluye (Ítems / Coberturas) -->
      <div style="margin-bottom: 1.75rem;">
        <label for="includes" style="display: block; font-family: var(--font-heading); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.35rem; color: var(--color-dark);">
          Lo que Incluye (Ítems / Cobertura del Servicio)
        </label>
        
        <div style="background-color: #F8FAFC; border: 1px solid var(--color-border-light); border-radius: var(--radius-md); padding: 0.75rem 1rem; margin-bottom: 0.75rem; font-size: 0.82rem; color: var(--color-text-secondary); line-height: 1.4;">
          💡 <strong>Tip:</strong> Ingresá cada servicio o cobertura incluida en su propia línea. Se mostrarán con el ícono de verificación (✓) en la web.<br>
          <em>Ejemplo:</em><br>
          Atención de emergencias 24/7<br>
          Respuesta inmediata ante incidentes críticos<br>
          Soporte integral (redes, servidores, WiFi, cámaras...)
        </div>

        <textarea id="includes" 
                  name="includes" 
                  rows="8" 
                  placeholder="Atención de emergencias 24/7
Respuesta inmediata ante incidentes críticos
Soporte integral (redes, servidores, WiFi...)" 
                  style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-metallic); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 0.9rem; line-height: 1.5;"><?= htmlspecialchars($plan['includes'] ?? '') ?></textarea>
      </div>

      <div style="display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary btn-lg" style="flex-grow: 1;">
          <?= $isEdit ? 'Guardar Cambios' : 'Crear Plan Corporativo' ?>
        </button>
        <a href="<?= url('/admin/planes') ?>" class="btn btn-outline-dark btn-lg">
          Cancelar
        </a>
      </div>

    </form>

  </div>
<?php
};

require REDTEC_SHARED_DIR . '/Layout/admin-layout.php';

