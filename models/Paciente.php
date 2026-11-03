<?php
require_once "config/Database.php";

class Paciente {
    public static function obtenerTodos() {
        $db = Database::conectar();
        $stmt = $db->query("SELECT * FROM pacientes ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregar($nombre, $apellido, $ci, $telefono) {
        $db = Database::conectar();
        $stmt = $db->prepare("INSERT INTO pacientes (nombre, apellido, ci, telefono) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $ci, $telefono]);
    }
}
?>