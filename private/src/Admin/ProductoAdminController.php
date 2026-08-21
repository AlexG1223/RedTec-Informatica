<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\Productos\ProductoRepository;
use RedTec\Categorias\CategoriaRepository;
use Throwable;

/**
 * Controlador de Administración de Productos (CRUD, Imágenes, Exportación CSV y Stock AJAX)
 */
class ProductoAdminController
{
    private ProductoRepository $productoRepository;
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->productoRepository  = new ProductoRepository();
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Muestra el listado de todos los productos (activos e inactivos).
     */
    public function index(): void
    {
        AdminGuard::check();

        $productos = $this->productoRepository->listarTodos();

        $pageTitle  = "Gestión de Productos";
        $activeMenu = "productos";

        require __DIR__ . '/views/productos/listado.php';
    }

    /**
     * Formulario para crear un nuevo producto.
     */
    public function crearForm(): void
    {
        AdminGuard::check();

        $categorias = $this->categoriaRepository->listarActivas();
        $producto   = null;

        $pageTitle  = "Nuevo Producto";
        $activeMenu = "productos";

        require __DIR__ . '/views/productos/form.php';
    }

    /**
     * Procesa la creación de un nuevo producto.
     */
    public function guardar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/productos/nuevo'));
            exit;
        }

        $code        = trim($_POST['code'] ?? '');
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $price       = (float)($_POST['price'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);

        if (empty($code) || empty($name) || $categoryId <= 0 || $price < 0 || $stock < 0) {
            $_SESSION['flash_error'] = 'Por favor complete los campos obligatorios (*).';
            header('Location: ' . url('/admin/productos/nuevo'));
            exit;
        }

        try {
            $newId = $this->productoRepository->crear([
                'code'        => $code,
                'name'        => $name,
                'description' => $description,
                'category_id' => $categoryId,
                'price'       => $price,
                'stock'       => $stock,
            ]);

            $_SESSION['flash_success'] = "Producto '{$name}' creado exitosamente. Ahora podés agregarle imágenes.";
            header('Location: ' . url('/admin/productos/' . $newId . '/editar'));
            exit;
        } catch (Throwable $e) {
            error_log("Error al guardar producto: " . $e->getMessage());
            $_SESSION['flash_error'] = 'Error al guardar el producto en la base de datos: ' . $e->getMessage();
            header('Location: ' . url('/admin/productos/nuevo'));
            exit;
        }
    }

    /**
     * Formulario para editar un producto existente y sus imágenes.
     */
    public function editarForm(string $id): void
    {
        AdminGuard::check();

        $idNum    = (int)$id;
        $producto = $this->productoRepository->buscarPorId($idNum);

        if (!$producto) {
            $_SESSION['flash_error'] = 'Producto no encontrado.';
            header('Location: ' . url('/admin/productos'));
            exit;
        }

        $categorias = $this->categoriaRepository->listarActivas();

        $pageTitle  = "Editar Producto: " . $producto['name'];
        $activeMenu = "productos";

        require __DIR__ . '/views/productos/form.php';
    }

    /**
     * Procesa la actualización de un producto existente.
     */
    public function actualizar(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        $code        = trim($_POST['code'] ?? '');
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $price       = (float)($_POST['price'] ?? 0);
        $stock       = (int)($_POST['stock'] ?? 0);

        if (empty($code) || empty($name) || $categoryId <= 0 || $price < 0 || $stock < 0) {
            $_SESSION['flash_error'] = 'Por favor complete todos los campos requeridos.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        try {
            $this->productoRepository->actualizar($idNum, [
                'code'        => $code,
                'name'        => $name,
                'description' => $description,
                'category_id' => $categoryId,
                'price'       => $price,
                'stock'       => $stock,
            ]);

            $_SESSION['flash_success'] = "Producto '{$name}' actualizado correctamente.";
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al actualizar el producto.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }
    }

    /**
     * Alterna el estado activo/inactivo (Dar de baja / Reactivar).
     */
    public function cambiarEstado(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/productos'));
            exit;
        }

        $producto = $this->productoRepository->buscarPorId($idNum);
        if ($producto) {
            $nuevoEstado = $producto['active'] ? 0 : 1;
            $this->productoRepository->cambiarEstado($idNum, $nuevoEstado);

            $mensaje = $nuevoEstado ? "Producto '{$producto['name']}' reactivado exitosamente." : "Producto '{$producto['name']}' dado de baja.";
            $_SESSION['flash_success'] = $mensaje;
        }

        header('Location: ' . url('/admin/productos'));
        exit;
    }

    /**
     * Actualiza el stock de un producto directamente vía AJAX desde el listado.
     */
    public function actualizarStockAjax(string $id): void
    {
        AdminGuard::check();

        header('Content-Type: application/json');

        $idNum = (int)$id;
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!AdminGuard::verifyCsrf($token)) {
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido.']);
            exit;
        }

        $stockInput = $_POST['stock'] ?? null;
        if ($stockInput === null || !is_numeric($stockInput) || (int)$stockInput < 0) {
            echo json_encode(['success' => false, 'message' => 'Stock inválido.']);
            exit;
        }

        $newStock = (int)$stockInput;
        $producto = $this->productoRepository->buscarPorId($idNum);

        if (!$producto) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
            exit;
        }

        try {
            $this->productoRepository->actualizar($idNum, [
                'code'        => $producto['code'],
                'name'        => $producto['name'],
                'description' => $producto['description'],
                'category_id' => $producto['category_id'],
                'price'       => $producto['price'],
                'stock'       => $newStock,
            ]);

            $isLowStock = ($newStock <= LOW_STOCK_THRESHOLD);

            echo json_encode([
                'success'      => true,
                'message'      => 'Stock actualizado.',
                'stock'        => $newStock,
                'is_low_stock' => $isLowStock
            ]);
            exit;
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos.']);
            exit;
        }
    }

    /**
     * Exporta la totalidad del catálogo actual a un archivo CSV descargable.
     */
    public function exportarCsv(): void
    {
        AdminGuard::check();

        $productos = $this->productoRepository->listarTodos();

        $filename = 'catalogo_redtec_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        // BOM UTF-8 para compatibilidad nativa con MS Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Cabecera CSV
        fputcsv($output, ['code', 'name', 'description', 'category', 'price', 'stock', 'active']);

        foreach ($productos as $p) {
            fputcsv($output, [
                $p['code'],
                $p['name'],
                $p['description'] ?? '',
                $p['category_name'] ?? '',
                number_format((float)$p['price'], 2, '.', ''),
                (int)$p['stock'],
                $p['active'] ? '1' : '0'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Procesa la subida de una nueva imagen para la galería del producto.
     */
    public function subirImagen(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        if (empty($_FILES['imagen']['name']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Por favor seleccione una imagen válida.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        $file     = $_FILES['imagen'];
        $maxSize  = 5 * 1024 * 1024;
        $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowed, true) || $file['size'] > $maxSize) {
            $_SESSION['flash_error'] = 'Formato no permitido o imagen muy pesada (máx 5MB). Usar JPG, PNG, WEBP o GIF.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        ];
        $ext = $extensions[$mimeType] ?? 'jpg';

        $uploadDir = __DIR__ . '/../../public/assets/uploads/productos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName   = 'prod_' . $idNum . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $imageUrl = '/assets/uploads/productos/' . $fileName;
            $this->productoRepository->agregarImagen($idNum, $imageUrl, 0);

            $_SESSION['flash_success'] = 'Imagen subida e incorporada a la galería correctamente.';
        } else {
            $_SESSION['flash_error'] = 'Ocurrió un error al mover el archivo al servidor.';
        }

        header('Location: ' . url("/admin/productos/{$idNum}/editar"));
        exit;
    }

    /**
     * Elimina una imagen de la galería del producto.
     */
    public function eliminarImagen(string $id, string $imageId): void
    {
        AdminGuard::check();

        $idNum      = (int)$id;
        $imageIdNum = (int)$imageId;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/productos/{$idNum}/editar"));
            exit;
        }

        $this->productoRepository->eliminarImagen($imageIdNum);

        $_SESSION['flash_success'] = 'Imagen eliminada de la galería.';
        header('Location: ' . url("/admin/productos/{$idNum}/editar"));
        exit;
    }
}
