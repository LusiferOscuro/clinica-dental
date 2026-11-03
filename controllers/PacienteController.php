<?php
require_once "models/Paciente.php";

class PacienteController {

    // Método principal para listar pacientes
    public function lista() {
        $paciente = new Paciente();
        $pacientes = $paciente->listar();
        require_once "views/paciente/lista.php";
    }

    // Alias por si el enrutador llama a index
    public function index() {
        $this->lista();
    }

    public function formulario() {
        require_once "views/paciente/formulario.php";
    }
}
?>
