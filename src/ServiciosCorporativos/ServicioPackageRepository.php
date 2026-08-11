<?php

namespace RedTec\ServiciosCorporativos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la gestión de Planes de Soporte Corporativo
 */
class ServicioPackageRepository
{
    /**
     * Devuelve la lista de paquetes/planes corporativos activos para el sitio público.
     *
     * @return array
     */
    public function listarActivos(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT id, name, description, price, active 
                    FROM service_packages 
                    WHERE active = 1 
                    ORDER BY id ASC";

            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Devuelve todos los paquetes/planes para el panel de administración (incluyendo inactivos).
     *
     * @return array
     */
    public function listarTodos(): array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM service_packages ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Busca un paquete/plan por su ID.
     *
     * @param int $id
     * @return array|null
     */
    public function buscarPorId(int $id): ?array
    {
        try {
            $pdo = Database::connect();
            $sql = "SELECT * FROM service_packages WHERE id = :id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $pkg = $stmt->fetch(PDO::FETCH_ASSOC);
            return $pkg ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Inserta un nuevo paquete/plan corporativo.
     *
     * @param array $data
     * @return int
     */
    public function crear(array $data): int
    {
        $pdo = Database::connect();
        $sql = "INSERT INTO service_packages (name, description, price, active) 
                VALUES (:name, :description, :price, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':price'       => $data['price'] !== null ? (float)$data['price'] : null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Actualiza un paquete/plan corporativo existente.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function actualizar(int $id, array $data): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE service_packages 
                SET name = :name, 
                    description = :description, 
                    price = :price 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':price'       => $data['price'] !== null ? (float)$data['price'] : null,
        ]);
    }

    /**
     * Alterna el estado activo (1) / inactivo (0) de un paquete/plan.
     *
     * @param int $id
     * @param int $activo
     * @return bool
     */
    public function cambiarEstado(int $id, int $activo): bool
    {
        $pdo = Database::connect();
        $sql = "UPDATE service_packages SET active = :active WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':active' => $activo ? 1 : 0
        ]);
    }
}
