<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Órdenes de Laboratorio Protésico Externo</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLab">+ Nueva Orden a Laboratorio</button>
</div>

<?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Paciente</th><th>Laboratorio</th><th>Trabajo Protésico</th><th>Pieza / Color</th><th>Envío &rarr; Entrega</th><th>Costo</th><th>Estado</th><th>Acción</th></tr>
            </thead>
            <tbody>
            <?php foreach ($ordenes as $o): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($o['paciente']) ?></strong></td>
                    <td><?= htmlspecialchars($o['laboratorio_nombre']) ?></td>
                    <td><?= htmlspecialchars($o['trabajo_solicitado']) ?></td>
                    <td>Pieza <strong><?= htmlspecialchars($o['pieza_fdi']) ?></strong> (Color <?= htmlspecialchars($o['color_guia']) ?>)</td>
                    <td><?= $o['fecha_envio'] ?> &rarr; <strong><?= $o['fecha_entrega_prometida'] ?></strong></td>
                    <td><?= number_format((float)$o['costo'], 2) ?> Bs</td>
                    <td><span class="badge <?= $o['estado']==='Recibido en Clínica'?'bg-success':'bg-warning text-dark' ?>"><?= htmlspecialchars($o['estado']) ?></span></td>
                    <td>
                        <?php if ($o['estado'] !== 'Recibido en Clínica'): ?>
                            <a href="index.php?controller=laboratorio&action=index&recibir=<?= $o['id'] ?>" class="btn btn-sm btn-outline-success">✓ Marcar Recibido</a>
                        <?php else: ?>
                            <span class="text-success small">Listo para cita</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalLab" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Enviar Trabajo a Laboratorio Dental</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Paciente</label>
                    <select name="paciente_id" class="form-select" required>
                        <?php foreach ($pacientes as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Laboratorio Externo</label><input type="text" name="laboratorio_nombre" class="form-control" value="Lab Dental Prótesis Sur" required></div>
                <div class="col-md-6"><label class="form-label">Trabajo Solicitado</label><input type="text" name="trabajo_solicitado" class="form-control" placeholder="Corona Zirconio / Placa" required></div>
                <div class="col-md-4"><label class="form-label">Pieza FDI</label><input type="text" name="pieza_fdi" class="form-control" value="26" required></div>
                <div class="col-md-4"><label class="form-label">Color Guía Vita</label><input type="text" name="color_guia" class="form-control" value="A2" required></div>
                <div class="col-md-4"><label class="form-label">Costo Lab (Bs)</label><input type="number" step="0.01" name="costo" class="form-control" value="350" required></div>
                <div class="col-md-6"><label class="form-label">Fecha Envío</label><input type="date" name="fecha_envio" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Entrega Prometida</label><input type="date" name="fecha_entrega_prometida" class="form-control" value="<?= date('Y-m-d', strtotime('+5 days')) ?>" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Registrar Orden</button></div>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>