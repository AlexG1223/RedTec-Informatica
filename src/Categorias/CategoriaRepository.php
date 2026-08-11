<?php

namespace RedTec\Categorias;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Categorías
 */
class CategoriaRepository
{
    /**
     * Devuelve todas las categorías activas.
     *
     * @return array
     */
    public function listarActivas(): array
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->query("SELECT id, name, image_url, active FROM categories WHERE active = 1 ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca una categoría activa por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT id, name, image_url, active FROM categories WHERE id = :id AND active = 1 LIMIT 1");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);

            return $cat ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}
