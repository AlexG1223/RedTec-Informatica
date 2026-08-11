<?php

namespace RedTec\ServiciosCorporativos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la consulta de Planes de Soporte Corporativo
 */
class ServicioPackageRepository
{
    /**
     * Devuelve la lista de paquetes/planes corporativos activos.
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
}
