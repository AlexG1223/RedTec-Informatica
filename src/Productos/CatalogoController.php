<?php

namespace RedTec\Productos;

use RedTec\Categorias\CategoriaRepository;
use RedTec\Productos\ProductoRepository;

/**
 * Controlador para la Tienda / Catálogo de Productos
 */
class CatalogoController
{
    private ProductoRepository $productoRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->productoRepository  = new ProductoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Muestra el catálogo de productos con soporte para búsqueda y filtrado por categoría.
     */
    public function index(): void
    {
        $categoriaId = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
        $buscar      = isset($_GET['buscar']) ? trim((string)$_GET['buscar']) : '';

        // Obtener categorías para la barra de filtros
        $categories = $this->categoriaRepository->listarActivas();

        // Obtener productos filtrados
        $products = $this->productoRepository->listar([
            'categoria_id' => $categoriaId,
            'buscar'       => $buscar,
        ]);

        // Datos de categoría activa si existe
        $activeCategory = $categoriaId > 0 ? $this->categoriaRepository->buscarPorId($categoriaId) : null;

        // Configuración de Título y Meta Descripción dinámicos para SEO
        if ($activeCategory) {
            $pageTitle       = "{$activeCategory['name']} — Tienda RedTec Informática";
            $pageDescription = "Catálogo de {$activeCategory['name']} en RedTec Informática Atlántida. Equipos de alta calidad con garantía y envío en Uruguay.";
        } elseif ($buscar !== '') {
            $pageTitle       = "Búsqueda: '{$buscar}' — RedTec Informática";
            $pageDescription = "Resultados de búsqueda para '{$buscar}' en la tienda de productos informáticos RedTec.";
        } else {
            $pageTitle       = "Catálogo de Productos — RedTec Informática";
            $pageDescription = "Tienda online de productos informáticos en Uruguay. Notebooks, redes, cámaras de seguridad y accesorios con envío a todo el país.";
        }

        $currentPage = 'tienda';

        require __DIR__ . '/views/catalogo.php';
    }
}
