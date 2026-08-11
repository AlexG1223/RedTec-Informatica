<?php

namespace RedTec\ServiciosTecnicos;

use RedTec\ServiciosTecnicos\ServicioRepository;

/**
 * Controlador del Módulo de Servicios Técnicos
 */
class ServicioController
{
    private ServicioRepository $servicioRepository;

    public function __construct()
    {
        $this->servicioRepository = new ServicioRepository();
    }

    /**
     * Muestra la vista institucional de Servicios Técnicos.
     */
    public function index(): void
    {
        $services = $this->servicioRepository->listarActivos();

        // Fallback en caso de que la BD no tenga datos iniciales
        if (empty($services)) {
            $services = [
                [
                    'id' => 1,
                    'name' => 'Instalación de Cámaras de Seguridad (CCTV)',
                    'description' => 'Diseño e instalación de sistemas de videovigilancia IP y analógicas HD para empresas y residencias con monitoreo remoto en el celular.',
                    'image_url' => '/assets/img/redtec.jpeg',
                    'active' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'Armado y Configuración de Servidores',
                    'description' => 'Implementación de servidores de archivos, Active Directory, virtualización y sistemas de respaldo automatizados en unidades NAS.',
                    'image_url' => null,
                    'active' => 1
                ],
                [
                    'id' => 3,
                    'name' => 'Redes y Conectividad',
                    'description' => 'Cableado estructurado Cat6, certificación de puntos de red, armado de racks y despliegue de redes Wi-Fi empresariales Mesh.',
                    'image_url' => '/assets/img/redtec.jpeg',
                    'active' => 1
                ]
            ];
        }

        $pageTitle       = "Servicios Técnicos e Infraestructura — RedTec Informática";
        $pageDescription = "Instalación de cámaras de seguridad, servidores, redes y soporte técnico informático a domicilio en Atlántida y todo Uruguay.";
        $currentPage     = 'servicios';

        require __DIR__ . '/views/servicios.php';
    }
}
