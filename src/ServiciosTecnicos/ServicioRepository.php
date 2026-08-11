<?php

namespace RedTec\ServiciosTecnicos;

use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Repositorio para la consulta de Servicios Técnicos Institucionales
 */
class ServicioRepository
{
    /**
     * Devuelve la lista de servicios técnicos activos.
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
}
