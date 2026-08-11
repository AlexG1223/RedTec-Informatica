<?php

namespace RedTec\Shared;

use PDO;
use PDOException;
use Exception;

/**
 * RedTec Informática - Capa de Conexión a Base de Datos (PDO Singleton)
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Devuelve la instancia única de conexión PDO.
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

            $host    = $config['host'] ?? '127.0.0.1';
            $port    = $config['port'] ?? 3306;
            $dbName  = $config['db_name'] ?? '';
            $user    = $config['username'] ?? '';
            $pass    = $config['password'] ?? '';
            $charset = $config['charset'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
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
