<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Planes de Tratamiento & Presupuestos</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPresupuesto">+ Nuevo Plan de Tratamiento</button>
</div>

<?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="row g-3">
<?php foreach ($presupuestos as $pr): 
    $saldo = $pr['total'] - $pr['abonado'];
    $pct = $pr['total'] > 0 ? round(($pr['abonado'] / $pr['total']) * 100) : 0;
?>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 h-100">
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($pr['titulo']) ?></h5>
                <span class="badge bg-info text-dark"><?= htmlspecialchars($pr['estado']) ?></span>
            </div>
            <p class="small text-muted mb-2">Paciente: <strong><?= htmlspecialchars($pr['paciente']) ?></strong></p>

            <div class="d-flex justify-content-between small mb-1">
                <span>Total: <strong><?= number_format($pr['total'], 2) ?> Bs</strong></span>
                <span class="text-success">Abonado: <strong><?= number_format($pr['abonado'], 2) ?> Bs</strong></span>
                <span class="text-danger">Saldo: <strong><?= number_format($saldo, 2) ?> Bs</strong></span>
            </div>
            <div class="progress mb-3" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: <?= $pct ?>%"></div>
            </div>

            <table class="table table-sm small mb-0">
                <thead><tr><th>Pieza</th><th>Tratamiento</th><th>Costo</th><th>Estado</th></tr></thead>
                <tbody>
                <?php foreach ($pr['items'] as $it): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($it['pieza_fdi']) ?></strong></td>
                        <td><?= htmlspecialchars($it['tratamiento']) ?></td>
                        <td><?= number_format($it['costo'], 2) ?> Bs</td>
                        <td>
                            <?php if ($it['estado_item'] === 'Terminado'): ?>
                                <span class="badge bg-success">Terminado</span>
                            <?php else: ?>
                                <a href="index.php?controller=presupuestos&action=index&completar_item=<?= $it['id'] ?>" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size:0.75rem;">Marcar Listo</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="modal fade" id="modalPresupuesto" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="crear_presupuesto" value="1">
            <div class="modal-header"><h5 class="modal-title">Crear Plan de Tratamiento</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Paciente</label>
                    <select name="paciente_id" class="form-select" required>
                        <?php foreach ($pacientes as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Título del Plan</label><input type="text" name="titulo" class="form-control" placeholder="Ej. Rehabilitación Sector Posterior" required></div>
                <div class="col-md-4"><label class="form-label">Pieza FDI</label><input type="text" name="pieza_fdi" class="form-control" placeholder="Ej. 16" required></div>
                <div class="col-md-8"><label class="form-label">Tratamiento</label><input type="text" name="tratamiento" class="form-control" placeholder="Ej. Endodoncia + Corona" required></div>
                <div class="col-12"><label class="form-label">Costo Total (Bs)</label><input type="number" step="0.01" name="costo" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar Presupuesto</button></div>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>