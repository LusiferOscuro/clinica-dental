<?php
require_once "models/Paciente.php";

class PacienteController {
    public function lista() {
        $paciente = new Paciente();
        $pacientes = method_exists($paciente, 'getPacientes') ? $paciente->getPacientes() : [];
        require_once "views/paciente/lista.php";
    }

    public function index() {
        $this->lista();
    }

    public function formulario() {
        require_once "views/paciente/formulario.php";
    }
}
?>