<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica Dental - Pacientes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; background: #333; padding: 10px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?c=paciente&a=lista">Pacientes</a>
        <a href="index.php?c=servicio&a=lista">Servicios</a>
        <a href="index.php?c=cita&a=lista">Citas</a>
    </nav>
    <h2>Lista de Pacientes</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Teléfono</th><th>Email</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($pacientes)): ?>
                <?php foreach ($pacientes as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No hay pacientes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>