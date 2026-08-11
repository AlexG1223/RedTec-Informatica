<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Controlador del Dashboard Principal del Panel de Administración
 */
class DashboardController
{
    /**
     * Muestra el resumen del panel de control.
     */
    public function index(): void
    {
        AdminGuard::check();

        $stats = [
            'total_productos'  => 0,
            'activos_productos'=> 0,
            'total_servicios'  => 0,
            'activos_servicios'=> 0,
            'total_planes'     => 0,
            'activos_planes'   => 0,
            'total_categorias' => 0,
        ];

        try {
            $pdo = Database::connect();

            // Stats Productos
            $stats['total_productos']   = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
            $stats['activos_productos'] = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE active = 1")->fetchColumn();

            // Stats Servicios
            $stats['total_servicios']   = (int)$pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
            $stats['activos_servicios'] = (int)$pdo->query("SELECT COUNT(*) FROM services WHERE active = 1")->fetchColumn();

            // Stats Planes
            $stats['total_planes']     = (int)$pdo->query("SELECT COUNT(*) FROM service_packages")->fetchColumn();
            $stats['activos_planes']   = (int)$pdo->query("SELECT COUNT(*) FROM service_packages WHERE active = 1")->fetchColumn();

            // Stats Categorías
            $stats['total_categorias'] = (int)$pdo->query("SELECT COUNT(*) FROM categories WHERE active = 1")->fetchColumn();

        } catch (Throwable $e) {
            // Manejo silencioso en el dashboard
        }

        $pageTitle  = "Dashboard de Administración";
        $activeMenu = "dashboard";

        require __DIR__ . '/views/dashboard.php';
    }
}
