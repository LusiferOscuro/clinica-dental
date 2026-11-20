<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Cita Online - Clínica Dental Pro XXI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="fw-bold text-primary mb-0">🦷 Reserva tu Cita Online</h3>
                    <a href="index.php?controller=auth&action=login" class="btn btn-sm btn-outline-secondary">Acceso Staff</a>
                </div>
                <p class="text-muted mb-4">Elige tu especialista, fecha y horario disponible sin hacer filas.</p>

                <?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

                <form method="POST" class="row g-3">
                    <div class="col-md-4"><label class="form-label">CI / Documento</label><input type="text" name="ci_nit" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Nombres</label><input type="text" name="nombres" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Fecha Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label">Sexo</label><select name="sexo" class="form-select"><option value="M">Masculino</option><option value="F">Femenino</option></select></div>
                    <div class="col-md-4"><label class="form-label">WhatsApp</label><input type="text" name="telefono" class="form-control" placeholder="+591 7XXXXXXX" required></div>
                    <div class="col-md-6">
                        <label class="form-label">Especialista</label>
                        <select name="odontologo_id" class="form-select" required>
                            <?php foreach ($doctores as $d): ?><option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre_completo'] . ' (' . $d['especialidad'] . ')') ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Servicio / Motivo</label>
                        <select name="servicio_id" class="form-select" required>
                            <?php foreach ($servicios as $s): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre'] . ' - ' . $s['precio_base'] . ' Bs') ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Fecha de Cita</label><input type="date" name="fecha" class="form-control" min="<?= date('Y-m-d') ?>" required></div>
                    <div class="col-md-6"><label class="form-label">Hora Preferida</label><input type="time" name="hora_inicio" class="form-control" min="08:00" max="19:30" required></div>
                    <div class="col-12"><label class="form-label">¿Alguna alergia o condición médica? (Opcional)</label><input type="text" name="alergias" class="form-control" placeholder="Ej. Alergia a penicilina / Ninguna"></div>
                    <div class="col-12"><button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Confirmar Reserva Online</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>