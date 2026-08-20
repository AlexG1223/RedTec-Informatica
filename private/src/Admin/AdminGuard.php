<?php

namespace RedTec\Admin;

/**
 * Guard de Autenticación y Verificador CSRF para el Panel de Administración
 */
class AdminGuard
{
    /**
     * Verifica que exista una sesión de administración activa.
     * Si el usuario no está autenticado, lo redirige al formulario de login.
     */
    public static function check(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }

    /**
     * Genera un token CSRF seguro y lo almacena en la sesión.
     * 
     * @return string Token CSRF
     */
    public static function csrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Valida si el token enviado coincide con el token en la sesión.
     * 
     * @param string|null $token Token recibido del formulario POST
     * @return bool
     */
    public static function verifyCsrf(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
