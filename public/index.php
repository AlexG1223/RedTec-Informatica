<?php

/**
 * RedTec Informática - Punto de Entrada Principal (Front Controller & Router Mínimo)
 */

// Autoloader Mínimo PSR-4 para clases en /src y /shared
spl_autoload_register(function ($class) {
    $prefixes = [
        'RedTec\\Shared\\' => __DIR__ . '/../shared/',
        'RedTec\\Home\\'   => __DIR__ . '/../src/Home/',
        'RedTec\\'         => __DIR__ . '/../src/',
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

// Tabla de Rutas de la Aplicación
$routes = [
    '/'          => [\RedTec\Home\HomeController::class, 'index'],
    '/index.php' => [\RedTec\Home\HomeController::class, 'index'],
];

// Resolución de Ruta
if (isset($routes[$uri])) {
    $target = $routes[$uri];
    $controllerName = $target[0];
    $methodName     = $target[1];

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    // Página Error 404
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
              Lo sentimos, la ruta <code><?= htmlspecialchars($uri) ?></code> no existe o aún se encuentra en construcción.
            </p>
            <div style="display: flex; justify-content: center; gap: 1rem;">
              <a href="/index.php" class="btn btn-primary">Volver al Inicio</a>
            </div>
          </div>
        </section>
        <?php
    };

    require __DIR__ . '/../shared/Layout/layout.php';
}
