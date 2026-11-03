<?php
class Database {
    public static function conectar() {
        $host = getenv('DB_HOST') ?: 'localhost';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $db   = getenv('DB_NAME') ?: 'clinica_dental';
        $port = getenv('DB_PORT') ?: 3306;

        $conexion = new mysqli($host, $user, $pass, $db, $port);
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        $conexion->set_charset("utf8");
        return $conexion;
    }
}
?>