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
    private static bool $schemaChecked = false;

    /**
     * Asegura que la tabla services exista y tenga registros por defecto (auto-migración).
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
            $sql = "CREATE TABLE IF NOT EXISTS services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                image_url VARCHAR(500) DEFAULT NULL,
                price DECIMAL(10,2) DEFAULT NULL,
                active TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $pdo->exec($sql);

            // Verificar si la tabla está vacía y sembrar registros por defecto
            $stmt = $pdo->query("SELECT COUNT(*) FROM services");
            $count = (int)$stmt->fetchColumn();

            if ($count === 0) {
                $samples = $this->getInitialSampleServices();
                $insertStmt = $pdo->prepare("INSERT INTO services (name, description, image_url, active) VALUES (:name, :description, :image_url, 1)");
                foreach ($samples as $s) {
                    $insertStmt->execute([
                        ':name'        => $s['name'],
                        ':description' => $s['description'],
                        ':image_url'   => $s['image_url'],
                    ]);
                }
            }
        } catch (Throwable $e) {
            error_log("Error en ensureSchema de services: " . $e->getMessage());
        }
    }

    /**
     * Devuelve la lista de servicios técnicos activos para la web pública.
     *
     * @return array
     */
    public function listarActivos(): array
    {
        try {
            $pdo = Database::connect();
            $this->ensureSchema($pdo);

            $sql = "SELECT * FROM services WHERE active = 1 OR active IS NULL ORDER BY id ASC";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                return $rows;
            }

            // Fallback: Si no hay ninguno filtrado con active = 1, traer todos los existentes
            $stmtAll = $pdo->query("SELECT * FROM services ORDER BY id ASC");
            $allRows = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($allRows)) {
                return $allRows;
            }

            return $this->getInitialSampleServices();
        } catch (Throwable $e) {
            error_log("Error en ServicioRepository::listarActivos: " . $e->getMessage());
            return $this->getInitialSampleServices();
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
            $this->ensureSchema($pdo);

            $sql = "SELECT * FROM services ORDER BY id DESC";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                return $rows;
            }

            return $this->getInitialSampleServices();
        } catch (Throwable $e) {
            error_log("Error en ServicioRepository::listarTodos: " . $e->getMessage());
            return $this->getInitialSampleServices();
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
            $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

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
        $this->ensureSchema($pdo);

        $sql = "UPDATE services SET active = :active WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':active' => $activo ? 1 : 0
        ]);
    }

    /**
     * Devuelve la lista inicial de servicios muestra en caso de fallback.
     *
     * @return array
     */
    private function getInitialSampleServices(): array
    {
        return [
            [
                'id'          => 1,
                'name'        => 'Seguridad Informática y Resguardos',
                'description' => 'Implementación de firewalls de red, antivirus corporativo administrado y planes de copia de seguridad.',
                'image_url'   => '/assets/img/redtec.jpeg',
                'active'      => 1
            ],
            [
                'id'          => 2,
                'name'        => 'Mantenimiento y Soporte Técnico In-Situ',
                'description' => 'Reparación de hardware, mantenimiento preventivo de equipamiento, limpieza técnica y asistencia presencial.',
                'image_url'   => null,
                'active'      => 1
            ],
            [
                'id'          => 3,
                'name'        => 'Redes y Conectividad',
                'description' => 'Cableado estructurado Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi empresariales Mesh.',
                'image_url'   => '/assets/img/redtec.jpeg',
                'active'      => 1
            ],
            [
                'id'          => 4,
                'name'        => 'Armado y Configuración de Servidores',
                'description' => 'Implementación de servidores de archivos, Active Directory, virtualización y sistemas de respaldo automatizados en unidades NAS.',
                'image_url'   => null,
                'active'      => 1
            ],
            [
                'id'          => 5,
                'name'        => 'Instalación de Cámaras de Seguridad (CCTV)',
                'description' => 'Diseño e instalación de sistemas de videovigilancia IP y analógicas HD para empresas y residencias con monitoreo remoto.',
                'image_url'   => '/assets/img/redtec.jpeg',
                'active'      => 1
            ]
        ];
    }
}
