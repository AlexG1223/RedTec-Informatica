<?php

namespace RedTec\Admin;

use RedTec\Admin\AdminGuard;
use RedTec\ServiciosCorporativos\ServicioPackageRepository;
use Throwable;

/**
 * Controlador del Panel de Administración para Planes Corporativos
 */
class ServicioPackageAdminController
{
    private ServicioPackageRepository $packageRepository;

    public function __construct()
    {
        $this->packageRepository = new ServicioPackageRepository();
    }

    /**
     * Muestra el listado de todos los planes corporativos.
     */
    public function index(): void
    {
        AdminGuard::check();

        $planes = $this->packageRepository->listarTodos();

        $pageTitle  = "Gestión de Planes Corporativos";
        $activeMenu = "planes";

        require __DIR__ . '/views/planes/listado.php';
    }

    /**
     * Formulario de creación de un nuevo plan.
     */
    public function crearForm(): void
    {
        AdminGuard::check();

        $plan       = null;
        $pageTitle  = "Nuevo Plan Corporativo";
        $activeMenu = "planes";

        require __DIR__ . '/views/planes/form.php';
    }

    /**
     * Procesa la creación de un nuevo plan.
     */
    public function guardar(): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/planes/nuevo'));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priceInput  = trim($_POST['price'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre del plan es obligatorio.';
            header('Location: ' . url('/admin/planes/nuevo'));
            exit;
        }

        $price = ($priceInput !== '' && is_numeric($priceInput)) ? (float)$priceInput : null;

        try {
            $this->packageRepository->crear([
                'name'        => $name,
                'description' => $description,
                'price'       => $price,
            ]);

            $_SESSION['flash_success'] = "Plan corporativo '{$name}' creado exitosamente.";
            header('Location: ' . url('/admin/planes'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al crear el plan corporativo.';
            header('Location: ' . url('/admin/planes/nuevo'));
            exit;
        }
    }

    /**
     * Formulario de edición de un plan existente.
     */
    public function editarForm(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;
        $plan  = $this->packageRepository->buscarPorId($idNum);

        if (!$plan) {
            $_SESSION['flash_error'] = 'Plan corporativo no encontrado.';
            header('Location: ' . url('/admin/planes'));
            exit;
        }

        $pageTitle  = "Editar Plan: " . $plan['name'];
        $activeMenu = "planes";

        require __DIR__ . '/views/planes/form.php';
    }

    /**
     * Procesa la actualización de un plan corporativo.
     */
    public function actualizar(string $id): void
    {
        AdminGuard::check();

        $idNum = (int)$id;

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url("/admin/planes/{$idNum}/editar"));
            exit;
        }

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priceInput  = trim($_POST['price'] ?? '');

        if (empty($name)) {
            $_SESSION['flash_error'] = 'El nombre del plan es obligatorio.';
            header('Location: ' . url("/admin/planes/{$idNum}/editar"));
            exit;
        }

        $price = ($priceInput !== '' && is_numeric($priceInput)) ? (float)$priceInput : null;

        try {
            $this->packageRepository->actualizar($idNum, [
                'name'        => $name,
                'description' => $description,
                'price'       => $price,
            ]);

            $_SESSION['flash_success'] = "Plan '{$name}' actualizado correctamente.";
            header('Location: ' . url('/admin/planes'));
            exit;
        } catch (Throwable $e) {
            $_SESSION['flash_error'] = 'Error al actualizar el plan corporativo.';
            header('Location: ' . url("/admin/planes/{$idNum}/editar"));
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
            header('Location: ' . url('/admin/planes'));
            exit;
        }

        $plan = $this->packageRepository->buscarPorId($idNum);
        if ($plan) {
            $nuevoEstado = $plan['active'] ? 0 : 1;
            $this->packageRepository->cambiarEstado($idNum, $nuevoEstado);

            $mensaje = $nuevoEstado ? "Plan '{$plan['name']}' reactivado exitosamente." : "Plan '{$plan['name']}' dado de baja.";
            $_SESSION['flash_success'] = $mensaje;
        }

        header('Location: ' . url('/admin/planes'));
        exit;
    }
}
