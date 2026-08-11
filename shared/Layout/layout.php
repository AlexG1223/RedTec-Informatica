<?php
/**
 * RedTec Informática - Layout Principal Base
 * 
 * @param string $pageTitle Título específico de la página.
 * @param string $pageDescription Meta descripción para SEO.
 * @param string $currentPage Identificador para la pestaña activa del menú.
 * @param int $cartCount Cantidad de items en el carrito.
 * @param callable|string $content Contenido HTML o función anónima que imprime el contenido.
 */

$pageTitle       = $pageTitle ?? 'RedTec Informática - Tienda & Servicios Tecnológicos';
$pageDescription = $pageDescription ?? 'Venta de productos informáticos, instalación de cámaras de seguridad, servidores, redes y soporte técnico corporativo en Uruguay.';
$currentPage     = $currentPage ?? '';
$cartCount       = $cartCount ?? 0;
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  
  <!-- Favicon / Isotipo -->
  <link rel="icon" type="image/png" href="/assets/img/Iso PNG.png">

  <!-- Google Fonts: Montserrat (Headings) e Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Hojas de Estilo Base del Sistema de Diseño -->
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/base.css">
  <link rel="stylesheet" href="/assets/css/components.css">
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

</body>
</html>
