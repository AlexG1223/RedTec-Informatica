<?php

namespace RedTec\Admin;

use RedTec\Checkout\OrderRepository;
use Throwable;

/**
 * Controlador del Panel de Administración para la Gestión de Pedidos
 */
class PedidoAdminController
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
    }

    /**
     * Muestra el listado de pedidos registrados.
     */
    public function index(): void
    {
        AdminGuard::check();

        $estado = $_GET['estado'] ?? '';
        $metodo = $_GET['metodo'] ?? '';
        $buscar = $_GET['buscar'] ?? '';

        $pedidos = $this->orderRepo->listarTodos([
            'estado' => $estado,
            'metodo' => $metodo,
            'buscar' => $buscar
        ]);

        $pageTitle  = "Gestión de Pedidos — RedTec Informática";
        $activeMenu = "pedidos";

        require __DIR__ . '/views/pedidos/index.php';
    }

    /**
     * Cambia el estado de un pedido desde el panel.
     *
     * @param string|int|null $idFromRoute ID de pedido capturado por la regex de la ruta
     */
    public function cambiarEstado($idFromRoute = null): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/pedidos'));
            exit;
        }

        $id = (int)($idFromRoute ?? ($_POST['order_id'] ?? 0));

        if ($id <= 0) {
            $parts = explode('/', trim($_SERVER['REQUEST_URI'] ?? '', '/'));
            foreach ($parts as $p) {
                if (is_numeric($p)) {
                    $id = (int)$p;
                    break;
                }
            }
        }

        $nuevoEstado = trim($_POST['status'] ?? '');

        if ($id > 0 && in_array($nuevoEstado, ['pagado', 'pendiente_pago', 'cancelado', 'fallido'], true)) {
            $exito = $this->orderRepo->cambiarEstado($id, $nuevoEstado);
            if ($exito) {
                $_SESSION['flash_success'] = "El pedido #" . sprintf('%05d', $id) . " se actualizó a estado '" . strtoupper($nuevoEstado) . "'.";
            } else {
                $_SESSION['flash_error'] = "No se pudo actualizar el estado del pedido.";
            }
        }

        header('Location: ' . url('/admin/pedidos'));
        exit;
    }

    /**
     * Elimina un pedido permanentemente.
     *
     * @param string|int|null $idFromRoute ID de pedido capturado por la regex de la ruta
     */
    public function eliminar($idFromRoute = null): void
    {
        AdminGuard::check();

        if (!AdminGuard::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token CSRF inválido.';
            header('Location: ' . url('/admin/pedidos'));
            exit;
        }

        $id = (int)($idFromRoute ?? ($_POST['order_id'] ?? 0));

        if ($id <= 0) {
            $parts = explode('/', trim($_SERVER['REQUEST_URI'] ?? '', '/'));
            foreach ($parts as $p) {
                if (is_numeric($p)) {
                    $id = (int)$p;
                    break;
                }
            }
        }

        if ($id > 0) {
            $exito = $this->orderRepo->eliminarPedido($id);
            if ($exito) {
                $_SESSION['flash_success'] = "El pedido #" . sprintf('%05d', $id) . " fue eliminado permanentemente.";
            } else {
                $_SESSION['flash_error'] = "No se pudo eliminar el pedido.";
            }
        }

        header('Location: ' . url('/admin/pedidos'));
        exit;
    }
}
