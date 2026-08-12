<?php
/**
 * RedTec Informática - Layout Principal del Panel de Administración
 * 
 * @var string $pageTitle Título de la sección del panel.
 * @var string $activeMenu Identificador del menú activo (dashboard, productos, servicios, planes).
 * @var callable|string $content Contenido HTML principal.
 */

use RedTec\Admin\AdminGuard;

$pageTitle  = $pageTitle ?? 'Panel de Administración — RedTec Informática';
$activeMenu = $activeMenu ?? 'dashboard';
$adminName  = $_SESSION['admin_name'] ?? 'Administrador';
$csrfToken  = AdminGuard::csrfToken();

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  
  <link rel="icon" type="image/png" href="<?= url('/assets/img/Iso PNG.png') ?>">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= url('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/components.css') ?>">

  <style>
    :root {
      --admin-sidebar-width: 250px;
    }

    body {
      background-color: #F3F4F6;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
    }

    /* Sidebar Lateral Dark #1F130F */
    .admin-sidebar {
      width: var(--admin-sidebar-width);
      background-color: #1F130F;
      color: #FFFFFF;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 100;
      box-shadow: 4px 0 15px rgba(0,0,0,0.15);
    }

    .sidebar-brand {
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sidebar-brand img {
      max-width: 150px;
      height: auto;
    }

    .sidebar-nav {
      padding: 1.25rem 0;
      flex-grow: 1;
    }

    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar-item {
      margin-bottom: 0.25rem;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.85rem 1.5rem;
      color: #D2D2D2;
      text-decoration: none;
      font-family: var(--font-heading);
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.2s;
      border-left: 4px solid transparent;
    }

    .sidebar-link:hover {
      color: #FFFFFF;
      background-color: rgba(255,255,255,0.05);
    }

    .sidebar-link.active {
      color: #FFFFFF;
      background-color: rgba(227, 69, 73, 0.15);
      border-left-color: var(--color-primary);
    }

    .sidebar-link.disabled {
      opacity: 0.45;
      cursor: not-allowed;
      pointer-events: none;
    }

    .sidebar-badge-soon {
      margin-left: auto;
      font-size: 0.68rem;
      background: rgba(255,255,255,0.12);
      color: #B0B0B0;
      padding: 0.15rem 0.45rem;
      border-radius: var(--radius-full);
      font-weight: 500;
    }

    .sidebar-footer {
      padding: 1.25rem 1.5rem;
      border-top: 1px solid rgba(255,255,255,0.08);
    }

    /* Main Area */
    .admin-main {
      margin-left: var(--admin-sidebar-width);
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .admin-header {
      background: #FFFFFF;
      border-bottom: 1px solid var(--color-border-light);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow-sm);
    }

    .admin-content {
      padding: 2rem;
      flex-grow: 1;
    }

    .admin-card {
      background: #FFFFFF;
      border-radius: var(--radius-lg);
      border: 1px solid var(--color-border-light);
      box-shadow: var(--shadow-sm);
      padding: 1.75rem;
    }

    .alert-flash-success {
      background-color: #D4EDDA;
      border: 1px solid #C3E6CB;
      color: #155724;
      padding: 0.85rem 1.25rem;
      border-radius: var(--radius-md);
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .alert-flash-error {
      background-color: #F8D7DA;
      border: 1px solid #F5C6CB;
      color: #721C24;
      padding: 0.85rem 1.25rem;
      border-radius: var(--radius-md);
      margin-bottom: 1.5rem;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <!-- BARRA LATERAL (SIDEBAR DARK) -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <a href="<?= url('/admin') ?>">
        <img src="<?= url('/assets/img/Logotipo PNG.png') ?>" alt="RedTec Panel">
      </a>
    </div>

    <nav class="sidebar-nav">
      <ul class="sidebar-menu">
        
        <li class="sidebar-item">
          <a href="<?= url('/admin') ?>" class="sidebar-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
          </a>
        </li>

        <li class="sidebar-item">
          <a href="<?= url('/admin/productos') ?>" class="sidebar-link <?= $activeMenu === 'productos' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            Productos
          </a>
        </li>

        <li class="sidebar-item">
          <a href="<?= url('/admin/servicios') ?>" class="sidebar-link <?= $activeMenu === 'servicios' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            Servicios
          </a>
        </li>

        <li class="sidebar-item">
          <a href="<?= url('/admin/planes') ?>" class="sidebar-link <?= $activeMenu === 'planes' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            Planes Corporativos
          </a>
        </li>

        <li class="sidebar-item">
          <a href="<?= url('/admin/categorias') ?>" class="sidebar-link <?= $activeMenu === 'categorias' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Categorías
          </a>
        </li>

        <li class="sidebar-item">
          <a href="<?= url('/admin/importar') ?>" class="sidebar-link <?= $activeMenu === 'importar' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Importar Catálogo
          </a>
        </li>


      </ul>
    </nav>

    <div class="sidebar-footer">
      <form action="<?= url('/admin/logout') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <button type="submit" class="btn btn-outline" style="width: 100%; color: #FFFFFF; border-color: rgba(255,255,255,0.2); font-size: 0.85rem;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Cerrar Sesión
        </button>
      </form>
    </div>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <div class="admin-main">
    
    <!-- BARRA SUPERIOR -->
    <header class="admin-header">
      <div>
        <h2 style="font-size: 1.25rem; margin: 0; color: var(--color-dark);"><?= htmlspecialchars($pageTitle) ?></h2>
      </div>

      <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= url('/') ?>" target="_blank" class="btn btn-outline-dark btn-sm" title="Abrir sitio web público en pestaña nueva">
          Ver Sitio Público &nearr;
        </a>

        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="width: 38px; height: 38px; background: var(--color-primary); color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: var(--font-heading);">
            <?= strtoupper(substr($adminName, 0, 1)) ?>
          </div>
          <div>
            <strong style="display: block; font-size: 0.9rem; color: var(--color-dark);"><?= htmlspecialchars($adminName) ?></strong>
            <small style="color: var(--color-text-muted); font-size: 0.75rem;">Administrador</small>
          </div>
        </div>
      </div>
    </header>

    <!-- ÁREA DE CONTENIDO Y MENSAJES FLASH -->
    <main class="admin-content">
      
      <?php if (!empty($flashSuccess)): ?>
        <div class="alert-flash-success">
          ✓ <?= htmlspecialchars($flashSuccess) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($flashError)): ?>
        <div class="alert-flash-error">
          ✕ <?= htmlspecialchars($flashError) ?>
        </div>
      <?php endif; ?>

      <?php 
      if (is_callable($content)) {
          $content();
      } else {
          echo $content ?? '';
      }
      ?>
    </main>

  </div>

</body>
</html>
