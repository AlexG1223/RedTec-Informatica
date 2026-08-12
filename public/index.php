<?php

/**
 * RedTec Informática - Punto de Entrada Principal (Front Controller & Router con Métodos HTTP)
 */

// Cargar configuración general del sitio (detector de entorno y helper url())
require_once __DIR__ . '/../config/site.php';

// Autoloader PSR-4 para clases en /src y /shared
spl_autoload_register(function ($class) {
    $prefixes = [
        'RedTec\\Shared\\'               => __DIR__ . '/../shared/',
        'RedTec\\Home\\'                 => __DIR__ . '/../src/Home/',
        'RedTec\\Categorias\\'           => __DIR__ . '/../src/Categorias/',
        'RedTec\\Productos\\'            => __DIR__ . '/../src/Productos/',
        'RedTec\\Checkout\\'             => __DIR__ . '/../src/Checkout/',
        'RedTec\\ServiciosTecnicos\\'   => __DIR__ . '/../src/ServiciosTecnicos/',
        'RedTec\\ServiciosCorporativos\\' => __DIR__ . '/../src/ServiciosCorporativos/',
        'RedTec\\Admin\\'                => __DIR__ . '/../src/Admin/',
        'RedTec\\SEO\\'                  => __DIR__ . '/../src/SEO/',
        'RedTec\\'                       => __DIR__ . '/../src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

// Iniciar sesión PHP si aún no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Normalización de URI y Método de la petición
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$requestUri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Soporte para despliegue en subdirectorios (ej: /RedTec/public)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir !== '/' && strpos($requestUri, $scriptDir) === 0) {
    $requestUri = substr($requestUri, strlen($scriptDir));
}

$uri = '/' . trim($requestUri, '/');

// Tabla de Rutas [Metodo, Ruta/Patron, [ClaseControlador, MetodoAccion], esRegex (bool)]
$routes = [
    // --- RUTAS PÚBLICAS Y SEO ---
    ['GET', '/', [\RedTec\Home\HomeController::class, 'index']],
    ['GET', '/index.php', [\RedTec\Home\HomeController::class, 'index']],
    ['GET', '/tienda', [\RedTec\Productos\CatalogoController::class, 'index']],
    ['GET', '/checkout', [\RedTec\Checkout\CheckoutController::class, 'index']],
    ['GET', '/servicios', [\RedTec\ServiciosTecnicos\ServicioController::class, 'index']],
    ['GET', '/servicios-corporativos', [\RedTec\ServiciosCorporativos\ServicioPackageController::class, 'index']],
    ['GET', '/sitemap.xml', [\RedTec\SEO\SitemapGenerator::class, 'generate']],
    ['GET', '#^/producto/(\d+)$#', [\RedTec\Productos\ProductoController::class, 'show'], true],

    // --- RUTAS DE AUTENTICACIÓN ADMIN ---
    ['GET',  '/admin/login', [\RedTec\Admin\AuthController::class, 'loginForm']],
    ['POST', '/admin/login', [\RedTec\Admin\AuthController::class, 'login']],
    ['POST', '/admin/logout', [\RedTec\Admin\AuthController::class, 'logout']],

    // --- RUTAS DEL PANEL DE ADMINISTRACIÓN ---
    ['GET',  '/admin', [\RedTec\Admin\DashboardController::class, 'index']],

    // CRUD Categorías
    ['GET',  '/admin/categorias', [\RedTec\Admin\CategoriaAdminController::class, 'index']],
    ['GET',  '/admin/categorias/nuevo', [\RedTec\Admin\CategoriaAdminController::class, 'crearForm']],
    ['POST', '/admin/categorias', [\RedTec\Admin\CategoriaAdminController::class, 'guardar']],
    ['GET',  '#^/admin/categorias/(\d+)/editar$#', [\RedTec\Admin\CategoriaAdminController::class, 'editarForm'], true],
    ['POST', '#^/admin/categorias/(\d+)$#', [\RedTec\Admin\CategoriaAdminController::class, 'actualizar'], true],
    ['POST', '#^/admin/categorias/(\d+)/eliminar$#', [\RedTec\Admin\CategoriaAdminController::class, 'eliminar'], true],

    // Importación Masiva CSV
    ['GET',  '/admin/importar', [\RedTec\Admin\ImportAdminController::class, 'index']],
    ['GET',  '/admin/importar/plantilla', [\RedTec\Admin\ImportAdminController::class, 'plantilla']],
    ['POST', '/admin/importar/previsualizar', [\RedTec\Admin\ImportAdminController::class, 'previsualizar']],
    ['POST', '/admin/importar/confirmar', [\RedTec\Admin\ImportAdminController::class, 'confirmar']],
    ['GET',  '/admin/importar/historial', [\RedTec\Admin\ImportAdminController::class, 'historial']],

    // CRUD Productos
    ['GET',  '/admin/productos', [\RedTec\Admin\ProductoAdminController::class, 'index']],
    ['GET',  '/admin/productos/exportar', [\RedTec\Admin\ProductoAdminController::class, 'exportarCsv']],
    ['GET',  '/admin/productos/nuevo', [\RedTec\Admin\ProductoAdminController::class, 'crearForm']],
    ['POST', '/admin/productos', [\RedTec\Admin\ProductoAdminController::class, 'guardar']],
    ['GET',  '#^/admin/productos/(\d+)/editar$#', [\RedTec\Admin\ProductoAdminController::class, 'editarForm'], true],
    ['POST', '#^/admin/productos/(\d+)$#', [\RedTec\Admin\ProductoAdminController::class, 'actualizar'], true],
    ['POST', '#^/admin/productos/(\d+)/baja$#', [\RedTec\Admin\ProductoAdminController::class, 'cambiarEstado'], true],
    ['POST', '#^/admin/productos/(\d+)/stock$#', [\RedTec\Admin\ProductoAdminController::class, 'actualizarStockAjax'], true],
    ['POST', '#^/admin/productos/(\d+)/imagenes/subir$#', [\RedTec\Admin\ProductoAdminController::class, 'subirImagen'], true],
    ['POST', '#^/admin/productos/(\d+)/imagenes/(\d+)/eliminar$#', [\RedTec\Admin\ProductoAdminController::class, 'eliminarImagen'], true],

    // CRUD Servicios
    ['GET',  '/admin/servicios', [\RedTec\Admin\ServicioAdminController::class, 'index']],
    ['GET',  '/admin/servicios/nuevo', [\RedTec\Admin\ServicioAdminController::class, 'crearForm']],
    ['POST', '/admin/servicios', [\RedTec\Admin\ServicioAdminController::class, 'guardar']],
    ['GET',  '#^/admin/servicios/(\d+)/editar$#', [\RedTec\Admin\ServicioAdminController::class, 'editarForm'], true],
    ['POST', '#^/admin/servicios/(\d+)$#', [\RedTec\Admin\ServicioAdminController::class, 'actualizar'], true],
    ['POST', '#^/admin/servicios/(\d+)/baja$#', [\RedTec\Admin\ServicioAdminController::class, 'cambiarEstado'], true],

    // CRUD Planes Corporativos
    ['GET',  '/admin/planes', [\RedTec\Admin\ServicioPackageAdminController::class, 'index']],
    ['GET',  '/admin/planes/nuevo', [\RedTec\Admin\ServicioPackageAdminController::class, 'crearForm']],
    ['POST', '/admin/planes', [\RedTec\Admin\ServicioPackageAdminController::class, 'guardar']],
    ['GET',  '#^/admin/planes/(\d+)/editar$#', [\RedTec\Admin\ServicioPackageAdminController::class, 'editarForm'], true],
    ['POST', '#^/admin/planes/(\d+)$#', [\RedTec\Admin\ServicioPackageAdminController::class, 'actualizar'], true],
    ['POST', '#^/admin/planes/(\d+)/baja$#', [\RedTec\Admin\ServicioPackageAdminController::class, 'cambiarEstado'], true],
];

// Resolución de Rutas
$matched = false;

foreach ($routes as $route) {
    $method  = $route[0];
    $pattern = $route[1];
    $handler = $route[2];
    $isRegex = $route[3] ?? false;

    if ($method !== $requestMethod) {
        continue;
    }

    if ($isRegex) {
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Eliminar la coincidencia completa
            $controllerClass = $handler[0];
            $actionMethod    = $handler[1];

            $controller = new $controllerClass();
            call_user_func_array([$controller, $actionMethod], $matches);
            $matched = true;
            break;
        }
    } else {
        if ($pattern === $uri) {
            $controllerClass = $handler[0];
            $actionMethod    = $handler[1];

            $controller = new $controllerClass();
            $controller->$actionMethod();
            $matched = true;
            break;
        }
    }
}

// Si la ruta no coincide, enviar código HTTP 404 estricto antes de renderizar
if (!$matched) {
    http_response_code(404);
    $pageTitle       = 'Página no encontrada — RedTec Informática';
    $pageDescription = 'La página solicitada no existe o ha sido movida.';
    $currentPage     = '404';
    $cartCount       = 0;

    $content = function() use ($uri) {
        ?>
        <section class="section-padding text-center">
          <div class="container" style="max-width: 600px;">
            <div style="font-size: 5rem; font-family: var(--font-heading); font-weight: 800; color: var(--color-primary); line-height: 1;">404</div>
            <h1 style="margin-top: 1rem; margin-bottom: 0.5rem;">Página no encontrada</h1>
            <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
              Lo sentimos, la ruta <code><?= htmlspecialchars($uri) ?></code> no existe o ha sido removida.
            </p>
            <div style="display: flex; justify-content: center; gap: 1rem;">
              <a href="<?= url('/') ?>" class="btn btn-primary">Volver al Inicio</a>
              <a href="<?= url('/tienda') ?>" class="btn btn-outline">Ir a la Tienda</a>
            </div>
          </div>
        </section>
        <?php
    };

    require __DIR__ . '/../shared/Layout/layout.php';
}
