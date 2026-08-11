<?php

namespace RedTec\Home;

use RedTec\Categorias\CategoriaRepository;

/**
 * Controlador del Módulo Inicio (Home)
 */
class HomeController
{
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Muestra la página principal de Inicio.
     */
    public function index(): void
    {
        $categories = $this->categoriaRepository->listarActivas();

        // Fallback en caso de que la BD esté vacía o en configuración inicial
        if (empty($categories)) {
            $categories = [
                ['id' => 1, 'name' => 'Equipos y Notebooks', 'image_url' => '/assets/img/categories/notebooks.jpg'],
                ['id' => 2, 'name' => 'Redes y Conectividad', 'image_url' => '/assets/img/categories/redes.jpg'],
                ['id' => 3, 'name' => 'Seguridad y Cámaras', 'image_url' => '/assets/img/categories/camaras.jpg'],
                ['id' => 4, 'name' => 'Accesorios', 'image_url' => '/assets/img/categories/accesorios.jpg'],
            ];
        }

        require __DIR__ . '/views/home.php';
    }
}
