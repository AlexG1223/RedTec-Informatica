<?php

namespace RedTec\Shared;

use PDO;
use PDOException;
use Exception;

/**
 * RedTec Informática - Capa de Conexión a Base de Datos (PDO Singleton Robustecida)
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Devuelve la instancia única de conexión PDO con soporte para Unix Sockets y TCP fallbacks.
     *
     * @return PDO
     * @throws Exception Si no existe la configuración o falla la conexión.
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $configFile = __DIR__ . '/../config/database.php';

            if (!file_exists($configFile)) {
                throw new Exception(
                    "Error de Configuración: No se encontró el archivo 'config/database.php'. " .
                    "Por favor copia 'config/database.example.php' como 'config/database.php' y configura tus credenciales de base de datos."
                );
            }

            $config = require $configFile;

            $host    = $config['host'] ?? 'localhost';
            $port    = $config['port'] ?? 3306;
            $dbName  = $config['db_name'] ?? '';
            $user    = $config['username'] ?? '';
            $pass    = $config['password'] ?? '';
            $charset = $config['charset'] ?? 'utf8mb4';

            // Para 'localhost' en Linux shared hosting (one.com), omitir port en DSN para usar Unix Socket nativo
            if (strtolower($host) === 'localhost') {
                $dsn = "mysql:host=localhost;dbname={$dbName};charset={$charset}";
            } else {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
            }

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Fallbacks secuenciales para entornos de hosting compartido con sockets personalizados
                $fallbackDsns = [
                    "mysql:host=localhost;port={$port};dbname={$dbName};charset={$charset}",
                    "mysql:host=127.0.0.1;port={$port};dbname={$dbName};charset={$charset}",
                    "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname={$dbName};charset={$charset}",
                    "mysql:unix_socket=/tmp/mysql.sock;dbname={$dbName};charset={$charset}",
                ];

                foreach ($fallbackDsns as $fbDsn) {
                    try {
                        self::$instance = new PDO($fbDsn, $user, $pass, $options);
                        return self::$instance;
                    } catch (PDOException $ex) {
                        continue;
                    }
                }

                throw new Exception("Error al conectar a la base de datos MySQL: " . $e->getMessage(), (int)$e->getCode(), $e);
            }
        }

        return self::$instance;
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
