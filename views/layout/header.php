<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental Piedra - Odontología Especializada</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-info" href="index.php">
            <i class="bi bi-hospital me-2"></i>Clínica Dental Piedra
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php"><i class="bi bi-house-door me-1"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php?c=paciente&a=lista"><i class="bi bi-people me-1"></i> Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php?c=servicio&a=lista"><i class="bi bi-journal-medical me-1"></i> Tratamientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php?c=cita&a=lista"><i class="bi bi-calendar-event me-1"></i> Citas</a>
                </li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn btn-qr-custom text-white" href="index.php?c=pago">
                        <i class="bi bi-qr-code-scan me-1"></i> Pagar con QR
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main>
