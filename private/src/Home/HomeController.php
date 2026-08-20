<?php

namespace RedTec\Home;

use RedTec\Categorias\CategoriaRepository;
use RedTec\SEO\StructuredDataBuilder;

/**
 * Controlador de la Página de Inicio (Home)
 */
class HomeController
{
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Muestra la página principal de inicio.
     */
    public function index(): void
    {
        $categories = $this->categoriaRepository->listarActivas();

        // Fallback en caso de que la BD no tenga categorías pobladas
        if (empty($categories)) {
            $categories = [
                ['id' => 1, 'name' => 'Equipos y Notebooks', 'image_url' => '/assets/img/redtec.jpeg'],
                ['id' => 2, 'name' => 'Redes y Conectividad', 'image_url' => '/assets/img/redtec.jpeg'],
                ['id' => 3, 'name' => 'Seguridad y Cámaras', 'image_url' => '/assets/img/redtec.jpeg'],
                ['id' => 4, 'name' => 'Accesorios y Periféricos', 'image_url' => '/assets/img/redtec.jpeg'],
            ];
        }

        // Preguntas Frecuentes (FAQ) para SEO y Asistentes de IA (GEO)
        $faqs = [
            [
                'question' => '¿Tienen local físico para ver y retirar los productos?',
                'answer'   => 'Sí, nuestro local principal se encuentra en Atlántida, Canelones, Uruguay, donde podés asesorarte personalmente y retirar tus compras.'
                // COMENTARIO PARA CLIENTE (MICHAEL/ALEX): Confirmar dirección exacta del local físico.
            ],
            [
                'question' => '¿Cómo se coordina la compra y el método de pago?',
                'answer'   => 'Al presionar "Finalizar Pedido" o solicitar un presupuesto desde la web, serás redirigido a nuestro WhatsApp oficial donde un técnico confirmará el stock y te indicará las opciones de pago contado o transferencia.'
                // COMENTARIO PARA CLIENTE: Redacción segura basada en el flujo actual vía WhatsApp.
            ],
            [
                'question' => '¿Realizan envíos de productos a todo Uruguay?',
                'answer'   => 'Sí, realizamos envíos de insumos informáticos y equipamiento a Atlántida, localidades de Canelones y a cualquier departamento del Uruguay.'
                // COMENTARIO PARA CLIENTE: Confirmar empresas de encomienda habilitadas (DAC, Mirtrans, etc.).
            ],
            [
                'question' => '¿Brindan servicio técnico e instalación a domicilio?',
                'answer'   => 'Sí, contamos con servicio técnico especializado para instalación de cámaras de seguridad CCTV, cableado de red, servidores y mantenimiento informático a domicilio para hogares y empresas.'
                // COMENTARIO PARA CLIENTE: Confirmar zona de cobertura para visitas in-situ.
            ]
        ];

        // Construcción de Datos Estructurados JSON-LD
        $jsonLdData = [
            StructuredDataBuilder::buildLocalBusiness(),
            StructuredDataBuilder::buildFAQPage($faqs)
        ];

        $pageTitle       = "RedTec Informática — Tienda de Tecnología y Servicios en Atlántida, Uruguay";
        $pageDescription = "Tienda online de productos informáticos, cámaras de seguridad CCTV, servidores, redes y soporte técnico corporativo en Atlántida y todo Uruguay.";
        $currentPage     = "inicio";
        $canonicalUrl    = absolute_url('/');

        require __DIR__ . '/views/home.php';
    }
}
