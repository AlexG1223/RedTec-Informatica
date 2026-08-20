<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\Productos\ProductoRepository;
use RedTec\Categorias\CategoriaRepository;
use RedTec\Shared\Database;
use PDO;
use Throwable;

/**
 * Controlador de Importación Masiva de Catálogo de Productos desde Archivos CSV
 */
class ImportAdminController
{
    private ProductoRepository $productoRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->productoRepository  = new ProductoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Pantalla principal de subida de archivo para importación.
     */
    public function index(): void
    {
        AdminGuard::check();

        $pageTitle  = "Importación Masiva de Catálogo (CSV)";
        $activeMenu = "importar";

        require __DIR__ . '/views/importar/index.php';
    }

    /**
     * Descarga una plantilla CSV de ejemplo pre-formateada.
     */
    public function plantilla(): void
    {
        AdminGuard::check();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=plantilla_productos_redtec.csv');

        $output = fopen('php://output', 'w');
        // BOM UTF-8 para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['code', 'name', 'description', 'category', 'price', 'stock']);
        fputcsv($output, ['NOTE-LEN-V15', 'Notebook Lenovo V15 G3 15.6" i5 8GB 256GB', 'Procesador Intel Core i5, SSD 256GB NVMe, Pantalla Full HD', 'Equipos y Notebooks', '650.00', '5']);
        fputcsv($output, ['CAM-HIK-2MP', 'Cámaras IP Hikvision 2MP Exterior Full HD', 'Visión nocturna 30m, IP67 intemperie', 'Seguridad y Cámaras', '79.00', '12']);
        fputcsv($output, ['ROUT-TP-ARCH', 'Router TP-Link Archer C6 AC1200 Dual Band', 'Wi-Fi 5 Gigabit MU-MIMO 4 antenas', 'Redes y Conectividad', '49.00', '20']);

        fclose($output);
        exit;
    }

