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
            '@type'       => 'Store',
            '@id'         => absolute_url('/#organization'),
            'name'        => 'RedTec Informática',
            'alternateName' => 'RedTec Atlántida',
            'url'         => absolute_url('/'),
            'logo'        => absolute_url('/assets/img/Logotipo PNG.png'),
            'image'       => absolute_url('/assets/img/Logotipo PNG.png'),
            'description' => 'Venta de memorias RAM, discos SSD, notebooks, cartuchos de impresora, accesorios de computación y reparación de PC en Atlántida, Canelones y todo Uruguay.',
            'telephone'   => '+' . REDTEC_WHATSAPP_NUMBER,
            'priceRange'  => '$$',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Atlántida',
                'addressLocality' => 'Atlántida',
                'addressRegion'   => 'Canelones',
                'postalCode'      => '15200',
                'addressCountry'  => 'UY'
            ],
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '-34.774475',
                'longitude' => '-55.7614383'
            ],
            'hasMap'      => 'https://maps.google.com/maps?q=-34.774475,-55.7614383',
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
                REDTEC_WHATSAPP_LINK,
                REDTEC_INSTAGRAM_URL,
                REDTEC_THREADS_URL
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
                'priceCurrency' => 'UYU',
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
        $position = 1;

        foreach ($items as $item) {
            if (isset($item[0]) && is_array($item[0])) {
                $item = $item[0];
            }

            $name = $item['name'] ?? null;
            $url  = $item['url'] ?? null;

            if (empty($name) || empty($url)) {
                continue;
            }

            $list[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $name,
                'item'     => absolute_url($url)
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
     * Genera el bloque JSON-LD de tipo Article para páginas de guías y soporte técnico.
     * 
     * @param string $title
     * @param string $description
     * @param string $url
     * @return array
     */
    public static function buildArticle(string $title, string $description, string $url): array
    {
        return [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => $title,
            'description'      => $description,
            'url'              => absolute_url($url),
            'mainEntityOfPage' => absolute_url($url),
            'author'           => [
                '@type' => 'Organization',
                'name'  => 'RedTec Informática',
                'url'   => absolute_url('/')
            ],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'RedTec Informática',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => absolute_url('/assets/img/Logotipo PNG.png')
                ]
            ],
            'inLanguage'       => 'es-UY'
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
