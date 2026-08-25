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
    private static bool $schemaChecked = false;

    /**
     * Asegura que la tabla service_packages exista y tenga registros por defecto (auto-migración).
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
            $sql = "CREATE TABLE IF NOT EXISTS service_packages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                price DECIMAL(10,2) DEFAULT NULL,
                active TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $pdo->exec($sql);

            // Verificar si la tabla está vacía y sembrar registros por defecto
            $stmt = $pdo->query("SELECT COUNT(*) FROM service_packages");
            $count = (int)$stmt->fetchColumn();

            if ($count === 0) {
                $samples = $this->getInitialSamplePackages();
                $insertStmt = $pdo->prepare("INSERT INTO service_packages (name, description, price, active) VALUES (:name, :description, :price, 1)");
                foreach ($samples as $s) {
                    $insertStmt->execute([
                        ':name'        => $s['name'],
                        ':description' => $s['description'],
                        ':price'       => $s['price'],
                    ]);
                }
            }
        } catch (Throwable $e) {
            error_log("Error en ensureSchema de service_packages: " . $e->getMessage());
        }
    }

    /**
     * Devuelve la lista de paquetes/planes corporativos activos para el sitio público.
     *
     * @return array
     */
    public function listarActivos(): array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $sql = "SELECT * FROM service_packages WHERE active = 1 OR active IS NULL ORDER BY id ASC";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                return $rows;
            }

            // Fallback: Si no hay ninguno con active = 1, traer todos los existentes
            $stmtAll = $pdo->query("SELECT * FROM service_packages ORDER BY id ASC");
            $allRows = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($allRows)) {
                return $allRows;
            }

            return $this->getInitialSamplePackages();
        } catch (Throwable $e) {
            error_log("Error en ServicioPackageRepository::listarActivos: " . $e->getMessage());
            return $this->getInitialSamplePackages();
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
            $this->ensureSchema($pdo);

            $sql = "SELECT * FROM service_packages ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                return $rows;
            }

            return $this->getInitialSamplePackages();
        } catch (Throwable $e) {
            error_log("Error en ServicioPackageRepository::listarTodos: " . $e->getMessage());
            return $this->getInitialSamplePackages();
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
            $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

        $sql = "UPDATE service_packages SET active = :active WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':active' => $activo ? 1 : 0
        ]);
    }

    /**
     * Devuelve la lista inicial de planes muestra en caso de fallback.
     *
     * @return array
     */
    private function getInitialSamplePackages(): array
    {
        return [
            [
                'id'          => 1,
                'name'        => 'Esencial',
                'description' => 'Soporte técnico reactivo remoto y presencial con tiempo de respuesta estándar para pequeñas oficinas y negocios (hasta 5 equipos).',
                'price'       => null,
                'active'      => 1
            ],
            [
                'id'          => 2,
                'name'        => 'Empresarial',
                'description' => 'Soporte prioritario, mantenimiento preventivo mensual, monitoreo de infraestructura y asistencia in-situ para PyMEs (hasta 15 equipos).',
                'price'       => null,
                'active'      => 1
            ],
            [
                'id'          => 3,
                'name'        => 'Premium',
                'description' => 'Soporte prioritario 24/7, servidor y red monitoreados en tiempo real, tiempo de respuesta SLA garantizado y técnico dedicado.',
                'price'       => null,
                'active'      => 1
            ]
        ];
    }
}
