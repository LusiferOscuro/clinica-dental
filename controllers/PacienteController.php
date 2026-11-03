<?php
require_once "models/Paciente.php";

class PacienteController {
    public function lista() {
        $modelo = new Paciente();
        $pacientes = $modelo->getPacientes();
        require_once "views/pacientes/lista.php";
    }
}
?>