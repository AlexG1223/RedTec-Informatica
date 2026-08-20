<?php
/**
 * RedTec Informática - Layout Principal Base con SEO Ampliado (OpenGraph, Twitter Cards, Canonical, Schema.org)
 * 
 * @param string $pageTitle Título específico de la página.
 * @param string $pageDescription Meta descripción para SEO.
 * @param string|null $canonicalUrl URL canónica (por defecto URL limpia sin query string).
 * @param string|null $metaRobots Meta etiqueta robots (ej: 'index, follow' o 'noindex, nofollow').
 * @param string|null $ogTitle Título OpenGraph.
 * @param string|null $ogDescription Descripción OpenGraph.
 * @param string|null $ogImage URL de imagen OpenGraph.
 * @param string|null $ogType Tipo OpenGraph ('website', 'product', etc.).
 * @param array|null $jsonLdData Estructura de datos JSON-LD Schema.org.
 * @param string $currentPage Identificador para la pestaña activa del menú.
 * @param int $cartCount Cantidad de items en el carrito.
 * @param callable|string $content Contenido HTML o función anónima.
 */

// Cargar configuración general del sitio (detector de entorno y helper url())
require_once __DIR__ . '/../../config/site.php';

$pageTitle       = $pageTitle ?? 'RedTec Informática - Tienda & Servicios Tecnológicos en Uruguay';
$pageDescription = $pageDescription ?? 'Venta de productos informáticos, instalación de cámaras de seguridad, servidores, redes y soporte técnico corporativo en Atlántida y todo Uruguay.';
$currentPage     = $currentPage ?? '';
$cartCount       = $cartCount ?? 0;

// URL canónica por defecto (limpia sin query string)
$requestPath     = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$canonicalUrl    = $canonicalUrl ?? absolute_url($requestPath);
$metaRobots      = $metaRobots ?? 'index, follow';

$ogTitle         = $ogTitle ?? $pageTitle;
$ogDescription   = $ogDescription ?? $pageDescription;
$ogImage         = $ogImage ?? absolute_url('/assets/img/Logotipo PNG.png');
$ogType          = $ogType ?? 'website';
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Título y Meta Descripción -->
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <meta name="robots" content="<?= htmlspecialchars($metaRobots) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">

  <?php if (defined('GOOGLE_SITE_VERIFICATION') && !empty(GOOGLE_SITE_VERIFICATION)): ?>
    <!-- Verificación Google Search Console -->
    <meta name="google-site-verification" content="<?= htmlspecialchars(GOOGLE_SITE_VERIFICATION) ?>">
  <?php endif; ?>

  <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
  <meta property="og:site_name" content="RedTec Informática">
  <meta property="og:locale" content="es_UY">
  <meta property="og:type" content="<?= htmlspecialchars($ogType) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($ogDescription) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($ogTitle) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($ogDescription) ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

  <?php if (!empty($jsonLdData)): ?>
    <!-- Datos Estructurados JSON-LD Schema.org -->
    <?= \RedTec\SEO\StructuredDataBuilder::render($jsonLdData) ?>
  <?php endif; ?>

  <!-- Base URL adaptativo para JavaScript -->
  <script>window.REDTEC_BASE_URL = "<?= rtrim(url(), '/') ?>";</script>

  <!-- Favicon / Isotipo -->
  <link rel="icon" type="image/png" href="<?= url('/assets/img/Iso PNG.png') ?>">

  <!-- Google Fonts: Montserrat (Headings) e Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Hojas de Estilo Base del Sistema de Diseño -->
  <link rel="stylesheet" href="<?= url('/assets/css/variables.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= url('/assets/css/components.css') ?>">

  <!-- Scripts JavaScript del Carrito (Client-side) -->
  <script src="<?= url('/assets/js/carrito/cart-service.js') ?>" defer></script>
  <script src="<?= url('/assets/js/carrito/cart-ui.js') ?>" defer></script>
</head>
<body>

  <!-- Header Institucional -->
  <?php include __DIR__ . '/header.php'; ?>

  <!-- Contenido Principal de la Página -->
  <main>
    <?php 
    if (is_callable($content)) {
        $content();
    } else {
        echo $content ?? '';
    }
    ?>
  </main>

  <!-- Footer Institucional & WhatsApp Flotante -->
  <?php include __DIR__ . '/footer.php'; ?>

  <!-- Panel Deslizante del Carrito (Cart Drawer) -->
  <?php include __DIR__ . '/cart-drawer.php'; ?>

</body>
</html>
