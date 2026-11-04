<?php
spl_autoload_register(function ($clase) {
    // Normalizar separadores de namespace a rutas de archivo
    $ruta = __DIR__ . '/../' . str_replace('\\', '/', $clase) . '.php';
    if (file_exists($ruta)) {
        require_once $ruta;
    }
});
