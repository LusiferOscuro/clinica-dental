<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clínica Dental - Servicios</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; background: #333; padding: 10px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #28a745; color: white; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?c=paciente&a=lista">Pacientes</a>
        <a href="index.php?c=servicio&a=lista">Servicios</a>
        <a href="index.php?c=cita&a=lista">Citas</a>
    </nav>
    <h2>Lista de Servicios</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Precio</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($servicios)): ?>
                <?php foreach ($servicios as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($s['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($s['precio'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No hay servicios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>