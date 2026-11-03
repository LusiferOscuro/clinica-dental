<?php
require_once "config/Database.php";

class Paciente {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function getPacientes() {
        $sql = "SELECT * FROM pacientes";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
