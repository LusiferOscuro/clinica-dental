<?php $ctrlActual = strtolower($_GET['controller'] ?? 'home'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Dental Pro — Siglo XXI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f1f5f9; }
        .sidebar { min-height: 100vh; background: #0f172a; color: #fff; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 9px 14px; border-radius: 8px; margin-bottom: 4px; font-size: 0.92rem; }
        .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #38bdf8; font-weight: 600; }
        .nav-section { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin: 14px 0 6px 10px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar p-3">
            <h5 class="fw-bold text-info mb-1">🦷 Dental Pro XXI</h5>
            <div class="small text-secondary mb-3">
                <strong><?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></strong><br>
                <span class="badge bg-info text-dark"><?= strtoupper($_SESSION['rol'] ?? '') ?></span>
            </div>

            <div class="nav-section">Gestión & Agenda</div>
            <a href="index.php?controller=home&action=index" class="<?= $ctrlActual==='home'?'active':'' ?>">📊 Dashboard & Agenda</a>
            <a href="index.php?controller=pacientes&action=index" class="<?= $ctrlActual==='pacientes'?'active':'' ?>">👥 Pacientes & Anamnesis</a>

            <div class="nav-section">Clínica Odontológica</div>
            <a href="index.php?controller=odontograma&action=index" class="<?= $ctrlActual==='odontograma'?'active':'' ?>">🦷 Odontograma FDI 3D/2D</a>
            <a href="index.php?controller=presupuestos&action=index" class="<?= $ctrlActual==='presupuestos'?'active':'' ?>">📋 Planes & Presupuestos</a>
            <a href="index.php?controller=laboratorio&action=index" class="<?= $ctrlActual==='laboratorio'?'active':'' ?>">🧪 Laboratorio Protésico</a>

            <div class="nav-section">Finanzas & Kardex</div>
            <a href="index.php?controller=caja&action=index" class="<?= $ctrlActual==='caja'?'active':'' ?>">💵 Caja, Abonos & Recibos</a>
            <a href="index.php?controller=inventario&action=index" class="<?= $ctrlActual==='inventario'?'active':'' ?>">📦 Inventario & Kardex</a>

            <div class="nav-section">Documentos & Portal</div>
            <a href="index.php?controller=documentos&action=consentimiento&paciente_id=1" target="_blank">📄 Consentimiento Legal</a>
            <a href="index.php?controller=documentos&action=receta&paciente_id=1" target="_blank">💊 Receta Médica PDF</a>
            <a href="index.php?controller=publico&action=reservar" target="_blank">🌐 Portal Citas Online</a>

            <hr class="border-secondary">
            <a href="index.php?controller=auth&action=logout" class="text-danger">🚪 Cerrar Sesión</a>
        </nav>
        <main class="col-md-10 p-4">