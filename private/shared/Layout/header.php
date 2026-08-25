<?php
/**
 * RedTec Informática - Layout Header Institucional (Estilo Inspirado en DK Computers)
 * 
 * @var string $currentPage Identificador de la página activa
 * @var int $cartCount Cantidad de items en el carrito
 */

$currentPage = $currentPage ?? '';
$cartCount   = $cartCount ?? 0;
$logoUrl     = url('/assets/img/Logotipo PNG.png');

?>
<header class="site-header">
  
  <!-- BARRA SUPERIOR CON LOGO, BUSCADOR Y NAV -->
  <div class="container">
    <div class="header-top-bar">
      
      <!-- Logotipo -->
      <a href="<?= url('/') ?>" class="site-logo" title="RedTec Informática - Inicio">
        <img src="<?= $logoUrl ?>" alt="RedTec Informática" width="180" height="48">
      </a>

      <!-- Buscador Integrado estilo DK Computers -->
      <form action="<?= url('/tienda') ?>" method="GET" class="header-search-form" role="search">
        <svg class="header-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" 
               name="buscar" 
               class="header-search-input" 
               placeholder="Buscar productos, marcas, periféricos..." 
               value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
               aria-label="Buscar productos en el catálogo">
      </form>

      <!-- Navegación Principal Estructurada (Sin Modificar Rutas ni Ítems) -->
      <nav class="site-nav" id="siteNav" aria-label="Navegación principal">
        <ul class="nav-list">
          <li>
            <a href="<?= url('/') ?>" class="nav-link <?= $currentPage === 'inicio' ? 'active' : '' ?>">
              Inicio
            </a>
          </li>
          <li>
            <a href="<?= url('/tienda') ?>" class="nav-link <?= $currentPage === 'tienda' ? 'active' : '' ?>">
              Tienda
            </a>
          </li>
          <li>
            <a href="<?= url('/servicios') ?>" class="nav-link <?= $currentPage === 'servicios' ? 'active' : '' ?>">
              Servicios Técnicos
            </a>
          </li>
          <li>
            <a href="<?= url('/servicios-corporativos') ?>" class="nav-link <?= $currentPage === 'corporativos' ? 'active' : '' ?>">
              Planes Corporativos
            </a>
          </li>
          <li>
            <a href="<?= url('/contacto') ?>" class="nav-link <?= $currentPage === 'contacto' ? 'active' : '' ?>">
              Contacto
            </a>
          </li>
        </ul>
      </nav>

      <!-- Acciones de Usuario (Ingresar / Admin + Carrito) -->
      <div class="header-actions">
        
        <?php if (!empty($_SESSION['admin_id'])): ?>
          <a href="<?= url('/admin') ?>" class="user-login-link" title="Ir al Panel de Administración">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Panel Admin</span>
          </a>
          <a href="<?= url('/admin/logout') ?>" class="user-login-link" style="color: #EF4444; border-color: rgba(239, 68, 68, 0.3);" title="Cerrar Sesión de Administración">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Salir</span>
          </a>
        <?php else: ?>
          <a href="<?= url('/admin/login') ?>" class="user-login-link" title="Acceso Administración">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Ingresar</span>
          </a>
        <?php endif; ?>

        <!-- Disparador del Drawer de Carrito -->
        <button type="button" 
                class="cart-trigger" 
                id="cartDrawerOpen" 
                aria-label="Abrir carrito de compras" 
                title="Ver carrito de compras">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          <span class="cart-badge" id="cartHeaderBadge"><?= (int)$cartCount ?></span>
        </button>

        <!-- Botón Menú Mobile -->
        <button type="button" 
                class="menu-toggle" 
                id="menuToggle" 
                aria-label="Abrir menú de navegación" 
                aria-expanded="false" 
                aria-controls="siteNav">
          <svg viewBox="0 0 24 24">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
          </svg>
        </button>

      </div>

    </div>
  </div>

  <!-- BARRA HORIZONTAL SECUNDARIA DE CATEGORÍAS (SOLO EN SECCIÓN TIENDA) -->
  <?php if ($currentPage === 'tienda'): ?>
    <?php
      $headerCategoriesList = [];
      try {
          $catRepo = new \RedTec\Categorias\CategoriaRepository();
          $dbCategories = $catRepo->listarActivas();
          foreach ($dbCategories as $c) {
              $catId   = (int)$c['id'];
              $catName = htmlspecialchars($c['name']);
              $catUrl  = url('/tienda?categoria=' . $catId);
              
              $rawImg = !empty($c['image_url']) ? $c['image_url'] : null;
              if (!$rawImg) {
                  $lower = mb_strtolower($c['name'], 'UTF-8');
                  if (strpos($lower, 'notebook') !== false || strpos($lower, 'equipo') !== false || strpos($lower, 'pc') !== false) {
                      $rawImg = '/assets/img/categories/notebooks.jpg';
                  } elseif (strpos($lower, 'red') !== false || strpos($lower, 'wifi') !== false || strpos($lower, 'wi-fi') !== false || strpos($lower, 'conectividad') !== false) {
                      $rawImg = '/assets/img/categories/redes.jpg';
                  } elseif (strpos($lower, 'cámara') !== false || strpos($lower, 'camara') !== false || strpos($lower, 'cctv') !== false || strpos($lower, 'seguridad') !== false) {
                      $rawImg = '/assets/img/categories/camaras.jpg';
                  } elseif (strpos($lower, 'accesorio') !== false) {
                      $rawImg = '/assets/img/categories/accesorios.jpg';
                  } else {
                      $rawImg = '/assets/img/categories/notebooks.jpg';
                  }
              }
              $imgUrl = strpos($rawImg, 'http') === 0 ? htmlspecialchars($rawImg) : url($rawImg);

              $headerCategoriesList[] = [
                  'id'    => $catId,
                  'name'  => $catName,
                  'url'   => $catUrl,
                  'image' => $imgUrl
              ];
          }
      } catch (Throwable $e) {
          $headerCategoriesList = [];
      }
    ?>
    <?php if (!empty($headerCategoriesList)): ?>
      <div class="nav-categories-bar">
        <div class="container">
          <ul class="nav-categories-list" style="padding: 0.75rem 0;">
            <?php foreach ($headerCategoriesList as $hCat): ?>
              <li>
                <a href="<?= $hCat['url'] ?>" class="nav-category-item" style="display: flex; align-items: center; gap: 0.65rem; padding: 0.4rem 0.85rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                  <img src="<?= $hCat['image'] ?>" alt="<?= $hCat['name'] ?>" style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.3); box-shadow: 0 2px 4px rgba(0,0,0,0.4);" onerror="this.style.display='none';">
                  <span style="font-weight: 700; font-size: 0.82rem; tracking: 0.03em;"><?= $hCat['name'] ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>

</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('menuToggle');
  const siteNav   = document.getElementById('siteNav');

  if (toggleBtn && siteNav) {
    toggleBtn.addEventListener('click', function() {
      const isOpen = siteNav.classList.toggle('is-open');
      toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }
});
</script>
