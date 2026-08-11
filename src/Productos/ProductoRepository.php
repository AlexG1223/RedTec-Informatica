<?php

namespace RedTec\Productos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión y consulta de Productos
 */
class ProductoRepository
{
    /**
     * Lista productos activos aplicando filtros opcionales (categoría o texto de búsqueda).
     *
     * @param array $filtros Array asociativo con 'categoria_id' y/o 'buscar'
     * @return array
     */
    public function listar(array $filtros = []): array
    {
        try {
            $pdo = Database::connect();

            $sql = "SELECT p.id, p.code, p.name, p.description, p.category_id, p.price, p.stock, p.active, p.updated_at,
                           c.name AS category_name,
                           (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.sort_order ASC, pi.id ASC LIMIT 1) AS primary_image
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.active = 1";

            $params = [];

            if (!empty($filtros['categoria_id']) && is_numeric($filtros['categoria_id'])) {
                $sql .= " AND p.category_id = :categoria_id";
                $params[':categoria_id'] = (int)$filtros['categoria_id'];
            }

            if (!empty($filtros['buscar']) && is_string($filtros['buscar'])) {
                $search = trim($filtros['buscar']);
                if ($search !== '') {
                    $sql .= " AND (p.name LIKE :buscar OR p.code LIKE :buscar)";
                    $params[':buscar'] = '%' . $search . '%';
                }
            }

            $sql .= " ORDER BY p.id DESC";

            $stmt = $pdo->prepare($sql);

            foreach ($params as $param => $value) {
                if (is_int($value)) {
                    $stmt->bindValue($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($param, $value, PDO::PARAM_STR);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca un producto activo por su ID, junto con su nombre de categoría e imágenes asociadas.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();

            $sql = "SELECT p.id, p.code, p.name, p.description, p.category_id, p.price, p.stock, p.active, p.updated_at,
                           c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = :id AND p.active = 1
                    LIMIT 1";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                return null;
            }

            // Cargar galería de imágenes
            $product['images'] = $this->listarImagenes($id);

            return $product;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Devuelve la lista de imágenes asociadas a un producto.
     *
     * @param int $productoId
     * @return array
     */
    public function listarImagenes(int $productoId): array
    {
        try {
            $pdo = Database::connect();

            $sql = "SELECT id, image_url, sort_order 
                    FROM product_images 
                    WHERE product_id = :product_id 
                    ORDER BY sort_order ASC, id ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':product_id', $productoId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }
}
