<?php
namespace controllers;

use models\Paciente;

class PacienteController {

    public function index() {
        $this->lista();
    }

    public function lista() {
        $pacientes = Paciente::listar();
        require_once 'views/layout/header.php';
        require_once 'views/pacientes/lista.php';
        require_once 'views/layout/footer.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Paciente::guardar($_POST);
        }
        header("Location: index.php?c=paciente&a=lista");
        exit;
    }

    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            Paciente::eliminar($id);
        }
        header("Location: index.php?c=paciente&a=lista");
        exit;
    }
}
