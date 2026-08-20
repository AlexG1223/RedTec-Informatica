<?php

namespace RedTec\ServiciosCorporativos;

use RedTec\ServiciosCorporativos\ServicioPackageRepository;

/**
 * Controlador del Módulo de Servicios Corporativos / Planes de Soporte
 */
class ServicioPackageController
{
    private ServicioPackageRepository $packageRepository;

    public function __construct()
    {
        $this->packageRepository = new ServicioPackageRepository();
    }

    /**
     * Muestra la vista de Planes Corporativos de Soporte Mensual.
     */
    public function index(): void
    {
        $packages = $this->packageRepository->listarActivos();

        // Fallback en caso de que la BD esté en proceso de configuración
        if (empty($packages)) {
            $packages = [
                [
                    'id' => 1,
                    'name' => 'Esencial',
                    'description' => 'Soporte técnico reactivo remoto y presencial con tiempo de respuesta estándar para pequeñas oficinas y negocios (hasta 5 equipos).',
                    'price' => null,
                    'active' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'Empresarial',
                    'description' => 'Soporte prioritario, mantenimiento preventivo mensual, monitoreo de infraestructura y asistencia in-situ para PyMEs (hasta 15 equipos).',
                    'price' => null,
                    'active' => 1
                ],
                [
                    'id' => 3,
                    'name' => 'Premium',
                    'description' => 'Soporte prioritario 24/7, servidor y red monitoreados en tiempo real, tiempo de respuesta SLA garantizado y técnico dedicado.',
                    'price' => null,
                    'active' => 1
                ]
            ];
        }

        $pageTitle       = "Planes de Soporte Técnico para Empresas — RedTec Informática";
        $pageDescription = "Planes de mantenimiento informático y soporte técnico mensual para PyMEs y empresas en Atlántida y Uruguay. Asistencia remota e in-situ.";
        $currentPage     = 'corporativos';

        require __DIR__ . '/views/planes.php';
    }
}
