<?php
require_once "models/Servicio.php";

class ServicioController {
    public function listar() {
        $servicios = Servicio::obtenerTodos();
        require "views/servicio/lista.php";
    }

    public function crear() {
        require "views/servicio/formulario.php";
    }

    public function guardar() {
        Servicio::agregar($_POST['nombre'], $_POST['descripcion'], $_POST['precio']);
        header("Location: index.php?c=servicio");
        exit;
    }

    public function eliminar() {
        Servicio::eliminar($_GET['id']);
        header("Location: index.php?c=servicio");
        exit;
    }
}
?>