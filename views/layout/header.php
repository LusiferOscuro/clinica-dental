<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f7fa; }
        .sidebar { min-height: 100vh; background: #0f172a; color: #fff; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 10px 16px; border-radius: 8px; margin-bottom: 4px; }
        .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #fff; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar p-3">
            <h5 class="fw-bold text-info mb-4">🦷 Dental Pro</h5>
            <div class="small text-secondary mb-3">
                Usuario: <strong><?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></strong><br>
                Rol: <span class="badge bg-info text-dark"><?= strtoupper($_SESSION['rol'] ?? '') ?></span>
            </div>
            <a href="index.php?controller=home&action=index">📊 Dashboard Gerencial</a>
            <a href="index.php?controller=odontograma&action=index&paciente_id=1">🦷 Odontograma FDI (Adulto/Niño)</a>
            <a href="index.php?controller=documentos&action=consentimiento&paciente_id=1" target="_blank">📄 Consentimiento Informado</a>
            <a href="index.php?controller=documentos&action=receta&paciente_id=1" target="_blank">💊 Receta Médica PDF</a>
            <a href="index.php?controller=publico&action=reservar" target="_blank">🌐 Portal Reserva Online</a>
            <hr class="border-secondary">
            <a href="index.php?controller=auth&action=logout" class="text-danger">🚪 Cerrar Sesión</a>
        </nav>
        <main class="col-md-10 p-4">
