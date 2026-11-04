<?php
namespace controllers;

class PagoController {
    public function index() {
        require_once 'views/layout/header.php';
        require_once 'views/pagos/qr.php';
        require_once 'views/layout/footer.php';
    }
}
