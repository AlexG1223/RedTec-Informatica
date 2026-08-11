<?php

namespace RedTec\Productos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Productos (Público y Panel de Administración)
 */
class ProductoRepository
{
    /**
     * Busca y lista productos activos para la tienda pública con filtros.
     *
     * @param array $filtros ['categoria' => int, 'buscar' => string]
     * @return array
     */
    public function listar(array $filtros = []): array
    {
        try {
            $pdo = Database::connect();
            
            $sql = "SELECT p.*, c.name as category_name,
                           (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.active = 1";
            
            $params = [];

            if (!empty($filtros['categoria'])) {
                $sql .= " AND p.category_id = :categoria";
                $params[':categoria'] = (int)$filtros['categoria'];
            }

            if (!empty($filtros['buscar'])) {
                $sql .= " AND (p.name LIKE :buscar OR p.code LIKE :buscar OR p.description LIKE :buscar)";
                $params[':buscar'] = '%' . trim($filtros['buscar']) . '%';
            }

            $sql .= " ORDER BY p.id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Devuelve la lista completa de productos para el panel de administración (incluyendo inactivos).
     *
     * @return array
     */
    public function listarTodos(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT p.*, c.name as category_name,
                           (SELECT image_url FROM product_images pi WHERE pi.product_id = p.id ORDER BY is_primary DESC, id ASC LIMIT 1) as primary_image
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    ORDER BY p.id DESC";
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca un producto específico por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT p.*, c.name as category_name 
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = :id
                    LIMIT 1";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                return null;
            }

            // Obtener galería de imágenes asociadas
            $product['images'] = $this->listarImagenes($id);

            return $product;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Lista todas las imágenes asociadas a un producto.
     *
     * @param int $productoId
     * @return array
     */
    public function listarImagenes(int $productoId): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT id, product_id, image_url, is_primary 
                    FROM product_images 
                    WHERE product_id = :product_id 
                    ORDER BY is_primary DESC, id ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':product_id' => $productoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Inserta un nuevo producto en la base de datos.
     *
     * @param array $data
     * @return int ID del producto creado
     */
    public function crear(array $data): int
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO products (code, name, description, category_id, price, stock, active, created_at, updated_at) 
                VALUES (:code, :name, :description, :category_id, :price, :stock, 1, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':code'        => $data['code'],
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':category_id' => (int)$data['category_id'],
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Actualiza la información de un producto existente.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function actualizar(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE products 
                SET code = :code, 
                    name = :name, 
                    description = :description, 
                    category_id = :category_id, 
                    price = :price, 
                    stock = :stock, 
                    updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':code'        => $data['code'],
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':category_id' => (int)$data['category_id'],
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
        ]);
    }

    /**
     * Alterna el estado activo (1) / inactivo (0) de un producto.
     *
     * @param int $id
     * @param int $activo (0 o 1)
     * @return bool
     */
    public function cambiarEstado(int $id, int $activo): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE products SET active = :active, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':active' => $activo ? 1 : 0
        ]);
    }

    /**
     * Agrega una imagen a la galería del producto.
     *
     * @param int $productoId
     * @param string $imageUrl
     * @param int $isPrimary
     * @return int ID de la imagen insertada
     */
    public function agregarImagen(int $productoId, string $imageUrl, int $isPrimary = 0): int
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO product_images (product_id, image_url, is_primary) VALUES (:product_id, :image_url, :is_primary)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':product_id' => $productoId,
            ':image_url'  => $imageUrl,
            ':is_primary' => $isPrimary
        ]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Elimina una imagen específica por su ID.
     *
     * @param int $imagenId
     * @return bool
     */
    public function eliminarImagen(int $imagenId): bool
    {
        $pdo = Database::connect();
        $sql = "DELETE FROM product_images WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $imagenId]);
    }
}
