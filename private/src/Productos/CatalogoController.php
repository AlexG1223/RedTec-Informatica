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

        $pageTitle = $activeCategory 
            ? "Catálogo de {$activeCategory['name']} — RedTec Informática" 
            : "Catálogo de Productos Informáticos — RedTec Informática";
            
        $pageDescription = "Explorá nuestro catálogo de equipamiento informático, notebooks, redes, cámaras de seguridad CCTV y repuestos en Atlántida y todo Uruguay.";
        $currentPage = "tienda";

        require __DIR__ . '/views/catalogo.php';
    }
}
