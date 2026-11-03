<?php
require_once "models/Cita.php";
require_once "models/Paciente.php";
require_once "models/Servicio.php";

class CitaController {
    public function listar() {
        $citas = Cita::obtenerTodas();
        require "views/cita/lista.php";
    }

    public function crear() {
        $pacientes = Paciente::obtenerTodos();
        $servicios = Servicio::obtenerTodos();
        require "views/cita/formulario.php";
    }

    public function guardar() {
        $paciente_id = $_POST['paciente_id'];
        $servicio_id = $_POST['servicio_id'];
        $fecha = $_POST['fecha'];
        $motivo = $_POST['motivo'];

        if (Cita::registrarCita($paciente_id, $servicio_id, $fecha, $motivo)) {
            header("Location: index.php?c=cita");
            exit;
        } else {
            echo "Error al registrar la cita.";
        }
    }
}
?>