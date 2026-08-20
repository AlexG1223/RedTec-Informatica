<?php

namespace RedTec\Productos;

use RedTec\Productos\ProductoRepository;
use RedTec\SEO\StructuredDataBuilder;

/**
 * Controlador para la Ficha Individual de Producto
 */
class ProductoController
{
    private ProductoRepository $productoRepository;

    public function __construct()
    {
        $this->productoRepository = new ProductoRepository();
    }

    /**
     * Muestra el detalle de un producto específico por su ID.
     *
     * @param string $id ID del producto recibido desde el router
     */
    public function show(string $id): void
    {
        $idNum   = (int)$id;
        $product = $this->productoRepository->buscarPorId($idNum);

        if (!$product || (empty($product['active']) && empty($_SESSION['admin_id']))) {
            http_response_code(404);
            $pageTitle       = 'Producto no encontrado — RedTec Informática';
            $pageDescription = 'El producto solicitado no existe o ha sido descontinuado.';
            $currentPage     = '404';
            $cartCount       = 0;

            $content = function() {
                ?>
                <section class="section-padding text-center">
                  <div class="container" style="max-width: 600px;">
                    <div style="font-size: 5rem; font-family: var(--font-heading); font-weight: 800; color: var(--color-primary); line-height: 1;">404</div>
                    <h1 style="margin-top: 1rem; margin-bottom: 0.5rem;">Producto no encontrado</h1>
                    <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
                      Lo sentimos, el producto seleccionado no existe o ha sido descontinuado.
                    </p>
                    <div style="display: flex; justify-content: center; gap: 1rem;">
                      <a href="<?= url('/tienda') ?>" class="btn btn-primary">Volver al Catálogo</a>
                    </div>
                  </div>
                </section>
                <?php
            };

            require REDTEC_SHARED_DIR . '/Layout/layout.php';
            return;
        }

        // Construcción de Breadcrumbs visibles y Schema
        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => '/'],
            ['name' => 'Tienda', 'url' => '/tienda']
        ];

        if (!empty($product['category_name']) && !empty($product['category_id'])) {
            $breadcrumbItems[] = [
                'name' => $product['category_name'],
                'url'  => '/tienda?categoria=' . $product['category_id']
            ];
        }

        $breadcrumbItems[] = [
            'name' => $product['name'],
            'url'  => '/producto/' . $product['id']
        ];

        // Construcción de Datos Estructurados Schema.org
        $jsonLdData = [
            StructuredDataBuilder::buildProduct($product),
            StructuredDataBuilder::buildBreadcrumbList($breadcrumbItems)
        ];

        // Imagen principal para OpenGraph
        $rawImg  = !empty($product['images'][0]['image_url']) ? $product['images'][0]['image_url'] : '/assets/img/Logotipo PNG.png';
        $ogImage = (strpos($rawImg, 'http') === 0) ? $rawImg : absolute_url($rawImg);

        $pageTitle       = "{$product['name']} — RedTec Informática";
        $pageDescription = !empty($product['description']) ? strip_tags(substr($product['description'], 0, 155)) : "Comprá {$product['name']} al mejor precio en RedTec Informática, Atlántida, Uruguay.";
        $currentPage     = "tienda";
        $canonicalUrl    = absolute_url('/producto/' . $product['id']);
        $ogTitle         = $pageTitle;
        $ogDescription   = $pageDescription;
        $ogType          = 'product';

        require __DIR__ . '/views/producto.php';
    }
}
