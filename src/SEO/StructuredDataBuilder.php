<?php

namespace RedTec\SEO;

/**
 * Generador de Datos Estructurados Schema.org (JSON-LD) para SEO y GEO
 */
class StructuredDataBuilder
{
    /**
     * Genera el bloque JSON-LD para la entidad LocalBusiness / Store (Ficha del Negocio).
     * 
     * @return array
     */
    public static function buildLocalBusiness(): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'LocalBusiness',
            '@id'         => absolute_url('/#organization'),
            'name'        => 'RedTec Informática',
            'alternateName' => 'RedTec',
            'url'         => absolute_url('/'),
            'logo'        => absolute_url('/assets/img/Logotipo PNG.png'),
            'image'       => absolute_url('/assets/img/Logotipo PNG.png'),
            'description' => 'Venta de productos informáticos, cámaras de seguridad CCTV, servidores, redes y soporte técnico corporativo en Atlántida, Canelones y todo Uruguay.',
            'telephone'   => '+' . REDTEC_WHATSAPP_NUMBER,
            'priceRange'  => '$$',
            'address'     => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Atlántida',
                'addressRegion'   => 'Canelones',
                'addressCountry'  => 'UY'
            ],
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '-34.7725', // Coordenadas de referencia de Atlántida, Canelones
                'longitude' => '-55.7583'
            ],
            'openingHoursSpecification' => [
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens'     => '09:00',
                    'closes'    => '19:00'
                ],
                [
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Saturday'],
                    'opens'     => '09:00',
                    'closes'    => '13:00'
                ]
            ],
            'sameAs' => [
                'https://wa.me/' . REDTEC_WHATSAPP_NUMBER
            ]
        ];
    }

    /**
     * Genera el bloque JSON-LD de tipo Product para la ficha individual de un producto.
     * 
     * @param array $product Datos del producto desde ProductoRepository
     * @return array
     */
    public static function buildProduct(array $product): array
    {
        $rawImg    = !empty($product['images'][0]['image_url']) ? $product['images'][0]['image_url'] : '/assets/img/Logotipo PNG.png';
        $imgUrl    = (strpos($rawImg, 'http') === 0) ? $rawImg : absolute_url($rawImg);
        $inStock   = ((int)($product['stock'] ?? 0)) > 0;
        $prodPrice = number_format((float)($product['price'] ?? 0), 2, '.', '');
        $prodUrl   = absolute_url('/producto/' . $product['id']);

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $product['name'],
            'image'       => [$imgUrl],
            'description' => !empty($product['description']) ? strip_tags($product['description']) : $product['name'],
            'sku'         => $product['code'] ?? ('PROD-' . $product['id']),
            'mpn'         => $product['code'] ?? ('PROD-' . $product['id']),
            'brand'       => [
                '@type' => 'Brand',
                'name'  => $product['category_name'] ?? 'RedTec Informática'
            ],
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => $prodUrl,
                'priceCurrency' => 'USD',
                'price'         => $prodPrice,
                'availability'  => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller'        => [
                    '@type' => 'Organization',
                    'name'  => 'RedTec Informática'
                ]
            ]
        ];
    }

    /**
     * Genera el bloque JSON-LD para la lista de migas de pan (BreadcrumbList).
     * 
     * @param array $items Array de elementos ['name' => '...', 'url' => '...']
     * @return array
     */
    public static function buildBreadcrumbList(array $items): array
    {
        $list = [];
        foreach ($items as $idx => $item) {
            $list[] = [
                '@type'    => 'ListItem',
                'position' => $idx + 1,
                'name'     => $item['name'],
                'item'     => absolute_url($item['url'])
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $list
        ];
    }

    /**
     * Genera el bloque JSON-LD para la sección de Preguntas Frecuentes (FAQPage).
     * 
     * @param array $faqs Array de elementos ['question' => '...', 'answer' => '...']
     * @return array
     */
    public static function buildFAQPage(array $faqs): array
    {
        $entities = [];
        foreach ($faqs as $faq) {
            $entities[] = [
                '@type'          => 'Question',
                'name'           => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $faq['answer']
                ]
            ];
        }

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities
        ];
    }

    /**
     * Convierte cualquier estructura de datos a un script JSON-LD válido para la cabecera.
     * 
     * @param array $data
     * @return string
     */
    public static function render(array $data): string
    {
        return '<script type="application/ld+json">' . "\n" . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n" . '</script>';
    }
}
