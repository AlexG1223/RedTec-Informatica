<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\ServiciosTecnicos\ServicioRepository;
use Throwable;

/**
 * Controlador del Panel de Administración para Servicios Técnicos
 */
class ServicioAdminController
{
    private ServicioRepository $servicioRepository;

    public function __construct()
    {
        $this->servicioRepository = new ServicioRepository();
    }

    /**
     * Muestra el listado de todos los servicios técnicos.
     */
    public function index(): void
    {
        AdminGuard::check();

        $servicios = $this->servicioRepository->listarTodos();

        $pageTitle  = "Gestión de Servicios Técnicos";
        $activeMenu = "servicios";

        require __DIR__ . '/views/servicios/listado.php';
    }

    /**
     * Formulario de creación de un nuevo servicio.
     */
    public function crearForm(): void
    {
        AdminGuard::check();

        $servicio  = null;
        $pageTitle  = "Nuevo Servicio Técnico";
        $activeMenu = "servicios";

        require __DIR__ . '/views/servicios/form.php';
    }

    /**
     * Procesa la creación de un nuevo servicio.
     */
    public function guardar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/servicios/nuevo'));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl    = trim($_POST['image_url'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre del servicio es obligatorio.';
            header('Location: ' . url('/admin/servicios/nuevo'));
            exit;
        }

        // Manejo opcional de subida de imagen
        $uploadUrl = $this->procesarSubidaImagen();
        if ($uploadUrl) {
            $imageUrl = $uploadUrl;
        }

        try {
            $this->servicioRepository->crear([
                'name'        => $name,
                'description' => $description,
                'image_url'   => !empty($imageUrl) ? $imageUrl : null,
            ]);

            $_SESSION['flash_success'] = "Servicio '{$name}' creado exitosamente.";
            header('Location: ' . url('/admin/servicios'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al crear el servicio.';
            header('Location: ' . url('/admin/servicios/nuevo'));
            exit;
        }
    }

    /**
     * Formulario de edición de un servicio existente.
     */
    public function editarForm(string $id): void
    {
        AdminGuard::check();

        $idNum    = (int)$id;
        $servicio = $this->servicioRepository->buscarPorId($idNum);

        if (!$servicio) {
            $_SESSION['flash_error'] = 'Servicio no encontrado.';
            header('Location: ' . url('/admin/servicios'));
            exit;
        }

        $pageTitle  = "Editar Servicio: " . $servicio['name'];
        $activeMenu = "servicios";

        require __DIR__ . '/views/servicios/form.php';
    }

    /**
     * Procesa la actualización de un servicio técnico.
     */
    public function actualizar(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/servicios/{$idNum}/editar"));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl    = trim($_POST['image_url'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre del servicio es obligatorio.';
            header('Location: ' . url("/admin/servicios/{$idNum}/editar"));
            exit;
        }

        // Manejo opcional de subida de imagen
        $uploadUrl = $this->procesarSubidaImagen();
        if ($uploadUrl) {
            $imageUrl = $uploadUrl;
        }

        try {
            $this->servicioRepository->actualizar($idNum, [
                'name'        => $name,
                'description' => $description,
                'image_url'   => !empty($imageUrl) ? $imageUrl : null,
            ]);

            $_SESSION['flash_success'] = "Servicio '{$name}' actualizado correctamente.";
            header('Location: ' . url('/admin/servicios'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al actualizar el servicio.';
            header('Location: ' . url("/admin/servicios/{$idNum}/editar"));
            exit;
        }
    }

    /**
     * Alterna el estado activo (Dar de baja / Reactivar).
     */
    public function cambiarEstado(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/servicios'));
            exit;
        }

        $servicio = $this->servicioRepository->buscarPorId($idNum);
        if ($servicio) {
            $nuevoEstado = $servicio['active'] ? 0 : 1;
            $this->servicioRepository->cambiarEstado($idNum, $nuevoEstado);

            $mensaje = $nuevoEstado ? "Servicio '{$servicio['name']}' reactivado exitosamente." : "Servicio '{$servicio['name']}' dado de baja.";
            $_SESSION['flash_success'] = $mensaje;
        }

        header('Location: ' . url('/admin/servicios'));
        exit;
    }

    /**
     * Procesa la subida opcional de imagen para el servicio.
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

        $uploadDir = __DIR__ . '/../../public/assets/uploads/servicios/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName   = 'srv_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/assets/uploads/servicios/' . $fileName;
        }

        return null;
    }
}