    /**
     * Procesa el archivo CSV subido y muestra una vista previa con los cambios previstos.
     */
    public function previsualizar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        if (empty($_FILES['csv_file']['name']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Por favor seleccione un archivo CSV válido.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        $file     = $_FILES['csv_file'];
        $filePath = $file['tmp_name'];
        $fileName = htmlspecialchars($file['name']);

        // Detectar codificación y delimitador
        $content = file_get_contents($filePath);
        if (mb_detect_encoding($content, 'UTF-8', true) === false) {
            $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
            file_put_contents($filePath, $content);
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $_SESSION['flash_error'] = 'No se pudo abrir el archivo subido.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        // Detectar delimitador (coma o punto y coma)
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        // Remover BOM de la primera línea si está presente
        $bom = pack('H*', 'EFBBBF');
        if (substr($firstLine, 0, 3) === $bom) {
            fseek($handle, 3);
        }

        // Leer cabecera
        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            fclose($handle);
            $_SESSION['flash_error'] = 'El archivo CSV está vacío o mal formateado.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        // Mapear columnas por nombre (normalizado)
        $columnMap = [];
        foreach ($header as $index => $colName) {
            $cleanName = strtolower(trim(str_replace(['"', "'", "\xEF\xBB\xBF"], '', $colName)));
            
            if (in_array($cleanName, ['code', 'codigo', 'código', 'cod'], true)) $columnMap['code'] = $index;
            elseif (in_array($cleanName, ['name', 'nombre', 'producto', 'titulo', 'título'], true)) $columnMap['name'] = $index;
            elseif (in_array($cleanName, ['description', 'descripcion', 'descripción', 'detalle'], true)) $columnMap['description'] = $index;
            elseif (in_array($cleanName, ['category', 'categoria', 'categoría'], true)) $columnMap['category'] = $index;
            elseif (in_array($cleanName, ['price', 'precio', 'monto'], true)) $columnMap['price'] = $index;
            elseif (in_array($cleanName, ['stock', 'existencia', 'cantidad'], true)) $columnMap['stock'] = $index;
        }

        if (!isset($columnMap['code']) || !isset($columnMap['name'])) {
            fclose($handle);
            $_SESSION['flash_error'] = 'El archivo CSV debe contener al menos las columnas "code" (código) y "name" (nombre).';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        $rowsPreview = [];
        $summary = [
            'total'   => 0,
            'to_create' => 0,
            'to_update' => 0,
            'errors'    => 0
        ];

        $rowNum = 1;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNum++;

            // Saltar filas totalmente vacías
            if (count(array_filter($row)) === 0) continue;

            $code        = isset($columnMap['code']) ? trim($row[$columnMap['code']] ?? '') : '';
            $name        = isset($columnMap['name']) ? trim($row[$columnMap['name']] ?? '') : '';
            $description = isset($columnMap['description']) ? trim($row[$columnMap['description']] ?? '') : '';
            $catName     = isset($columnMap['category']) ? trim($row[$columnMap['category']] ?? '') : '';
            $priceStr    = isset($columnMap['price']) ? trim($row[$columnMap['price']] ?? '0') : '0';
            $stockStr    = isset($columnMap['stock']) ? trim($row[$columnMap['stock']] ?? '0') : '0';

            // Limpieza de precio y stock
            $priceClean  = preg_replace('/[^0-9\.]/', '', str_replace(',', '.', $priceStr));
            $price       = is_numeric($priceClean) ? (float)$priceClean : 0.0;
            $stock       = is_numeric($stockStr) ? (int)$stockStr : 0;

            $errors = [];
            if (empty($code)) $errors[] = "Falta el código de producto";
            if (empty($name)) $errors[] = "Falta el nombre del producto";
            if ($price < 0)   $errors[] = "Precio inválido ($priceStr)";
            if ($stock < 0)   $errors[] = "Stock inválido ($stockStr)";

            $action = 'create';
            $existingProd = null;
            $catStatus = 'existente';

            if (empty($errors)) {
                // Verificar si el producto ya existe por código
                $existingProd = $this->productoRepository->buscarPorCodigo($code);
                if ($existingProd) {
                    $action = 'update';
                }

                // Verificar si la categoría existe o se creará
                if (!empty($catName)) {
                    $existingCat = $this->categoriaRepository->buscarPorNombre($catName);
                    if (!$existingCat) {
                        $catStatus = 'se_creara';
                    }
                } else {
                    $catStatus = 'sin_categoria';
                }
            } else {
                $action = 'error';
            }

            $summary['total']++;
            if ($action === 'create') $summary['to_create']++;
            elseif ($action === 'update') $summary['to_update']++;
            else $summary['errors']++;

            $rowsPreview[] = [
                'row_num'       => $rowNum,
                'code'          => $code,
                'name'          => $name,
                'description'   => $description,
                'category_name' => $catName,
                'cat_status'    => $catStatus,
                'price'         => $price,
                'stock'         => $stock,
                'action'        => $action,
                'existing_id'   => $existingProd ? (int)$existingProd['id'] : null,
                'errors'        => $errors,
            ];
        }

        fclose($handle);

        // Guardar la previsualización en la sesión para el paso de confirmación
        $_SESSION['import_preview'] = [
            'filename' => $fileName,
            'summary'  => $summary,
            'rows'     => $rowsPreview,
        ];

        $pageTitle  = "Previsualizar Importación CSV";
        $activeMenu = "importar";

        require __DIR__ . '/views/importar/preview.php';
    }

    /**
     * Ejecuta la confirmación de la importación escribiendo los registros en la BD y guardando el log.
     */
    public function confirmar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        $previewData = $_SESSION['import_preview'] ?? null;
        if (!$previewData || empty($previewData['rows'])) {
            $_SESSION['flash_error'] = 'No hay datos de importación pendientes de confirmar.';
            header('Location: ' . url('/admin/importar'));
            exit;
        }

        $filename     = $previewData['filename'] ?? 'importacion.csv';
        $rows         = $previewData['rows'];
        $createdCount = 0;
        $updatedCount = 0;
        $failedCount  = 0;
        $processed    = 0;

        // Cache de categorías creadas durante este proceso para evitar duplicados
        $categoryCache = [];

        try {
            $pdo = Database::connect();
            $pdo->beginTransaction();

            foreach ($rows as $row) {
                if ($row['action'] === 'error') {
                    $failedCount++;
                    $processed++;
                    continue;
                }

                // 1. Resolver Categoría por nombre
                $catId = 1; // ID por defecto (General/Sin Categoría)
                $catName = trim($row['category_name']);

                if (!empty($catName)) {
                    if (isset($categoryCache[strtolower($catName)])) {
                        $catId = $categoryCache[strtolower($catName)];
                    } else {
                        $cat = $this->categoriaRepository->buscarPorNombre($catName);
                        if ($cat) {
                            $catId = (int)$cat['id'];
                        } else {
                            // Crear nueva categoría automáticamente
                            $catId = $this->categoriaRepository->crear([
                                'name'        => $catName,
                                'description' => 'Categoría creada automáticamente durante importación masiva.',
                                'image_url'   => null
                            ]);
                        }
                        $categoryCache[strtolower($catName)] = $catId;
                    }
                }

                // 2. Crear o Actualizar Producto
                if ($row['action'] === 'create') {
                    $this->productoRepository->crear([
                        'code'        => $row['code'],
                        'name'        => $row['name'],
                        'description' => $row['description'],
                        'category_id' => $catId,
                        'price'       => $row['price'],
                        'stock'       => $row['stock'],
                    ]);
                    $createdCount++;
                } elseif ($row['action'] === 'update' && !empty($row['existing_id'])) {
                    $this->productoRepository->actualizar((int)$row['existing_id'], [
                        'code'        => $row['code'],
                        'name'        => $row['name'],
                        'description' => $row['description'],
                        'category_id' => $catId,
                        'price'       => $row['price'],
                        'stock'       => $row['stock'],
                    ]);
                    $updatedCount++;
                }

                $processed++;
            }

            // 3. Registrar Log de Importación en import_logs
            $adminId = $_SESSION['admin_id'] ?? 1;
            $sqlLog  = "INSERT INTO import_logs (filename, total_processed, total_created, total_updated, total_failed, imported_by, imported_at) 
                        VALUES (:filename, :processed, :created, :updated, :failed, :admin_id, NOW())";
            $stmtLog = $pdo->prepare($sqlLog);
            $stmtLog->execute([
                ':filename'  => $filename,
                ':processed' => $processed,
                ':created'   => $createdCount,
                ':updated'   => $updatedCount,
                ':failed'    => $failedCount,
                ':admin_id'  => $adminId,
            ]);

            $pdo->commit();

            unset($_SESSION['import_preview']);
            $_SESSION['flash_success'] = "Importación masiva completada: {$createdCount} creados, {$updatedCount} actualizados, {$failedCount} fallidos.";
            header('Location: ' . url('/admin/importar/historial'));
            exit;

        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['flash_error'] = 'Error al ejecutar la importación en la base de datos: ' . $e->getMessage();
            header('Location: ' . url('/admin/importar'));
            exit;
        }
    }

    /**
     * Muestra el historial de importaciones masivas registradas en import_logs.
     */
    public function historial(): void
    {
        AdminGuard::check();

        $logs = [];
        try {
            $pdo = Database::connect();
            $sql = "SELECT l.*, a.name as admin_name 
                    FROM import_logs l 
                    LEFT JOIN admins a ON l.imported_by = a.id 
                    ORDER BY l.id DESC";
            $stmt = $pdo->query($sql);
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $logs = [];
        }

        $pageTitle  = "Historial de Importaciones";
        $activeMenu = "importar";

        require __DIR__ . '/views/importar/historial.php';
    }
}
