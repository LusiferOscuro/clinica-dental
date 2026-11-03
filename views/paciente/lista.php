<?php require "views/plantilla/encabezado.php"; ?>
<h2>Lista de Pacientes</h2>
<a href="index.php?c=paciente&a=crear">Nuevo Paciente</a>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>CI</th>
        <th>Teléfono</th>
    </tr>
    <?php foreach ($pacientes as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nombre'] ?></td>
        <td><?= $p['apellido'] ?></td>
        <td><?= $p['ci'] ?></td>
        <td><?= $p['telefono'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require "views/plantilla/pie.php"; ?>