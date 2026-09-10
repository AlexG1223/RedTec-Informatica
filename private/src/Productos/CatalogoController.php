<?php

namespace RedTec\Productos;

use RedTec\Productos\ProductoRepository;
use RedTec\Categorias\CategoriaRepository;
use RedTec\SEO\StructuredDataBuilder;

/**
 * Controlador de la Tienda / Catálogo Público de Productos
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
     * Muestra el catálogo de productos con filtros por categoría y búsqueda.
     */
    public function index(): void
    {
        $categoriaId = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
        $buscar      = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

        // Obtenemos los productos de la categoría seleccionada (o todos si categoriaId es 0)
        // para habilitar el filtrado instantáneo en tiempo real (0ms) en el navegador
        $products           = $this->productoRepository->listar(['categoria' => $categoriaId]);
        $categories         = $this->categoriaRepository->listarActivas();
        $featuredCategories = $this->categoriaRepository->listarDestacadas();

        $activeCategory = null;
        if ($categoriaId > 0) {
            $activeCategory = $this->categoriaRepository->buscarPorId($categoriaId);
        }

        // Construcción de Breadcrumbs
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => '/'],
            ['name' => 'Tienda', 'url' => '/tienda']
        ];

        if ($activeCategory) {
            $breadcrumbItems[] = [
                'name' => $activeCategory['name'],
                'url'  => '/tienda?categoria=' . $activeCategory['id']
            ];
        }

        $jsonLdData = [
            StructuredDataBuilder::buildBreadcrumbList($breadcrumbItems)
        ];

        // Canonicalización: siempre apunta a /tienda sin parámetros para evitar contenido duplicado
        $canonicalUrl = absolute_url('/tienda');

        if ($activeCategory) {
            $catNameLower = mb_strtolower($activeCategory['name'], 'UTF-8');
            if (strpos($catNameLower, 'ram') !== false || strpos($catNameLower, 'insumo') !== false || strpos($catNameLower, 'repuesto') !== false) {
                $pageTitle       = "Memorias RAM y Repuestos de PC en Uruguay — RedTec";
                $pageDescription = "Comprá memorias RAM DDR4 y DDR5, fuentes de PC y repuestos en Atlántida, Canelones. Envíos a todo Uruguay.";
            } elseif (strpos($catNameLower, 'notebook') !== false || strpos($catNameLower, 'equipo') !== false) {
                $pageTitle       = "Notebooks HP y Lenovo en Atlántida — RedTec Informática";
                $pageDescription = "Notebooks HP y Lenovo de alta performance, fuentes de PC y equipamiento con garantía oficial en Atlántida, Canelones.";
            } elseif (strpos($catNameLower, 'cartucho') !== false || strpos($catNameLower, 'impresora') !== false) {
                $pageTitle       = "Cartuchos de Impresora e Insumos en Atlántida — RedTec";
                $pageDescription = "Venta de cartuchos de impresora multifunción e insumos informáticos con envío rápido a Atlántida, Canelones y todo Uruguay.";
            } else {
                $pageTitle       = "{$activeCategory['name']} en Atlántida, Canelones — RedTec";
                $pageDescription = "Explorá nuestro catálogo de {$activeCategory['name']}, accesorios de computación y periféricos en RedTec Informática, Atlántida.";
            }
        } else {
            $pageTitle       = "Memorias RAM, Discos SSD y Notebooks — Tienda RedTec";
            $pageDescription = "Venta online de memorias RAM, discos SSD, notebooks HP y Lenovo, cartuchos de impresora y accesorios de computación en Atlántida, Canelones y Uruguay.";
        }
        $currentPage = "tienda";

        require __DIR__ . '/views/catalogo.php';
    }
}
