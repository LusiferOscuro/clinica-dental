<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Caja, Cobros por Cuotas & Arqueo</h3>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCaja">+ Registrar Ingreso / Abono / Egreso</button>
</div>

<?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm p-3"><span class="text-muted small">Total Ingresos</span><h3 class="fw-bold text-success mb-0"><?= number_format((float)$totalIngresos, 2) ?> Bs</h3></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm p-3"><span class="text-muted small">Total Egresos</span><h3 class="fw-bold text-danger mb-0"><?= number_format((float)$totalEgresos, 2) ?> Bs</h3></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm p-3"><span class="text-muted small">Saldo Neto en Caja</span><h3 class="fw-bold text-primary mb-0"><?= number_format((float)($totalIngresos - $totalEgresos), 2) ?> Bs</h3></div></div>
</div>

<div class="card border-0 shadow-sm p-3">
    <h6 class="fw-bold mb-3">Movimientos de Caja y Comprobantes</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>#</th><th>Fecha</th><th>Tipo</th><th>Categoría / Descripción</th><th>Paciente</th><th>Método</th><th>Monto</th><th>Comprobante</th></tr>
            </thead>
            <tbody>
            <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td><small><?= $m['fecha_movimiento'] ?></small></td>
                    <td><span class="badge <?= $m['tipo']==='Ingreso'?'bg-success':'bg-danger' ?>"><?= $m['tipo'] ?></span></td>
                    <td><strong><?= htmlspecialchars($m['categoria']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($m['descripcion']) ?></small></td>
                    <td><?= htmlspecialchars($m['paciente'] ?? '-') ?></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($m['metodo_pago']) ?></span></td>
                    <td class="fw-bold"><?= number_format((float)$m['monto'], 2) ?> Bs</td>
                    <td><a href="index.php?controller=caja&action=recibo&id=<?= $m['id'] ?>" target="_blank" class="btn btn-sm btn-outline-dark">🧾 Recibo PDF</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCaja" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Registrar Movimiento de Caja</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select"><option value="Ingreso">Ingreso (Cobro / Abono)</option><option value="Egreso">Egreso (Gasto / Lab)</option></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Método de Pago</label>
                    <select name="metodo_pago" class="form-select"><option value="QR">QR Simple</option><option value="Efectivo">Efectivo</option><option value="Transferencia">Transferencia</option><option value="Tarjeta">Tarjeta</option></select>
                </div>
                <div class="col-12">
                    <label class="form-label">Abonar a Plan de Tratamiento (Opcional)</label>
                    <select name="presupuesto_id" class="form-select">
                        <option value="">-- Pago directo sin plan --</option>
                        <?php foreach ($presupuestos as $pr): ?>
                            <option value="<?= $pr['id'] ?>"><?= htmlspecialchars($pr['paciente'] . ' - ' . $pr['titulo'] . ' (Saldo: ' . ($pr['total']-$pr['abonado']) . ' Bs)') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Categoría</label><input type="text" name="categoria" class="form-control" value="Abono Tratamiento" required></div>
                <div class="col-md-6"><label class="form-label">Monto (Bs)</label><input type="number" step="0.01" name="monto" class="form-control" required></div>
                <div class="col-12"><label class="form-label">Descripción / Glosa</label><input type="text" name="descripcion" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">Registrar y Emitir Recibo</button></div>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>