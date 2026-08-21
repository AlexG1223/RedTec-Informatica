<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\Categorias\CategoriaRepository;
use Throwable;

/**
 * Controlador del Panel de Administración para la Gestión de Categorías
 */
class CategoriaAdminController
{
    private CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->categoriaRepository = new CategoriaRepository();
    }

    /**
     * Listado de todas las categorías con conteo de productos asociados.
     */
    public function index(): void
    {
        AdminGuard::check();

        $categorias = $this->categoriaRepository->listarTodasConConteo();

        $pageTitle  = "Gestión de Categorías";
        $activeMenu = "categorias";

        require __DIR__ . '/views/categorias/listado.php';
    }

    /**
     * Formulario para crear una nueva categoría.
     */
    public function crearForm(): void
    {
        AdminGuard::check();

        $categoria  = null;
        $pageTitle  = "Nueva Categoría";
        $activeMenu = "categorias";

        require __DIR__ . '/views/categorias/form.php';
    }

    /**
     * Procesa la creación de una nueva categoría.
     */
    public function guardar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/categorias/nuevo'));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl    = trim($_POST['image_url'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre de la categoría es obligatorio.';
            header('Location: ' . url('/admin/categorias/nuevo'));
            exit;
        }

        // Subida opcional de imagen
        $uploadUrl = $this->procesarSubidaImagen();
        if ($uploadUrl) {
            $imageUrl = $uploadUrl;
        }

        try {
            $this->categoriaRepository->crear([
                'name'        => $name,
                'description' => $description,
                'image_url'   => !empty($imageUrl) ? $imageUrl : null,
            ]);

            $_SESSION['flash_success'] = "Categoría '{$name}' creada correctamente.";
            header('Location: ' . url('/admin/categorias'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al crear la categoría en la base de datos.';
            header('Location: ' . url('/admin/categorias/nuevo'));
            exit;
        }
    }

    /**
     * Formulario para editar una categoría existente.
     */
    public function editarForm(string $id): void
    {
        AdminGuard::check();

        $idNum     = (int)$id;
        $categoria = $this->categoriaRepository->buscarPorId($idNum);

        if (!$categoria) {
            $_SESSION['flash_error'] = 'Categoría no encontrada.';
            header('Location: ' . url('/admin/categorias'));
            exit;
        }

        $pageTitle  = "Editar Categoría: " . $categoria['name'];
        $activeMenu = "categorias";

        require __DIR__ . '/views/categorias/form.php';
    }

    /**
     * Procesa la actualización de una categoría.
     */
    public function actualizar(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/categorias/{$idNum}/editar"));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl    = trim($_POST['image_url'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre de la categoría es obligatorio.';
            header('Location: ' . url("/admin/categorias/{$idNum}/editar"));
            exit;
        }

        // Subida opcional de imagen
        $uploadUrl = $this->procesarSubidaImagen();
        if ($uploadUrl) {
            $imageUrl = $uploadUrl;
        }

        try {
            $this->categoriaRepository->actualizar($idNum, [
                'name'        => $name,
                'description' => $description,
                'image_url'   => !empty($imageUrl) ? $imageUrl : null,
            ]);

            $_SESSION['flash_success'] = "Categoría '{$name}' actualizada exitosamente.";
            header('Location: ' . url('/admin/categorias'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al actualizar la categoría.';
            header('Location: ' . url("/admin/categorias/{$idNum}/editar"));
            exit;
        }
    }

    /**
     * Elimina una categoría si no tiene productos asociados.
     */
    public function eliminar(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/categorias'));
            exit;
        }

        $categoria = $this->categoriaRepository->buscarPorId($idNum);
        if (!$categoria) {
            $_SESSION['flash_error'] = 'Categoría no encontrada.';
            header('Location: ' . url('/admin/categorias'));
            exit;
        }

        $productosAsociados = $this->categoriaRepository->contarProductos($idNum);
        if ($productosAsociados > 0) {
            $_SESSION['flash_error'] = "No se puede eliminar la categoría '{$categoria['name']}' porque tiene {$productosAsociados} producto(s) asociado(s). Primero reasigne o elimine dichos productos.";
            header('Location: ' . url('/admin/categorias'));
            exit;
        }

        $res = $this->categoriaRepository->eliminar($idNum);
        if ($res) {
            $_SESSION['flash_success'] = "Categoría '{$categoria['name']}' eliminada correctamente.";
        } else {
            $_SESSION['flash_error'] = "Ocurrió un error al eliminar la categoría.";
        }

        header('Location: ' . url('/admin/categorias'));
        exit;
    }

    /**
     * Procesa la subida opcional de archivo de imagen para la categoría.
     */
    private function procesarSubidaImagen(): ?string
    {
        if (empty($_FILES['imagen_archivo']['name']) || $_FILES['imagen_archivo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file     = $_FILES['imagen_archivo'];
        $maxSize  = 5 * 1024 * 1024;
        $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowed, true) || $file['size'] > $maxSize) {
            return null;
        }

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
        ];
        $ext = $extensions[$mimeType] ?? 'jpg';

        $uploadDir = REDTEC_PRIVATE_DIR . '/../public/assets/uploads/categorias/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName   = 'cat_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/assets/uploads/categorias/' . $fileName;
        }

        return null;
    }
}
