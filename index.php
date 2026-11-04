<?php
session_start();

require_once __DIR__ . '/config/Autoload.php';

// Inicializar estado por defecto si no hay base de datos cargada
if (!isset($_SESSION['servicios'])) {
    $_SESSION['servicios'] = [
        1 => ['id' => 1, 'nombre' => 'Blanqueamiento Dental LED', 'categoria' => 'Estética Dental', 'descripcion' => 'Aclarado estético de manchas profundas en 1 sesión de 45 minutos.', 'precio' => '120.00'],
        2 => ['id' => 2, 'nombre' => 'Diseño de Sonrisa Digital', 'categoria' => 'Estética Dental', 'descripcion' => 'Planificación 3D y colocación de carillas estéticas de porcelana.', 'precio' => '350.00'],
        3 => ['id' => 3, 'nombre' => 'Ortodoncia Invisible', 'categoria' => 'Ortodoncia', 'descripcion' => 'Alineación de dientes con placas transparentes e imperceptibles.', 'precio' => '450.00'],
        4 => ['id' => 4, 'nombre' => 'Brackets de Zafiro', 'categoria' => 'Ortodoncia', 'descripcion' => 'Brackets translúcidos de máxima estética y durabilidad.', 'precio' => '280.00'],
        5 => ['id' => 5, 'nombre' => 'Implantes de Titanio', 'categoria' => 'Implantología', 'descripcion' => 'Reemplazo permanente con corona estética de circonio.', 'precio' => '500.00'],
        6 => ['id' => 6, 'nombre' => 'Endodoncia de Precisión', 'categoria' => 'Endodoncia', 'descripcion' => 'Tratamiento de conducto con tecnología rotatoria sin dolor.', 'precio' => '150.00']
    ];
}

if (!isset($_SESSION['pacientes'])) {
    $_SESSION['pacientes'] = [
        1 => ['id' => 1, 'nombre' => 'Carlos Mendoza', 'telefono' => '+591 71234567', 'email' => 'carlos.mendoza@email.com'],
        2 => ['id' => 2, 'nombre' => 'María Fernández', 'telefono' => '+591 79876543', 'email' => 'maria.f@email.com']
    ];
}

if (!isset($_SESSION['citas'])) {
    $_SESSION['citas'] = [
        101 => ['id' => 101, 'paciente' => 'Carlos Mendoza', 'servicio' => 'Blanqueamiento Dental LED', 'fecha' => '2026-11-05 10:00 AM', 'estado' => 'Confirmada']
    ];
}

// Router M-V-C dinámico
$controladorNombre = isset($_GET['c']) ? ucfirst(strtolower($_GET['c'])) : 'Home';
$accion = isset($_GET['a']) ? strtolower($_GET['a']) : 'index';

$claseController = "controllers\\{$controladorNombre}Controller";
$archivoController = __DIR__ . "/controllers/{$controladorNombre}Controller.php";

if (file_exists($archivoController)) {
    $instancia = new $claseController();
    if (method_exists($instancia, $accion)) {
        $instancia->$accion();
    } else {
        $instancia->index();
    }
} else {
    $home = new controllers\HomeController();
    $home->index();
}
