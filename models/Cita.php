<?php
require_once "config/Database.php";

class Cita {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function getCitas() {
        $sql = "SELECT c.id, p.nombre AS paciente, s.nombre AS servicio, c.fecha, c.hora 
                FROM citas c
                LEFT JOIN pacientes p ON c.paciente_id = p.id
                LEFT JOIN servicios s ON c.servicio_id = s.id";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>