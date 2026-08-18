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

// Obtener categorías para la barra horizontal de navegación (o fallbacks con íconos)
$headerCategories = [
    ['name' => 'Notebooks', 'url' => url('/tienda?categoria=1'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="12" rx="2"/><path d="M2 20h20"/></svg>'],
    ['name' => 'Equipos & PCs', 'url' => url('/tienda?categoria=1'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="16" y1="14" x2="16.01" y2="14"/></svg>'],
    ['name' => 'Redes & Wi-Fi', 'url' => url('/tienda?categoria=2'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>'],
    ['name' => 'Cámaras & CCTV', 'url' => url('/tienda?categoria=3'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>'],
    ['name' => 'Accesorios', 'url' => url('/tienda?categoria=4'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 12h.01"/><path d="M10 12h.01"/><path d="M14 12h.01"/><path d="M18 12h.01"/></svg>'],
    ['name' => 'Servicios Técnicos', 'url' => url('/servicios'), 'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>'],
];
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
        <svg class="header-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
              Servicios
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

  <!-- BARRA HORIZONTAL SECUNDARIA DE CATEGORÍAS (ESTILO DK COMPUTERS) -->
  <div class="nav-categories-bar">
    <div class="container">
      <ul class="nav-categories-list">
        <?php foreach ($headerCategories as $hCat): ?>
          <li>
            <a href="<?= $hCat['url'] ?>" class="nav-category-item">
              <span class="nav-category-icon"><?= $hCat['icon'] ?></span>
              <span><?= htmlspecialchars($hCat['name']) ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

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
