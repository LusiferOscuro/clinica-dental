<?php
// 1. Cargar la configuración de la Base de Datos
require_once 'config/Database.php';

// 2. Obtener el controlador y la acción de la URL (por defecto muestra la landing page)
$controller = isset($_GET['c']) ? strtolower($_GET['c']) : 'landing';
$action     = isset($_GET['a']) ? strtolower($_GET['a']) : 'index';

// 3. Si la ruta es "landing", mostramos la portada moderna
if ($controller === 'landing') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clínica Dental Muelini - Sistema de Gestión</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            body { background-color: #f4f7f6; color: #333; }
            header { background-color: #2c3e50; color: #fff; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .logo { font-size: 22px; font-weight: bold; color: #1abc9c; }
            nav a { color: #fff; text-decoration: none; margin-left: 20px; font-weight: 500; transition: color 0.3s; }
            nav a:hover { color: #1abc9c; }
            .hero { background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=1200') center/cover; height: 65vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #fff; padding: 0 20px; }
            .hero h1 { font-size: 38px; margin-bottom: 15px; }
            .hero p { font-size: 17px; max-width: 600px; margin-bottom: 25px; color: #ecf0f1; }
            .btn-ingresar { background-color: #1abc9c; color: #fff; padding: 12px 30px; font-size: 16px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background 0.3s; }
            .btn-ingresar:hover { background-color: #16a085; }
            .servicios { padding: 40px 5%; text-align: center; }
            .servicios h2 { margin-bottom: 25px; color: #2c3e50; }
            .grid-servicios { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
            .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #1abc9c; }
            footer { background-color: #2c3e50; color: #bdc3c7; text-align: center; padding: 15px; margin-top: 30px; font-size: 14px; }
        </style>
    </head>
    <body>
        <header>
            <div class="logo">🦷 Clínica Muelini</div>
            <nav>
                <a href="index.php">Inicio</a>
                <a href="index.php?c=paciente&a=lista">Pacientes</a>
                <a href="index.php?c=servicio&a=lista">Servicios</a>
                <a href="index.php?c=cita&a=lista">Citas</a>
            </nav>
        </header>

        <section class="hero">
            <h1>Atención Dental de Vanguardia</h1>
            <p>Sistema Web de gestión de pacientes, agenda médica, dentistas y servicios en la nube.</p>
            <a href="index.php?c=paciente&a=lista" class="btn-ingresar">Ingresar al Sistema →</a>
        </section>

        <section class="servicios">
            <h2>Nuestros Servicios</h2>
            <div class="grid-servicios">
                <div class="card">
                    <h3>Odontología General</h3>
                    <p>Revisiones, limpiezas profundas y tratamiento de caries para toda la familia.</p>
                </div>
                <div class="card">
                    <h3>Ortodoncia</h3>
                    <p>Tratamientos de alineación dental con los mejores especialistas del área.</p>
                </div>
                <div class="card">
                    <h3>Cirugía e Implantes</h3>
                    <p>Atención quirúrgica de precisión con tecnología de punta y máxima calidez.</p>
                </div>
            </div>
        </section>

        <footer>
            <p>&copy; 2026 Clínica Odontológica Muelini - Sistema de Gestión PHP & MySQL</p>
        </footer>
    </body>
    </html>
    <?php
    exit();
}

// 4. Si pide una sección del sistema (paciente, cita, servicio), despachamos al controlador MVC correspondiente
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $objetoController = new $controllerName();
        if (method_exists($objetoController, $action)) {
            $objetoController->$action();
        } else {
            echo "La acción <b>{$action}</b> no existe en el controlador <b>{$controllerName}</b>.";
        }
    } else {
        echo "La clase <b>{$controllerName}</b> no se encuentra.";
    }
} else {
    echo "El controlador <b>{$controllerName}</b> no existe.";
}
?>