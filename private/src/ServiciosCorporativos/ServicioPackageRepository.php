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
                badge VARCHAR(100) DEFAULT 'PLAN',
                name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                banner TEXT DEFAULT NULL,
                includes_tag VARCHAR(255) DEFAULT 'INCLUYE:',
                includes TEXT DEFAULT NULL,
                price DECIMAL(10,2) DEFAULT NULL,
                active TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $pdo->exec($sql);

            // Asegurar que existan las columnas si la tabla ya había sido creada antes
            $cols = [
                'badge'        => "ALTER TABLE service_packages ADD COLUMN badge VARCHAR(100) DEFAULT 'PLAN' AFTER id",
                'banner'       => "ALTER TABLE service_packages ADD COLUMN banner TEXT DEFAULT NULL AFTER description",
                'includes_tag' => "ALTER TABLE service_packages ADD COLUMN includes_tag VARCHAR(255) DEFAULT 'INCLUYE:' AFTER banner",
                'includes'     => "ALTER TABLE service_packages ADD COLUMN includes TEXT DEFAULT NULL AFTER includes_tag",
            ];
            foreach ($cols as $colName => $alterSql) {
                try {
                    $pdo->exec($alterSql);
                } catch (Throwable $e) {}
            }

            // Verificar si la tabla está vacía y sembrar registros oficiales por defecto
            $stmt = $pdo->query("SELECT COUNT(*) FROM service_packages");
            $count = (int)$stmt->fetchColumn();

            if ($count === 0) {
                $samples = $this->getInitialSamplePackages();
                $insertStmt = $pdo->prepare("INSERT INTO service_packages (id, badge, name, description, banner, includes_tag, includes, price, active) VALUES (:id, :badge, :name, :description, :banner, :includes_tag, :includes, :price, 1)");
                foreach ($samples as $s) {
                    $insertStmt->execute([
                        ':id'           => $s['id'],
                        ':badge'        => $s['badge'],
                        ':name'         => $s['name'],
                        ':description'  => $s['description'],
                        ':banner'       => $s['banner'] ?? null,
                        ':includes_tag' => $s['includes_tag'] ?? 'INCLUYE:',
                        ':includes'     => $s['includes'] ?? null,
                        ':price'        => $s['price'],
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

            $sql = "SELECT * FROM service_packages ORDER BY id ASC";
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

        $sql = "INSERT INTO service_packages (badge, name, description, banner, includes_tag, includes, price, active) 
                VALUES (:badge, :name, :description, :banner, :includes_tag, :includes, :price, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':badge'        => $data['badge'] ?? 'PLAN',
            ':name'         => $data['name'],
            ':description'  => $data['description'] ?? null,
            ':banner'       => $data['banner'] ?? null,
            ':includes_tag' => $data['includes_tag'] ?? 'INCLUYE:',
            ':includes'     => $data['includes'] ?? null,
            ':price'        => $data['price'] !== null ? (float)$data['price'] : null,
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
                SET badge = :badge,
                    name = :name, 
                    description = :description, 
                    banner = :banner,
                    includes_tag = :includes_tag,
                    includes = :includes, 
                    price = :price 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':badge'        => $data['badge'] ?? 'PLAN',
            ':name'         => $data['name'],
            ':description'  => $data['description'] ?? null,
            ':banner'       => $data['banner'] ?? null,
            ':includes_tag' => $data['includes_tag'] ?? 'INCLUYE:',
            ':includes'     => $data['includes'] ?? null,
            ':price'        => $data['price'] !== null ? (float)$data['price'] : null,
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
                'id'           => 1,
                'badge'        => 'PLAN 1',
                'name'         => 'ESENCIAL',
                'description'  => 'Nos ocupamos de que tu tecnología funcione para que vos puedas dedicarte a tu empresa.',
                'banner'       => 'TRANQUILIDAD PARA TU NEGOCIO | SOPORTE TÉCNICO INMEDIATO',
                'includes_tag' => 'INCLUYE:',
                'includes'     => "Soporte remoto y asistencia por WhatsApp / Correo\nMantenimiento preventivo\nMonitoreo básico de equipos\nVisitas planificadas\nInforme mensual básico",
                'price'        => null,
                'active'       => 1
            ],
            [
                'id'           => 2,
                'badge'        => 'PLAN 2',
                'name'         => 'PROFESIONAL',
                'description'  => 'Más control, más prevención, más eficiencia para que tu empresa no se detenga.',
                'banner'       => 'MÁS PREVENCIÓN, MENOS PROBLEMAS | SOPORTE TÉCNICO MÁS RÁPIDO',
                'includes_tag' => 'INCLUYE TODO DEL PLAN 1, MÁS:',
                'includes'     => "Mayor prioridad en la atención\nVisitas técnicas (según plan)\nMonitoreo activo de sistemas y red\nSupervisión de respaldos\nActualizaciones y parches\nReportes mensuales detallados\nCapacitación básica para tu equipo",
                'price'        => null,
                'active'       => 1
            ],
            [
                'id'           => 3,
                'badge'        => 'PLAN 3',
                'name'         => 'CONTINUIDAD 360°',
                'description'  => 'La solución integral para empresas que no pueden detenerse.',
                'banner'       => 'SOPORTE INTEGRAL: REDES, SERVIDORES, WIFI, CÁMARAS, ACCESOS, RESPALDOS, ETC.',
                'includes_tag' => 'INCLUYE TODO DEL PLAN 2, MÁS:',
                'includes'     => "Atención de emergencias 24/7\nRespuesta inmediata ante incidentes críticos\nSoporte integral (redes, servidores, WiFi, cámaras, accesos, respaldos, etc.)\nPlanificación y optimización de infraestructura\nGestión proactiva y mejoras continuas\nEquipos de respaldo en caso de fallas\nReuniones periódicas de seguimiento estratégico",
                'price'        => null,
                'active'       => 1
            ]
        ];
    }
}
