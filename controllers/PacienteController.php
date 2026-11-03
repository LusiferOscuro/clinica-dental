<?php
require_once "models/Paciente.php";

class PacienteController {
    public function listar() {
        $pacientes = Paciente::obtenerTodos();
        require "views/paciente/lista.php";
    }

    public function crear() {
        require "views/paciente/formulario.php";
    }

    public function guardar() {
        Paciente::agregar($_POST['nombre'], $_POST['apellido'], $_POST['ci'], $_POST['telefono']);
        header("Location: index.php?c=paciente");
        exit;
    }
}
?>