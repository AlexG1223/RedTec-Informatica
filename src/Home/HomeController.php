<?php

namespace RedTec\Home;

use RedTec\Shared\Database;
use Throwable;

/**
 * Controlador del Módulo Inicio (Home)
 */
class HomeController
{
    /**
     * Muestra la página principal de Inicio.
     */
    public function index(): void
    {
        $categories = [];

        try {
            $pdo = Database::connect();
            $stmt = $pdo->query("SELECT id, name, image_url FROM categories WHERE active = 1 ORDER BY id ASC");
            $categories = $stmt->fetchAll();
        } catch (Throwable $e) {
            // Si la base de datos no está creada localmente todavía, usamos fallback con las categorías oficiales
            $categories = [
                ['id' => 1, 'name' => 'Equipos y Notebooks', 'image_url' => '/assets/img/categories/notebooks.jpg'],
                ['id' => 2, 'name' => 'Redes y Conectividad', 'image_url' => '/assets/img/categories/redes.jpg'],
                ['id' => 3, 'name' => 'Seguridad y Cámaras', 'image_url' => '/assets/img/categories/camaras.jpg'],
                ['id' => 4, 'name' => 'Accesorios', 'image_url' => '/assets/img/categories/accesorios.jpg'],
            ];
        }

        // Cargar vista de Home pasando las categorías obtenidas
        require __DIR__ . '/views/home.php';
    }
}
