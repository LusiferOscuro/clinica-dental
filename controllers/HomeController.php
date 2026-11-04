<?php
namespace controllers;

use models\Servicio;

class HomeController {
    public function index() {
        $servicios = Servicio::listar();
        require_once 'views/layout/header.php';
        require_once 'views/home/inicio.php';
        require_once 'views/layout/footer.php';
    }
}
