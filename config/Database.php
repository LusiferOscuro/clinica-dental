<?php
class Database {
    public static function conectar() {
        $host = "localhost";
        $db = "clinica_dental";
        $user = "root";
        $password = "";
        
        try {
            $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>