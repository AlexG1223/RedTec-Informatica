<?php

namespace RedTec\Productos;

use RedTec\Productos\ProductoRepository;

/**
 * Controlador para la Ficha Detallada de Producto
 */
class ProductoController
{
    private ProductoRepository $productoRepository;

    public function __construct()
    {
        $this->productoRepository = new ProductoRepository();
    }

    /**
     * Muestra la ficha individual de un producto.
     *
     * @param string|int $id ID del producto desde la URL
     */
    public function show($id): void
    {
        $productoId = (int)$id;

        if ($productoId <= 0) {
            $this->mostrar404();
            return;
        }

        $product = $this->productoRepository->buscarPorId($productoId);

        if (!$product) {
            $this->mostrar404();
            return;
        }

        $pageTitle       = "{$product['name']} — RedTec Informática";
        $cleanDesc       = !empty($product['description']) ? strip_tags($product['description']) : "Detalles y especificaciones de {$product['name']}";
        $pageDescription = mb_strimwidth($cleanDesc, 0, 155, "...");
        $currentPage     = 'tienda';

        require __DIR__ . '/views/producto.php';
    }

    /**
     * Renderiza la página de error 404 cuando un producto no existe o está inactivo.
     */
    private function mostrar404(): void
    {
        http_response_code(404);
        $pageTitle       = "Producto No Encontrado (404) | RedTec Informática";
        $pageDescription = "El producto solicitado no está disponible o no existe.";
        $currentPage     = "tienda";

        $content = function() {
            ?>
            <section class="section-padding text-center">
              <div class="container" style="max-width: 600px;">
                <span style="font-family: var(--font-heading); font-size: 5rem; font-weight: 800; color: var(--color-primary); display: block; line-height: 1;">404</span>
                <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Producto No Encontrado</h1>
                <p style="margin-bottom: 2rem;">
                  El producto que buscas no existe o ha sido desactivado del catálogo.
                </p>
                <div style="display: flex; justify-content: center; gap: 1rem;">
                  <a href="/tienda" class="btn btn-primary">Volver al Catálogo</a>
                  <a href="/index.php" class="btn btn-outline">Ir al Inicio</a>
                </div>
              </div>
            </section>
            <?php
        };

        require __DIR__ . '/../../shared/Layout/layout.php';
    }
}
