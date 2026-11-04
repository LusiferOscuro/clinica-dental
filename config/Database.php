<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $db = null;

    public static function connect(): ?PDO {
        if (self::$db === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $dbname = getenv('DB_NAME') ?: 'clinica_db';
            $port = getenv('DB_PORT') ?: '3306';

            try {
                self::$db = new PDO("mysql:host={$host};dbname={$dbname};port={$port};charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                // Si la BD no está disponible en local, la app sigue funcionando con fallback en sesión
                return null;
            }
        }
        return self::$db;
    }
}
