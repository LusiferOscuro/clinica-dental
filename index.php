<?php
require_once "config/Database.php";

$controlador = $_GET['c'] ?? 'paciente';
$accion      = $_GET['a'] ?? 'lista';

$nombreControlador = ucfirst(strtolower($controlador)) . "Controller";
$archivoControlador = "controllers/" . $nombreControlador . ".php";

if (file_exists($archivoControlador)) {
    require_once $archivoControlador;
    if (class_exists($nombreControlador)) {
        $controladorObj = new $nombreControlador();
        if (method_exists($controladorObj, $accion)) {
            $controladorObj->$accion();
        } else {
            die("Error: El método '$accion' no existe en $nombreControlador.");
        }
    } else {
        die("Error: La clase '$nombreControlador' no se encontró.");
    }
} else {
    die("Error: El archivo del controlador '$archivoControlador' no existe.");
}
?>