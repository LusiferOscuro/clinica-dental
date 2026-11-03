<?php
require_once "config/Database.php";

$controller = isset($_GET['c']) ? strtolower($_GET['c']) : 'paciente';
$action = isset($_GET['a']) ? strtolower($_GET['a']) : 'lista';

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = "controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $object = new $controllerName();
        if (method_exists($object, $action)) {
            $object->$action();
        } else {
            if (method_exists($object, 'lista')) {
                $object->lista();
            } else {
                echo "Error: La acción no existe en el controlador.";
            }
        }
    } else {
        echo "Error: La clase del controlador no existe.";
    }
} else {
    echo "Error: El archivo del controlador no existe.";
}
?>