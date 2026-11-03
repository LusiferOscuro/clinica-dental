<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica Dental - Citas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; background: #333; padding: 10px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #17a2b8; color: white; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?c=paciente&a=lista">Pacientes</a>
        <a href="index.php?c=servicio&a=lista">Servicios</a>
        <a href="index.php?c=cita&a=lista">Citas</a>
    </nav>
    <h2>Lista de Citas</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Paciente</th><th>Servicio</th><th>Fecha</th><th>Hora</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($citas)): ?>
                <?php foreach ($citas as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['paciente'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['servicio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['fecha'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['hora'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay citas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>