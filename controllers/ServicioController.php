<?php
namespace controllers;

use models\Servicio;

class ServicioController {

    public function index() {
        $this->lista();
    }

    public function lista() {
        $servicios = Servicio::listar();
        require_once 'views/layout/header.php';
        require_once 'views/servicios/lista.php';
        require_once 'views/layout/footer.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Servicio::guardar($_POST);
        }
        header("Location: index.php?c=servicio&a=lista");
        exit;
    }

    public function eliminar() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            Servicio::eliminar($id);
        }
        header("Location: index.php?c=servicio&a=lista");
        exit;
    }
}
