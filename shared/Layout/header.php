<?php
/**
 * RedTec Informática - Partial Header
 * 
 * @var string $currentPage (opcional, para resaltar el enlace activo)
 * @var int $cartCount (opcional, cantidad de items en el carrito)
 */

$currentPage = $currentPage ?? '';
$cartCount   = $cartCount ?? 0;
?>
<header class="site-header">
  <div class="container header-container">
    
    <!-- Logo Institucional -->
    <a href="/index.php" class="site-logo" title="RedTec Informática - Inicio">
      <img src="/assets/img/Logotipo PNG.png" alt="RedTec Informática Logo" width="180" height="48">
    </a>

    <!-- Navegación Principal -->
    <nav class="site-nav" id="siteNav">
      <ul class="nav-list">
        <li>
          <a href="/index.php" class="nav-link <?= $currentPage === 'inicio' ? 'active' : '' ?>">Inicio</a>
        </li>
        <li>
          <a href="/productos.php" class="nav-link <?= $currentPage === 'tienda' ? 'active' : '' ?>">Tienda</a>
        </li>
        <li>
          <a href="/servicios-tecnicos.php" class="nav-link <?= $currentPage === 'servicios' ? 'active' : '' ?>">Servicios Técnicos</a>
        </li>
        <li>
          <a href="/servicios-corporativos.php" class="nav-link <?= $currentPage === 'corporativos' ? 'active' : '' ?>">Servicios Corporativos</a>
        </li>
        <li>
          <a href="/contacto.php" class="nav-link <?= $currentPage === 'contacto' ? 'active' : '' ?>">Contacto</a>
        </li>
      </ul>
    </nav>

    <!-- Acciones Derecha (Carrito & Menú Mobile) -->
    <div class="header-actions">
      <a href="/carrito.php" class="cart-trigger" title="Ver Carrito de Compras">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span class="cart-badge" id="cartCountBadge"><?= (int)$cartCount ?></span>
      </a>

      <!-- Botón Menú Mobile Hamburguesa -->
      <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menú de navegación" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
      </button>
    </div>

  </div>
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

    // Cerrar al hacer clic fuera en mobile
    document.addEventListener('click', function(event) {
      if (!siteNav.contains(event.target) && !menuToggle.contains(event.target)) {
        siteNav.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }
});
</script>
