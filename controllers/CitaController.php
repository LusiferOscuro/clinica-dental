<?php
namespace controllers;

use models\Cita;

class CitaController {

    public function index() {
        $this->lista();
    }

    public function lista() {
        $citas = Cita::listar();
        require_once 'views/layout/header.php';
        require_once 'views/citas/lista.php';
        require_once 'views/layout/footer.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Cita::guardar($_POST);
        }
        header("Location: index.php?c=cita&a=lista");
        exit;
    }

    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            Cita::eliminar($id);
        }
        header("Location: index.php?c=cita&a=lista");
        exit;
    }
}
