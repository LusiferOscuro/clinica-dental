<?php
require_once "models/Servicio.php";

class ServicioController {
    public function lista() {
        $modelo = new Servicio();
        $servicios = $modelo->obtener();
        require_once "views/servicios/lista.php";
    }
}
?>