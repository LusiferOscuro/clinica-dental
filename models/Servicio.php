<?php
require_once "config/Database.php";

class Servicio {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function obtener() {
        $sql = "SELECT * FROM servicios";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>