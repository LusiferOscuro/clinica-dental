<?php
require_once "models/Cita.php";

class CitaController {
    public function lista() {
        require_once "views/cita/lista.php";
    }

    public function index() {
        $this->lista();
    }

    public function formulario() {
        require_once "views/cita/formulario.php";
    }
}
?>