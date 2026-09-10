<?php

namespace RedTec\SEO;

use RedTec\SEO\StructuredDataBuilder;

/**
 * Controlador de Guías SEO de Ayuda y Tendencias Emergentes
 */
class GuiaController
{
    /**
     * Muestra el índice general de guías de ayuda.
     */
    public function index(): void
    {
        $guias = $this->obtenerGuias();

        $pageTitle       = "Guías de Computación y Asesoramiento Técnico — RedTec";
        $pageDescription = "Guías útiles sobre qué hacer si tu computadora no enciende, soluciones para notebook lenta, cómo armar PC a medida y cartuchos de impresora en Atlántida.";
        $currentPage     = "servicios";
        $canonicalUrl    = absolute_url('/guias');

        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => '/'],
            ['name' => 'Guías de Ayuda', 'url' => '/guias']
        ];

        $jsonLdData = [
            StructuredDataBuilder::buildBreadcrumbList($breadcrumbItems)
        ];

        require __DIR__ . '/views/guias_index.php';
    }

    /**
     * Muestra una guía individual por su slug.
     *
     * @param string $slug
     */
    public function show(string $slug): void
    {
        $guias = $this->obtenerGuias();

        if (!isset($guias[$slug])) {
            http_response_code(404);
            $pageTitle       = 'Guía no encontrada — RedTec Informática';
            $pageDescription = 'La guía solicitada no existe o ha sido movida.';
            $currentPage     = '404';
            $cartCount       = 0;

            $content = function() {
                ?>
                <section class="section-padding text-center">
                  <div class="container" style="max-width: 600px;">
                    <div style="font-size: 5rem; font-family: var(--font-heading); font-weight: 800; color: var(--color-primary); line-height: 1;">404</div>
                    <h1 style="margin-top: 1rem; margin-bottom: 0.5rem;">Guía no encontrada</h1>
                    <p style="color: var(--color-text-secondary); margin-bottom: 2rem;">
                      Lo sentimos, la guía solicitada no existe o ha sido descontinuada.
                    </p>
                    <div style="display: flex; justify-content: center; gap: 1rem;">
                      <a href="<?= url('/guias') ?>" class="btn btn-primary">Ver Guías de Ayuda</a>
                      <a href="<?= url('/tienda') ?>" class="btn btn-outline">Ir a la Tienda</a>
                    </div>
                  </div>
                </section>
                <?php
            };

            require REDTEC_SHARED_DIR . '/Layout/layout.php';
            return;
        }

        $guia            = $guias[$slug];
        $pageTitle       = $guia['title'];
        $pageDescription = $guia['description'];
        $currentPage     = "servicios";
        $canonicalUrl    = absolute_url('/guias/' . $slug);

        $breadcrumbItems = [
            ['name' => 'Inicio', 'url' => '/'],
            ['name' => 'Guías', 'url' => '/guias'],
            ['name' => $guia['h1'], 'url' => '/guias/' . $slug]
        ];

        $jsonLdData = [
            StructuredDataBuilder::buildArticle($pageTitle, $pageDescription, '/guias/' . $slug),
            StructuredDataBuilder::buildBreadcrumbList($breadcrumbItems),
            StructuredDataBuilder::buildFAQPage($guia['faqs'] ?? [])
        ];

        require __DIR__ . '/views/guias.php';
    }

    /**
     * Catálogo de guías con contenido SEO estructurado y palabras clave empíricas.
     *
     * @return array
     */
    private function obtenerGuias(): array
    {
        return [
            'mi-computadora-no-enciende' => [
                'slug'        => 'mi-computadora-no-enciende',
                'h1'          => 'Mi computadora no enciende: Causas comunes y solución en Atlántida',
                'title'       => 'Mi Computadora No Enciende: Solución y Service — RedTec',
                'description' => '¿Tu PC o notebook no enciende? Diagnóstico técnico, cambio de fuentes de PC y reparación de computadoras en Atlántida, Canelones.',
                'summary'     => 'Si tu computadora no enciende, no emite imagen o se apaga sola, te explicamos los motivos más habituales y cómo lo solucionamos en nuestro taller de Atlántida.',
                'updated_at'  => '2026-09-10',
                'faqs'        => [
                    [
                        'question' => '¿Por qué mi PC no da video ni enciende luces?',
                        'answer'   => 'Suele estar relacionado a una falla en la fuente de alimentación (fuentes de PC), problemas de energía eléctrica o motherboards dañadas.'
                    ],
                    [
                        'question' => '¿Tienen service de computadoras presencial en Atlántida?',
                        'answer'   => 'Sí, podés traer tu equipo a nuestro taller en Atlántida, Canelones para diagnóstico inmediato y reparación de PC.'
                    ]
                ],
                'sections'    => [
                    [
                        'h2' => '1. Falla en la fuente de alimentación o cargador',
                        'p'  => 'Uno de los motivos principales de "mi computadora no enciende" es el deterioro de las fuentes de PC o adaptadores de corriente. Los picos de tensión en la zona de Canelones y Costa de Oro suelen dañar los circuitos internos. En RedTec contamos con reemplazos directos de fuentes de PC certificadas y cargadores multimarca.'
                    ],
                    [
                        'h2' => '2. Problemas con memorias RAM o acumulación de polvo',
                        'p'  => 'Si el equipo enciende los ventiladores pero no muestra imagen en el monitor, las memorias RAM pueden tener sulfatación en sus pines de contacto o estar descalzadas. Realizamos mantenimiento preventivo, limpieza ultrasónica y ampliación con memorias RAM DDR4 y DDR5 de alta velocidad.'
                    ],
                    [
                        'h2' => '3. Diagnóstico profesional y reparación de PC en Atlántida',
                        'p'  => 'No fuerces el encendido repetidamente si percibís olor a quemado o ruidos extraños. Nuestro service de computadoras en Atlántida realiza pruebas de banco con herramientas profesionales para reparar motherboards, placas de video y cambiar componentes defectuosos.'
                    ]
                ],
                'cta_text'  => '¿Necesitás reparar tu PC?',
                'cta_link'  => '/servicios',
                'cta_label' => 'Consultar por Reparación de PC'
            ],
            'notebook-lenta-que-hacer' => [
                'slug'        => 'notebook-lenta-que-hacer',
                'h1'          => 'Notebook lenta: Qué hacer para acelerar tu equipo al máximo',
                'title'       => 'Notebook Lenta: Qué Hacer y Cómo Mejorarla — RedTec',
                'description' => '¿Qué hacer si tu notebook está lenta? Ampliá memorias RAM y discos SSD con servicio técnico en Atlántida y Canelones.',
                'summary'     => 'Descubrí las causas de lentitud en notebooks Lenovo, HP, ASUS y Dell, y cómo duplicar o triplicar su velocidad de trabajo con cambios de componentes.',
                'updated_at'  => '2026-09-10',
                'faqs'        => [
                    [
                        'question' => '¿Conviene cambiar el disco duro por un SSD?',
                        'answer'   => 'Absolutamente. Instalar un disco SSD NVMe o SATA III acelera hasta 10 veces el arranque de Windows y la apertura de programas.'
                    ],
                    [
                        'question' => '¿Cuánta memoria RAM se necesita hoy en día?',
                        'answer'   => 'Para un desempeño fluido en navegación y oficina recomendamos al menos 8GB o 16GB de memorias RAM.'
                    ]
                ],
                'sections'    => [
                    [
                        'h2' => '1. Cambio de Disco Duro HDD por Discos SSD',
                        'p'  => 'Si te preguntás "notebook lenta qué hacer", la respuesta número uno es reemplazar el disco rígido mecánico por discos SSD de alta velocidad. Es la mejora con mayor impacto en el rendimiento diario de notebooks HP, Lenovo y de todas las marcas.'
                    ],
                    [
                        'h2' => '2. Ampliación de Memorias RAM',
                        'p'  => 'Al abrir varias pestañas del navegador o programas de trabajo, la memoria colapsa y el sistema recurre al almacenamiento virtual. Instalar módulos adicionales de memorias RAM elimina los tirones y congelamientos.'
                    ],
                    [
                        'h2' => '3. Mantenimiento térmico y limpieza de software',
                        'p'  => 'El sobrecalentamiento obliga al procesador a bajar sus frecuencias (thermal throttling). Nuestro service de computadoras realiza cambio de pasta térmica, limpieza de disipadores y optimización del sistema operativo en Atlántida.'
                    ]
                ],
                'cta_text'  => 'Acelerá tu notebook hoy',
                'cta_link'  => '/tienda',
                'cta_label' => 'Ver Discos SSD y Memorias RAM'
            ],
            'armar-pc-a-medida' => [
                'slug'        => 'armar-pc-a-medida',
                'h1'          => 'Armar PC a medida para gaming y trabajo en Uruguay',
                'title'       => 'Armar PC a Medida en Uruguay: Componentes y Repuestos — RedTec',
                'description' => 'Armado de PC a medida para gaming y oficina. Memorias RAM, placas de video, discos SSD y fuentes con asesoramiento técnico en Atlántida.',
                'summary'     => 'Guía completa sobre cómo seleccionar los componentes correctos para armar una computadora personalizada según tu presupuesto y uso.',
                'updated_at'  => '2026-09-10',
                'faqs'        => [
                    [
                        'question' => '¿Arman la PC ensamblada y probada en el local?',
                        'answer'   => 'Sí, armamos el equipo a medida con gestión de cables profesional, pruebas de estrés y actualización de BIOS.'
                    ]
                ],
                'sections'    => [
                    [
                        'h2' => '1. Elección de Procesador, Motherboard y Memorias RAM',
                        'p'  => 'Para armar PC a medida equilibramos la potencia del procesador con memorias RAM en Dual Channel para exprimir los FPS en juegos y la rapidez en renderizado o tareas de oficina.'
                    ],
                    [
                        'h2' => '2. Placas de video y Monitores Gaming',
                        'p'  => 'Para entusiastas y diseñadores, combinamos placas de video de última generación con monitores gaming de alta tasa de refresco, asegurando fluidez total visual.'
                    ],
                    [
                        'h2' => '3. Fuentes de PC de calidad y almacenamiento SSD',
                        'p'  => 'No hay que escatimar en las fuentes de PC: una fuente certificada protege todos tus componentes de variaciones eléctricas. Complementamos con discos SSD NVMe ultra rápidos.'
                    ]
                ],
                'cta_text'  => 'Presupuestá tu PC ideal',
                'cta_link'  => '/contacto',
                'cta_label' => 'Cotizar Armado de PC'
            ],
            'cartuchos-de-impresora' => [
                'slug'        => 'cartuchos-de-impresora',
                'h1'          => 'Cartuchos de impresora e insumos informáticos en Atlántida',
                'title'       => 'Cartuchos de Impresora e Insumos en Atlántida — RedTec',
                'description' => 'Cartuchos de impresora multifunción e insumos informáticos con envío a Atlántida, Canelones y todo Uruguay. Consultá stock por WhatsApp.',
                'summary'     => 'Venta de cartuchos de impresora originales y alternativos de alta calidad para impresoras multifunción HP, Canon, Epson y Brother.',
                'updated_at'  => '2026-09-10',
                'faqs'        => [
                    [
                        'question' => '¿Tienen cartuchos de impresora para entrega en Canelones?',
                        'answer'   => 'Sí, disponemos de amplio stock de cartuchos de impresora para impresoras multifunción con retiro en local de Atlántida o envío rápido.'
                    ]
                ],
                'sections'    => [
                    [
                        'h2' => '1. Cartuchos de Impresora para Impresoras Multifunción',
                        'p'  => 'Dada la creciente demanda en cartuchos de impresora (+900% interanual en búsquedas), mantenemos un catálogo actualizado con tintas y tóners de alto rendimiento para hogares, comercios y oficinas.'
                    ],
                    [
                        'h2' => '2. Insumos Informáticos y Cables & Adaptadores',
                        'p'  => 'Además de impresoras multifunción y cartuchos, disponemos de hojas, resmas, cables y adaptadores, periféricos y accesorios de computación completos.'
                    ],
                    [
                        'h2' => '3. Envíos y Asesoramiento en Atlántida',
                        'p'  => 'Comprá tus insumos de computación con envío directo en Atlántida, Las Toscas, La Floresta y toda la zona costera de Canelones.'
                    ]
                ],
                'cta_text'  => 'Consultá por tu modelo de impresora',
                'cta_link'  => '/tienda',
                'cta_label' => 'Ver Insumos en la Tienda'
            ]
        ];
    }
}
