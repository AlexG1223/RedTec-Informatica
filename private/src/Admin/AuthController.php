<?php

namespace RedTec\Admin;

use RedTec\Shared\Database;
use RedTec\Admin\AdminGuard;
use PDO;
use Throwable;

/**
 * Controlador de Autenticación del Panel de Administración
 */
class AuthController
{
    /**
     * Muestra el formulario de inicio de sesión de administración.
     */
    public function loginForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya está autenticado, redirigir directo al dashboard
        if (!empty($_SESSION['admin_id'])) {
            header('Location: ' . url('/admin'));
            exit;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $csrfToken = AdminGuard::csrfToken();
        require __DIR__ . '/views/login.php';
    }

    /**
     * Procesa el intento de login con email y contraseña.
     */
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token    = $_POST['csrf_token'] ?? null;
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!AdminGuard::verifyCsrf($token)) {
            $_SESSION['login_error'] = 'Token de seguridad inválido. Por favor intente nuevamente.';
            header('Location: ' . url('/admin/login'));
            exit;
        }

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Por favor ingrese su email y contraseña.';
            header('Location: ' . url('/admin/login'));
            exit;
        }

        try {
            $pdo  = Database::connect();
            $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Regenerar el ID de sesión para prevenir Session Fixation
                session_regenerate_id(true);

                $_SESSION['admin_id']    = (int)$admin['id'];
                $_SESSION['admin_name']  = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];

                header('Location: ' . url('/admin'));
                exit;
            } else {
                $_SESSION['login_error'] = 'Credenciales de acceso incorrectas.';
                header('Location: ' . url('/admin/login'));
                exit;
            }
        } catch (Throwable $e) {
            if (IS_LOCAL) {
                $_SESSION['login_error'] = 'Error de BD: ' . $e->getMessage();
            } else {
                $_SESSION['login_error'] = 'Error al conectar con la base de datos.';
            }
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }

    /**
     * Cierra la sesión activa del administrador.
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header('Location: ' . url('/admin/login'));
        exit;
    }
}
