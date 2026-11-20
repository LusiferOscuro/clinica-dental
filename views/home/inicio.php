<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Panel Gerencial & Agenda Inteligente</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaCita">+ Agendar Nueva Cita</button>
</div>

<?php if (!empty($msgCita)): ?><div class="alert alert-success"><?= htmlspecialchars($msgCita) ?></div><?php endif; ?>
<?php if (!empty($errCita)): ?><div class="alert alert-danger"><?= htmlspecialchars($errCita) ?></div><?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Pacientes</span>
            <h3 class="fw-bold text-primary mb-0"><?= (int)$totalPacientes ?></h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Citas Agenda</span>
            <h3 class="fw-bold text-success mb-0"><?= (int)$citasHoy ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Ingresos Caja (Bs)</span>
            <h3 class="fw-bold text-dark mb-0"><?= number_format((float)$ingresosMes, 2) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Cuentas por Cobrar</span>
            <h3 class="fw-bold text-warning mb-0"><?= number_format((float)$saldoPorCobrar, 2) ?> Bs</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Stock Crítico</span>
            <h3 class="fw-bold text-danger mb-0"><?= (int)$insumosCriticos ?></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold mb-3">Ingresos vs. Egresos Operativos (Bs)</h6>
            <canvas id="chartFinanzas" height="125"></canvas>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold mb-3">Distribución de Tratamientos</h6>
            <canvas id="chartTratamientos" height="170"></canvas>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-3 mb-4">
    <h6 class="fw-bold mb-3">Agenda Clínica (Con Bloqueo de Sillones y Recordatorio WhatsApp)</h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr><th>Fecha / Hora</th><th>Paciente</th><th>Especialista</th><th>Sillón</th><th>Servicio</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($agenda as $c): 
                $tel = preg_replace('/[^0-9]/', '', $c['telefono_whatsapp'] ?? '');
                $txtWa = urlencode("Hola {$c['paciente']}, te recordamos tu cita odontológica el {$c['fecha']} a las {$c['hora_inicio']} con {$c['odontologo']}.");
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($c['fecha']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($c['hora_inicio']) ?> - <?= htmlspecialchars($c['hora_fin']) ?></small></td>
                    <td><?= htmlspecialchars($c['paciente']) ?><br><small class="text-muted"><?= htmlspecialchars($c['origen']) ?></small></td>
                    <td><?= htmlspecialchars($c['odontologo']) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($c['sillon']) ?></span></td>
                    <td><?= htmlspecialchars($c['servicio'] ?? $c['motivo_consulta']) ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($c['estado']) ?></span></td>
                    <td>
                        <a href="https://wa.me/<?= $tel ?>?text=<?= $txtWa ?>" target="_blank" class="btn btn-sm btn-success" title="Recordatorio WhatsApp">📲 WA</a>
                        <?php if ($c['estado'] !== 'Confirmada'): ?>
                        <a href="index.php?controller=home&action=index&cambiar_estado=Confirmada&cita_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-success" title="Confirmar cita">✓ Confirmar</a>
                        <?php endif; ?>
                        <a href="index.php?controller=home&action=index&cambiar_estado=Atendida&cita_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">✓ Atendida</a>
                        <a href="index.php?controller=odontograma&action=index&paciente_id=<?= $c['paciente_id'] ?>" class="btn btn-sm btn-outline-dark">🦷 Ficha</a>
                        <a href="index.php?controller=home&action=index&eliminar_cita=<?= $c['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar cita" onclick="return confirm('¿Eliminar esta cita de la agenda?');">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm p-3">
    <h6 class="fw-bold mb-2">Bitácora de Auditoría en Tiempo Real (Seguridad)</h6>
    <div class="small text-muted">
        <?php foreach ($auditoria as $a): ?>
            <div>• [<?= $a['fecha'] ?>] <strong><?= htmlspecialchars($a['nombre_completo'] ?? 'Portal Público') ?></strong> &rarr; <span class="badge bg-secondary"><?= $a['accion'] ?></span> en <code><?= $a['tabla_afectada'] ?></code>: <?= htmlspecialchars($a['detalle']) ?></div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Nueva Cita -->
<div class="modal fade" id="modalNuevaCita" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="nueva_cita" value="1">
            <div class="modal-header"><h5 class="modal-title">Agendar Cita (Validación de Sillón/Doctor)</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Paciente</label>
                    <select name="paciente_id" class="form-select" required>
                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos'] . ' (CI: ' . $p['ci_nit'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Odontólogo</label>
                    <select name="odontologo_id" class="form-select" required>
                        <?php foreach ($doctores as $d): ?><option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre_completo']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sillón / Consultorio</label>
                    <select name="sillon_id" class="form-select" required>
                        <?php foreach ($sillones as $s): ?><option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Servicio</label>
                    <select name="servicio_id" class="form-select">
                        <?php foreach ($servicios as $sv): ?><option value="<?= $sv['id'] ?>"><?= htmlspecialchars($sv['nombre'] . ' (' . $sv['precio_base'] . ' Bs)') ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Fecha</label><input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Hora Inicio</label><input type="time" name="hora_inicio" class="form-control" value="15:00" required></div>
                <div class="col-12"><label class="form-label">Motivo</label><input type="text" name="motivo_consulta" class="form-control" value="Consulta odontológica" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar Turno</button></div>
        </form>
    </div>
</div>

<script>
new Chart(document.getElementById('chartFinanzas'), {
    type: 'bar',
    data: {
        labels: ['Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov'],
        datasets: [
            { label: 'Ingresos (Bs)', data: [18500, 21200, 19800, 24500, 26100, <?= max(28900, (float)$ingresosMes) ?>], backgroundColor: '#0d6efd' },
            { label: 'Egresos / Lab (Bs)', data: [5200, 6100, 5800, 7200, 6900, <?= max(7400, (float)$egresosMes) ?>], backgroundColor: '#dc3545' }
        ]
    }
});
new Chart(document.getElementById('chartTratamientos'), {
    type: 'doughnut',
    data: {
        labels: ['Operatoria/Resinas', 'Ortodoncia', 'Endodoncia', 'Cirugía', 'Prótesis'],
        datasets: [{ data: [38, 25, 15, 12, 10], backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'] }]
    }
});
</script>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
