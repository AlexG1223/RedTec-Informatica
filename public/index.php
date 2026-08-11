<?php

/**
 * RedTec Informática - Punto de Entrada Principal (Front Controller & Router con Detección de Entorno)
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

// Normalización de URI de la petición
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Soporte para despliegue en subdirectorios (ej: /RedTec/public)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($scriptDir !== '/' && strpos($requestUri, $scriptDir) === 0) {
    $requestUri = substr($requestUri, strlen($scriptDir));
}

$uri = '/' . trim($requestUri, '/');

// Rutas Exactas
$staticRoutes = [
    '/'                     => [\RedTec\Home\HomeController::class, 'index'],
    '/index.php'            => [\RedTec\Home\HomeController::class, 'index'],
    '/tienda'               => [\RedTec\Productos\CatalogoController::class, 'index'],
    '/checkout'             => [\RedTec\Checkout\CheckoutController::class, 'index'],
    '/servicios'            => [\RedTec\ServiciosTecnicos\ServicioController::class, 'index'],
    '/servicios-corporativos'=> [\RedTec\ServiciosCorporativos\ServicioPackageController::class, 'index'],
];



// Rutas Dinámicas (Patrón Regex => [ClaseControlador, Metodo])
$dynamicRoutes = [
    '#^/producto/([0-9]+)$#' => [\RedTec\Productos\ProductoController::class, 'show'],
];

$matched = false;

// 1. Intentar coincidencia exacta
if (isset($staticRoutes[$uri])) {
    $target = $staticRoutes[$uri];
    $controllerName = $target[0];
    $methodName     = $target[1];

    $controller = new $controllerName();
    $controller->$methodName();
    $matched = true;
} else {
    // 2. Intentar coincidencias dinámicas (Regex)
    foreach ($dynamicRoutes as $pattern => $target) {
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Quitar coincidencia completa
            $controllerName = $target[0];
            $methodName     = $target[1];

            $controller = new $controllerName();
            call_user_func_array([$controller, $methodName], $matches);
            $matched = true;
            break;
        }
    }
}

// 3. Si ninguna ruta coincide -> Mostrar Página 404
if (!$matched) {
    http_response_code(404);
    $pageTitle       = "Página No Encontrada (404) | RedTec Informática";
    $pageDescription = "La página solicitada no existe o ha sido movida.";
    $currentPage     = "";

    $content = function() use ($uri) {
        ?>
        <section class="section-padding text-center">
          <div class="container" style="max-width: 600px;">
            <span style="font-family: var(--font-heading); font-size: 5rem; font-weight: 800; color: var(--color-primary); display: block; line-height: 1;">404</span>
            <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Página No Encontrada</h1>
            <p style="margin-bottom: 2rem;">
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
