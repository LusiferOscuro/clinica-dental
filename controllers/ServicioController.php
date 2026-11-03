<?php
require_once "models/Servicio.php";

class ServicioController {
    public function lista() {
        $servicio = new Servicio();
        $servicios = method_exists($servicio, 'obtener') ? $servicio->obtener() : [];
        require_once "views/servicio/lista.php";
    }

    public function index() {
        $this->lista();
    }

    public function formulario() {
        require_once "views/servicio/formulario.php";
    }
}
?>