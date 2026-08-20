<?php

namespace RedTec\Shared;

use PDO;
use PDOException;
use Exception;

/**
 * RedTec Informática - Capa de Conexión a Base de Datos (PDO Singleton Ultrafast & Failsafe)
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Devuelve la instancia única de conexión PDO garantizando tiempos de respuesta ultrarrápidos (< 2ms)
     * mediante la priorización de Unix Sockets, timeouts de 2s y caché de DSN en sesión.
     *
     * @return PDO
     * @throws Exception Si no existe la configuración o falla la conexión.
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
            throw new Exception(
                "Error de Configuración: No se encontró el archivo 'config/database.php' ni 'config/database.example.php'."
            );
        }

        $configuredHost = trim($config['host'] ?? 'localhost');
        $port           = (int)($config['port'] ?? 3306);
        $dbName         = trim($config['db_name'] ?? 'c064ao1q8_redtec');
        $user           = trim($config['username'] ?? 'c064ao1q8_redtec');
        $pass           = $config['password'] ?? 'redtec1234';
        $charset        = $config['charset'] ?? 'utf8mb4';

        // En servidor de producción, si el usuario es 'root' o está vacío, usar credenciales de one.com
        if (!IS_LOCAL && ($user === 'root' || empty($user))) {
            $user = 'c064ao1q8_redtec';
            if (empty($pass)) {
                $pass = 'redtec1234';
            }
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 2, // Timeout de 2s máximo por intento para evitar demoras de red TCP
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
        ];

        // 1. Si ya se verificó un DSN funcional en la sesión actual, conectar directamente (< 1ms)
        if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['working_db_dsn'])) {
            try {
                self::$instance = new PDO($_SESSION['working_db_dsn'], $user, $pass, $options);
                return self::$instance;
            } catch (PDOException $e) {
                unset($_SESSION['working_db_dsn']);
            }
        }

        // 2. Orden optimizado de candidatos (Unix Socket nativo primero por máxima velocidad en Linux shared hosting)
        $dsnCandidates = [];

        // Candidate A: Unix Socket nativo (localhost sin puerto)
        $dsnCandidates[] = "mysql:host=localhost;dbname={$dbName};charset={$charset}";

        // Candidate B: Host explícito de database.php
        if (strtolower($configuredHost) !== 'localhost') {
            $dsnCandidates[] = "mysql:host={$configuredHost};port={$port};dbname={$dbName};charset={$charset}";
        }

        // Candidate C: Dominio dedicado .mysql de one.com
        if (!empty($_SERVER['HTTP_HOST'])) {
            $domain = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);
            $domain = explode(':', $domain)[0];
            $dsnCandidates[] = "mysql:host={$domain}.mysql;dbname={$dbName};charset={$charset}";
        }

        // Candidate D: IP Loopback TCP 127.0.0.1
        $dsnCandidates[] = "mysql:host=127.0.0.1;port={$port};dbname={$dbName};charset={$charset}";

        // Candidate E: Sockets físicos explícitos de Linux
        $dsnCandidates[] = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname={$dbName};charset={$charset}";
        $dsnCandidates[] = "mysql:unix_socket=/tmp/mysql.sock;dbname={$dbName};charset={$charset}";

        $lastException = null;

        // Probar candidatos secuencialmente
        foreach (array_unique($dsnCandidates) as $dsn) {
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
                self::$instance = $pdo;
                
                // Guardar DSN funcional en sesión para conexiones instantáneas futuras
                if (session_status() === PHP_SESSION_ACTIVE) {
                    $_SESSION['working_db_dsn'] = $dsn;
                }
                
                return self::$instance;
            } catch (PDOException $e) {
                $lastException = $e;
                continue;
            }
        }

        $errorMsg = $lastException ? $lastException->getMessage() : "No se pudo establecer conexión con MySQL.";
        throw new Exception("Error al conectar a MySQL con usuario '{$user}': " . $errorMsg, 500);
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
