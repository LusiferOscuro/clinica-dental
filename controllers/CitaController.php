<?php
require_once "models/Cita.php";

class CitaController {
    public function lista() {
        $modelo = new Cita();
        $citas = $modelo->getCitas();
        require_once "views/citas/lista.php";
    }
}
?>