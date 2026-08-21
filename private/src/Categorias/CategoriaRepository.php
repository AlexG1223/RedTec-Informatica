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
    private static bool $schemaChecked = false;

    /**
     * Asegura que la tabla categories tenga todas las columnas requeridas (auto-migración).
     *
     * @param PDO $pdo
     */
    private function ensureSchema(PDO $pdo): void
    {
        if (self::$schemaChecked) {
            return;
        }
        self::$schemaChecked = true;
        try {
            $pdo->exec("ALTER TABLE categories ADD COLUMN description TEXT DEFAULT NULL");
        } catch (Throwable $e) {
            // Ignorar si la columna ya existe
        }
    }

    /**
     * Devuelve la lista de todas las categorías registradas en la base de datos para selectores y la tienda.
     * Infalible: incluye automigración de esquema y fallbacks de seguridad.
     *
     * @return array
     */
    public function listarActivas(): array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                return $rows;
            }

            // Fallback si la tabla está vacía
            return [
                ['id' => 1, 'name' => 'Equipos y Notebooks'],
                ['id' => 2, 'name' => 'Redes y Conectividad'],
                ['id' => 3, 'name' => 'Seguridad y Cámaras'],
                ['id' => 4, 'name' => 'Accesorios'],
            ];
        } catch (Throwable $e) {
            error_log("Error al listar categorias en BD: " . $e->getMessage());
            try {
                $pdo = Database::connect();
                $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return !empty($rows) ? $rows : [
                    ['id' => 1, 'name' => 'Equipos y Notebooks'],
                    ['id' => 2, 'name' => 'Redes y Conectividad'],
                    ['id' => 3, 'name' => 'Seguridad y Cámaras'],
                    ['id' => 4, 'name' => 'Accesorios'],
                ];
            } catch (Throwable $ex) {
                return [
                    ['id' => 1, 'name' => 'Equipos y Notebooks'],
                    ['id' => 2, 'name' => 'Redes y Conectividad'],
                    ['id' => 3, 'name' => 'Seguridad y Cámaras'],
                    ['id' => 4, 'name' => 'Accesorios'],
                ];
            }
        }
    }

    /**
     * Devuelve todas las categorías con el conteo de productos asociados para el panel de administración.
     * Compatible con ONLY_FULL_GROUP_BY de MySQL 5.7 y 8.0+.
     *
     * @return array
     */
    public function listarTodasConConteo(): array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);
            $sql = "SELECT c.*, 
                           (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as total_products 
                    FROM categories c 
                    ORDER BY c.id ASC";
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            error_log("Error en listarTodasConConteo: " . $e->getMessage());
            return $this->listarActivas();
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
            $this->ensureSchema($pdo);
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
            $this->ensureSchema($pdo);
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
     * Inserta una nueva categoría en la base de datos.
     *
     * @param array $data
     * @return int ID de la categoría creada
     */
    public function crear(array $data): int
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);

        try {
            $sql = "INSERT INTO categories (name, description, image_url, active) 
                    VALUES (:name, :description, :image_url, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'        => $data['name'],
                ':description' => $data['description'] ?? null,
                ':image_url'   => $data['image_url'] ?? null,
            ]);
            return (int)$pdo->lastInsertId();
        } catch (Throwable $e) {
            $sql = "INSERT INTO categories (name, image_url, active) 
                    VALUES (:name, :image_url, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'      => $data['name'],
                ':image_url' => $data['image_url'] ?? null,
            ]);
            return (int)$pdo->lastInsertId();
        }
    }

    /**
     * Actualiza una categoría existente en la base de datos.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function actualizar(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $this->ensureSchema($pdo);

        try {
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
        } catch (Throwable $e) {
            $sql = "UPDATE categories 
                    SET name = :name, 
                        image_url = :image_url 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':id'        => $id,
                ':name'      => $data['name'],
                ':image_url' => $data['image_url'] ?? null,
            ]);
        }
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
