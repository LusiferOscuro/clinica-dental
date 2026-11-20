<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - Clínica Dental Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">🦷 Clínica Dental Pro</h3>
                    <p class="text-muted small">Sistema Integral Clínico y Administrativo</p>
                </div>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="index.php?controller=auth&action=login">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo Institucional</label>
                        <input type="email" name="email" class="form-control" value="admin@clinicadental.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" name="password" class="form-control" value="Admin123*" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">Ingresar al Sistema</button>
                </form>
                <hr>
                <div class="text-center">
                    <a href="index.php?controller=publico&action=reservar" class="btn btn-outline-success btn-sm">🌐 Ir al Portal Público de Reserva de Citas</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>