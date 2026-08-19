<?php

namespace RedTec\Shared;

use PDO;
use PDOException;
use Exception;

/**
 * RedTec Informática - Capa de Conexión a Base de Datos (PDO Singleton Autodetect)
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Devuelve la instancia única de conexión PDO probando automáticamente las variantes de host y socket de one.com.
     *
     * @return PDO
     * @throws Exception Si no existe la configuración o falla la conexión.
     */
    public static function connect(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $configFile = __DIR__ . '/../config/database.php';

        if (!file_exists($configFile)) {
            throw new Exception(
                "Error de Configuración: No se encontró el archivo 'config/database.php'. " .
                "Por favor copia 'config/database.example.php' como 'config/database.php' en la carpeta /config/ de tu servidor e ingresa tus credenciales."
            );
        }

        $config = require $configFile;

        $configuredHost = trim($config['host'] ?? 'localhost');
        $port           = (int)($config['port'] ?? 3306);
        $dbName         = trim($config['db_name'] ?? '');
        $user           = trim($config['username'] ?? '');
        $pass           = $config['password'] ?? '';
        $charset        = $config['charset'] ?? 'utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
        ];

        // Construcción de lista inteligente de DSNs a probar secuencialmente
        $dsnCandidates = [];

        // 1. Host configurado en database.php (sin puerto si es localhost para forzar Unix Socket)
        if (strtolower($configuredHost) === 'localhost') {
            $dsnCandidates[] = "mysql:host=localhost;dbname={$dbName};charset={$charset}";
        } else {
            $dsnCandidates[] = "mysql:host={$configuredHost};port={$port};dbname={$dbName};charset={$charset}";
        }

        // 2. Localhost Socket Unix Estándar
        $dsnCandidates[] = "mysql:host=localhost;dbname={$dbName};charset={$charset}";

        // 3. Dominio .mysql de one.com (ej: redtecinformatica.com.mysql)
        if (!empty($_SERVER['HTTP_HOST'])) {
            $domain = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);
            $domain = explode(':', $domain)[0];
            $dsnCandidates[] = "mysql:host={$domain}.mysql;dbname={$dbName};charset={$charset}";
        }

        // 4. IP Loopback TCP 127.0.0.1
        $dsnCandidates[] = "mysql:host=127.0.0.1;port={$port};dbname={$dbName};charset={$charset}";

        // 5. Rutas explícitas de sockets Unix de Linux hosting
        $dsnCandidates[] = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname={$dbName};charset={$charset}";
        $dsnCandidates[] = "mysql:unix_socket=/tmp/mysql.sock;dbname={$dbName};charset={$charset}";
        $dsnCandidates[] = "mysql:unix_socket=/var/lib/mysql/mysql.sock;dbname={$dbName};charset={$charset}";

        $lastException = null;

        // Probar secuencialmente hasta que uno funcione
        foreach (array_unique($dsnCandidates) as $dsn) {
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
                self::$instance = $pdo;
                return self::$instance;
            } catch (PDOException $e) {
                $lastException = $e;
                continue;
            }
        }

        $errorMsg = $lastException ? $lastException->getMessage() : "No se pudo establecer conexión con MySQL.";
        throw new Exception("Error al conectar a MySQL: " . $errorMsg, 500);
    }

    /**
     * Previene la clonación del Singleton.
     */
    private function __clone() {}

    /**
     * Previene la deserialización del Singleton.
     */
    public function __wakeup() {}
}
