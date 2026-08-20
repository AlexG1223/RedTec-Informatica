<?php

namespace RedTec\SEO;

use RedTec\Productos\ProductoRepository;
use Throwable;

/**
 * Generador de Mapa de Sitio XML (Sitemap.xml) Dinámico
 */
class SitemapGenerator
{
    /**
     * Genera y envía el XML del sitemap con las cabeceras HTTP correspondientes.
     */
    public static function generate(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $productoRepository = new ProductoRepository();
        $products = $productoRepository->listar();

        $urls = [
            [
                'loc'        => absolute_url('/'),
                'lastmod'    => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority'   => '1.0'
            ],
            [
                'loc'        => absolute_url('/tienda'),
                'lastmod'    => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority'   => '0.9'
            ],
            [
                'loc'        => absolute_url('/servicios'),
                'lastmod'    => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority'   => '0.8'
            ],
            [
                'loc'        => absolute_url('/servicios-corporativos'),
                'lastmod'    => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority'   => '0.8'
            ],
        ];

        // Agregar fichas de productos activos
        foreach ($products as $p) {
            $lastmod = !empty($p['updated_at']) ? date('Y-m-d', strtotime($p['updated_at'])) : date('Y-m-d');
            $urls[]  = [
                'loc'        => absolute_url('/producto/' . $p['id']),
                'lastmod'    => $lastmod,
                'changefreq' => 'weekly',
                'priority'   => '0.7'
            ];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc']) . "</loc>\n";
            echo "    <lastmod>" . $u['lastmod'] . "</lastmod>\n";
            echo "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
            echo "    <priority>" . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }
}
