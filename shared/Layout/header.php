<?php
/**
 * RedTec Informática - Layout Header (Estilo DK Computers adaptado a Paleta RedTec)
 * 
 * @var string $currentPage (opcional, para resaltar el enlace activo)
 * @var int $cartCount (opcional, cantidad de items en el carrito)
 */

$currentPage = $currentPage ?? '';
$cartCount   = $cartCount ?? 0;
$buscarQuery = isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '';
?>
<header class="site-header-dk">
  <!-- 1. BARRA SUPERIOR (LOGO, BÚSQUEDA Y USUARIO/CARRITO) -->
  <div class="header-top-row">
    <div class="container header-top-container">
      
      <!-- Logo Institucional -->
      <a href="<?= url('/') ?>" class="header-logo" title="RedTec Informática - Inicio">
        <img src="<?= url('/assets/img/Logotipo PNG.png') ?>" alt="RedTec Informática Logo" width="170" height="45">
      </a>

      <!-- Buscador Central Integrado -->
      <form action="<?= url('/tienda') ?>" method="GET" class="header-search-form">
        <div class="header-search-input-wrap">
          <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" 
                 name="buscar" 
                 value="<?= $buscarQuery ?>" 
                 placeholder="Buscar productos, marcas, notebooks, cámaras..." 
                 aria-label="Buscar productos" 
                 autocomplete="off">
        </div>
        <button type="submit" class="header-search-btn">Buscar</button>
      </form>

      <!-- Acciones de Usuario y Carrito -->
      <div class="header-right-actions">
        
        <!-- Botón Ingresar / Panel Admin -->
        <a href="<?= url('/admin/login') ?>" class="header-user-btn" title="Acceso al Panel de Administración">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Ingresar</span>
        </a>

        <!-- Trigger del Carrito -->
        <a href="<?= url('/checkout') ?>" class="cart-trigger" title="Ver Carrito de Compras" onclick="if(window.CartUI) { window.CartUI.toggleDrawer(); return false; }">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          <span class="cart-badge" id="cartCountBadge"><?= (int)$cartCount ?></span>
        </a>

        <!-- Botón Menú Mobile Hamburguesa -->
        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación" aria-expanded="false">
          <svg viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
        </button>

      </div>

    </div>
  </div>

  <!-- 2. NAVEGACIÓN HORIZONTAL POR CATEGORÍAS CON ÍCONOS (Estilo DK Computers) -->
  <nav class="header-categories-nav" id="siteNav">
    <div class="container">
      <ul class="header-cat-list">
        
        <li>
          <a href="<?= url('/tienda?buscar=Equipos') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Equipos') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            <span>Equipos</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Monitor') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Monitor') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="12" y1="17" x2="12" y2="21"/><line x1="8" y1="21" x2="16" y2="21"/></svg>
            <span>Monitores</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Notebook') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Notebook') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M2 20h20"/></svg>
            <span>Notebooks</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Hardware') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Hardware') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/></svg>
            <span>Hardware</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Accesorios') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Accesorios') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a4 4 0 0 0-4 4v7a4 4 0 0 0 8 0V6a4 4 0 0 0-4-4z"/><line x1="12" y1="19" x2="12" y2="22"/></svg>
            <span>Accesorios</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Gamer') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Gamer') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><circle cx="15" cy="13" r="1"/><circle cx="18" cy="11" r="1"/><rect x="2" y="6" width="20" height="12" rx="6"/></svg>
            <span>Zona Gamer</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Redes') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Redes') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
            <span>Conectividad</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/tienda?buscar=Camara') ?>" class="header-cat-item <?= ($currentPage === 'tienda' && $buscarQuery === 'Camara') ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Seguridad CCTV</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/servicios') ?>" class="header-cat-item <?= $currentPage === 'servicios' ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            <span>Servicios</span>
          </a>
        </li>

        <li>
          <a href="<?= url('/servicios-corporativos') ?>" class="header-cat-item <?= $currentPage === 'corporativos' ? 'active' : '' ?>">
            <svg class="cat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
            <span>Planes PyME</span>
          </a>
        </li>

      </ul>
    </div>
  </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const menuToggle = document.getElementById('menuToggle');
  const siteNav = document.getElementById('siteNav');

  if (menuToggle && siteNav) {
    menuToggle.addEventListener('click', function() {
      const isOpen = siteNav.classList.toggle('is-open');
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function(event) {
      if (!siteNav.contains(event.target) && !menuToggle.contains(event.target)) {
        siteNav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
});
</script>
