<?php
require_once __DIR__ . '/config/Autoload.php';

$controllerName = ucfirst(strtolower($_GET['controller'] ?? 'home')) . 'Controller';
$action = $_GET['action'] ?? 'index';

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        echo "Acción no encontrada.";
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado.";
}
