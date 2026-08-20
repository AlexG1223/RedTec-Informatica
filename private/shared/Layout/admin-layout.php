<?php
/**
 * RedTec Informática - Layout Principal del Panel de Administración (Estilo Refinado de Marca)
 * 
 * @var string $pageTitle Título de la página
 * @var string $activeMenu Menú activo ('dashboard', 'productos', 'categorias', 'servicios', 'planes', 'importar')
 * @var callable|string $content Contenido de la vista
 */

// Cargar configuración general del sitio (detector de entorno y helper url())
require_once __DIR__ . '/../../config/site.php';

$pageTitle  = $pageTitle ?? 'Panel de Administración — RedTec Informática';
$activeMenu = $activeMenu ?? 'dashboard';

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$adminName  = $_SESSION['admin_name'] ?? 'Administrador';
$csrfToken  = \RedTec\Admin\AdminGuard::csrfToken();
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="robots" content="noindex, nofollow">
  
  <link rel="icon" type="image/png" href="<?= url('/assets/img/Iso PNG.png') ?>">
  
  <!-- Google Fonts: Montserrat & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Hojas de Estilo Base -->
  <link rel="stylesheet" href="<?= url('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/components.css') ?>">

  <style>
    /* Estilos Específicos del Layout Admin */
    body.admin-body {
      background-color: #F3F4F6;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
    }

    .admin-wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    /* Sidebar Lateral Oscuro */
    .admin-sidebar {
      width: 260px;
      background-color: var(--color-dark);
      color: #FFFFFF;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      border-right: 3px solid var(--color-primary);
      box-shadow: 4px 0 20px rgba(0,0,0,0.3);
      position: sticky;
      top: 0;
      height: 100vh;
      z-index: 100;
    }

    .sidebar-brand {
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .sidebar-brand img {
      height: 42px;
      width: auto;
      object-fit: contain;
    }

    .sidebar-menu {
      padding: 1.25rem 0.85rem;
      display: flex;
      flex-direction: column;
      gap: 0.35rem;
      flex-grow: 1;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      color: #D2D2D2;
      font-family: var(--font-heading);
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: var(--radius-md);
      text-decoration: none;
      transition: background 0.2s, color 0.2s, transform 0.15s;
    }

    .sidebar-link svg {
      width: 20px;
      height: 20px;
      stroke: currentColor;
    }

    .sidebar-link:hover, .sidebar-link.active {
      background-color: var(--color-primary);
      color: #FFFFFF;
      transform: translateX(3px);
    }

    .sidebar-user {
      padding: 1.25rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      background: var(--color-dark-surface);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Main Content Area */
    .admin-main {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .admin-header {
      background: #FFFFFF;
      height: 70px;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--color-border-light);
      box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .admin-content {
      padding: 2rem;
      flex-grow: 1;
    }

    /* Flash Alerts */
    .alert-flash {
      padding: 1rem 1.25rem;
      border-radius: var(--radius-md);
      margin-bottom: 1.5rem;
      font-family: var(--font-heading);
      font-size: 0.9rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow-sm);
    }

    .alert-flash-success {
      background-color: #D1FAE5;
      color: #065F46;
      border: 1px solid #A7F3D0;
    }

    .alert-flash-error {
      background-color: #FEE2E2;
      color: #991B1B;
      border: 1px solid #FCA5A5;
    }

    @media (max-width: 991px) {
      .admin-wrapper { flex-direction: column; }
      .admin-sidebar { width: 100%; height: auto; position: static; }
      .admin-header { padding: 0 1rem; }
      .admin-content { padding: 1rem; }
    }
  </style>
</head>
<body class="admin-body">

<div class="admin-wrapper">
  
  <!-- SIDEBAR LATERAL DE NAVEGACIÓN -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <img src="<?= url('/assets/img/Logotipo PNG.png') ?>" alt="RedTec">
    </div>

    <nav style="flex-grow: 1;">
      <ul class="sidebar-menu">
        <li>
          <a href="<?= url('/admin') ?>" class="sidebar-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
          </a>
        </li>
        <li>
          <a href="<?= url('/admin/productos') ?>" class="sidebar-link <?= $activeMenu === 'productos' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            Productos
          </a>
        </li>
        <li>
          <a href="<?= url('/admin/categorias') ?>" class="sidebar-link <?= $activeMenu === 'categorias' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Categorías
          </a>
        </li>
        <li>
          <a href="<?= url('/admin/servicios') ?>" class="sidebar-link <?= $activeMenu === 'servicios' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            Servicios Técnicos
          </a>
        </li>
        <li>
          <a href="<?= url('/admin/planes') ?>" class="sidebar-link <?= $activeMenu === 'planes' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Planes Corporativos
          </a>
        </li>
        <li>
          <a href="<?= url('/admin/importar') ?>" class="sidebar-link <?= $activeMenu === 'importar' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Importar Catálogo
          </a>
        </li>
      </ul>
    </nav>

    <!-- Usuario y Cerrar Sesión -->
    <div class="sidebar-user">
      <div style="font-size: 0.85rem;">
        <strong style="display: block; color: #FFF;"><?= htmlspecialchars($adminName) ?></strong>
        <span style="color: #9CA3AF; font-size: 0.75rem;">Administrador</span>
      </div>

      <form action="<?= url('/admin/logout') ?>" method="POST" style="margin: 0;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" style="background: transparent; border: none; color: #EF4444; cursor: pointer; padding: 4px; display: flex;" title="Cerrar Sesión">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </button>
      </form>
    </div>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="admin-main">
    
    <!-- BARRA SUPERIOR ADMIN -->
    <header class="admin-header">
      <div style="font-family: var(--font-heading); font-weight: 700; font-size: 1.1rem; color: var(--color-dark);">
        <?= htmlspecialchars($pageTitle) ?>
      </div>

      <div>
        <a href="<?= url('/') ?>" target="_blank" class="btn btn-outline-dark btn-sm">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Ver Sitio Público
        </a>
      </div>
    </header>

    <!-- CUERPO DE LA VISTA CON FLASH ALERTS -->
    <div class="admin-content">
      
      <?php if ($flashSuccess): ?>
        <div class="alert-flash alert-flash-success">
          <span>✓ <?= htmlspecialchars($flashSuccess) ?></span>
          <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; cursor: pointer; color: currentColor; font-weight: bold;">✕</button>
        </div>
      <?php endif; ?>

      <?php if ($flashError): ?>
        <div class="alert-flash alert-flash-error">
          <span>✕ <?= htmlspecialchars($flashError) ?></span>
          <button type="button" onclick="this.parentElement.remove()" style="background: transparent; border: none; cursor: pointer; color: currentColor; font-weight: bold;">✕</button>
        </div>
      <?php endif; ?>

      <?php
      if (is_callable($content)) {
          $content();
      } else {
          echo $content ?? '';
      }
      ?>
    </div>

  </main>

</div>

</body>
</html>
