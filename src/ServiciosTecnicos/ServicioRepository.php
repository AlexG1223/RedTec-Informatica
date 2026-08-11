<?php

namespace RedTec\ServiciosTecnicos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Servicios Técnicos Institucionales
 */
class ServicioRepository
{
    /**
     * Devuelve la lista de servicios técnicos activos para la web pública.
     *
     * @return array
     */
    public function listarActivos(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT id, name, description, image_url, active 
                    FROM services 
                    WHERE active = 1 
                    ORDER BY id ASC";
            
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Devuelve todos los servicios técnicos para el panel de administración (incluyendo inactivos).
     *
     * @return array
     */
    public function listarTodos(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM services ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca un servicio técnico por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM services WHERE id = :id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            return $service ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Inserta un nuevo servicio técnico.
     *
     * @param array $data
     * @return int
     */
    public function crear(array $data): int
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO services (name, description, image_url, active) 
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
     * Actualiza un servicio técnico existente.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function actualizar(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE services 
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
     * Alterna el estado activo (1) / inactivo (0) de un servicio técnico.
     *
     * @param int $id
     * @param int $activo
     * @return bool
     */
    public function cambiarEstado(int $id, int $activo): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE services SET active = :active WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':active' => $activo ? 1 : 0
        ]);
    }
}
