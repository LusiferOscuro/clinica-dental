<?php
require_once "config/Database.php";

class Servicio {
    public static function obtenerTodos() {
        $db = Database::conectar();
        $resultado = $db->query("SELECT * FROM servicios ORDER BY id DESC");
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregar($nombre, $descripcion, $precio) {
        $db = Database::conectar();
        $stmt = $db->prepare("INSERT INTO servicios (nombre, descripcion, precio) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio]);
    }

    public static function eliminar($id) {
        $db = Database::conectar();
        $stmt = $db->prepare("DELETE FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>