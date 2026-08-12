<?php

namespace RedTec\Categorias;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Categorías de Productos
 */
class CategoriaRepository
{
    /**
     * Devuelve la lista de categorías activas para los selectores y la web pública.
     *
     * @return array
     */
    public function listarActivas(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT id, name, description, image_url, active 
                    FROM categories 
                    WHERE active = 1 
                    ORDER BY name ASC";
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Devuelve todas las categorías con el conteo de productos asociados para el panel de administración.
     *
     * @return array
     */
    public function listarTodasConConteo(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT c.*, COUNT(p.id) as total_products 
                    FROM categories c 
                    LEFT JOIN products p ON c.id = p.category_id 
                    GROUP BY c.id 
                    ORDER BY c.id ASC";
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca una categoría por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cat ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Busca una categoría por su nombre exacto (insensible a mayúsculas/minúsculas).
     *
     * @param string $name
     * @return array|null
     */
    public function buscarPorNombre(string $name): ?array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM categories WHERE LOWER(TRIM(name)) = LOWER(TRIM(:name)) LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':name' => $name]);
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cat ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Devuelve la cantidad de productos asociados a una categoría.
     *
     * @param int $id
     * @return int
     */
    public function contarProductos(int $id): int
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT COUNT(*) FROM products WHERE category_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            return 0;
        }
    }

    /**
     * Inserta una nueva categoría.
     *
     * @param array $data
     * @return int ID de la categoría creada
     */
    public function crear(array $data): int
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO categories (name, description, image_url, active) 
                VALUES (:name, :description, :image_url, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':image_url'   => $data['image_url'] ?? null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Actualiza una categoría existente.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function actualizar(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE categories 
                SET name = :name, 
                    description = :description, 
                    image_url = :image_url 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':image_url'   => $data['image_url'] ?? null,
        ]);
    }

    /**
     * Elimina una categoría de la base de datos si no tiene productos asociados.
     *
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        if ($this->contarProductos($id) > 0) {
            return false;
        }

        $pdo = Database::connect();
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
