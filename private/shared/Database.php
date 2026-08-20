<?php

namespace RedTec\Shared;

use PDO;
use PDOException;
use Exception;

/**
 * RedTec Informática - Capa de Conexión a Base de Datos (Producción Directa Ultrafina)
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Devuelve la instancia única de conexión PDO directa e instantánea (< 1ms).
     *
     * @return PDO
     * @throws Exception Si falla la conexión.
     */
    public static function connect(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $configFile  = __DIR__ . '/../config/database.php';
        $exampleFile = __DIR__ . '/../config/database.example.php';

        if (file_exists($configFile)) {
            $config = require $configFile;
        } elseif (file_exists($exampleFile)) {
            $config = require $exampleFile;
        } else {
            throw new Exception("Error de Configuración: No se encontró 'database.php'.");
        }

        $host    = trim($config['host'] ?? 'localhost');
        $dbName  = trim($config['db_name'] ?? 'c064ao1q8_redtec');
        $user    = trim($config['username'] ?? 'c064ao1q8_redtec');
        $pass    = $config['password'] ?? 'redtec1234';
        $charset = $config['charset'] ?? 'utf8mb4';

        if (!defined('IS_LOCAL') || !IS_LOCAL) {
            if ($user === 'root' || empty($user)) {
                $user = 'c064ao1q8_redtec';
                if (empty($pass)) {
                    $pass = 'redtec1234';
                }
            }
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 2,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
        ];

        // Conexión Unix Socket nativa directa para velocidad extrema en Linux hosting
        if (strtolower($host) === 'localhost') {
            $dsn = "mysql:host=localhost;dbname={$dbName};charset={$charset}";
        } else {
            $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";
        }

        try {
            self::$instance = new PDO($dsn, $user, $pass, $options);
            return self::$instance;
        } catch (PDOException $e) {
            // Fallback directo a socket Unix si el host primario falla
            $fallbackDsn = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname={$dbName};charset={$charset}";
            try {
                self::$instance = new PDO($fallbackDsn, $user, $pass, $options);
                return self::$instance;
            } catch (PDOException $ex) {
                throw new Exception("Error al conectar a MySQL: " . $e->getMessage(), 500);
            }
        }
    }

    private function __clone() {}
    public function __wakeup() {}
}
