<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $host     = getenv('DB_HOST') ?: '127.0.0.1';
            $port     = getenv('DB_PORT') ?: '3306';
            $db_name  = getenv('DB_NAME') ?: 'clinica_dental_db';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: '';

            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
                self::$conn = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Error de conexión a MySQL: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
