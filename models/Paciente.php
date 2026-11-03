<?php
require_once "models/Paciente.php";

class PacienteController {

    public function lista() {
        $paciente = new Paciente();
        
        // Comprueba si existe la función 'listar', si no, intenta con las alternativas comunes
        if (method_exists($paciente, 'listar')) {
            $pacientes = $paciente->listar();
        } elseif (method_exists($paciente, 'mostrar')) {
            $pacientes = $paciente->mostrar();
        } elseif (method_exists($paciente, 'obtenerTodos')) {
            $pacientes = $paciente->obtenerTodos();
        } else {
            $pacientes = [];
        }

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
