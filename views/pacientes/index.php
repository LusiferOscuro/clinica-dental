<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Pacientes & Historia Clínica (Anamnesis)</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPaciente">+ Nuevo Paciente</button>
</div>

<?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<form method="GET" class="row g-2 mb-3">
    <input type="hidden" name="controller" value="pacientes">
    <input type="hidden" name="action" value="index">
    <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="Buscar por nombre, apellido o CI..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"></div>
    <div class="col-auto"><button class="btn btn-outline-primary">Buscar</button></div>
</form>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>CI/NIT</th><th>Paciente</th><th>WhatsApp</th><th>Alertas Médicas (Anamnesis)</th><th>Acciones Clínicas</th></tr>
            </thead>
            <tbody>
            <?php foreach ($pacientes as $p): 
                $tieneAlerta = ($p['alergias'] !== 'Ninguna' || $p['condiciones_medicas'] !== 'Ninguna');
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['ci_nit']) ?></strong></td>
                    <td><?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?><br><small class="text-muted">Nac: <?= htmlspecialchars($p['fecha_nacimiento']) ?></small></td>
                    <td><?= htmlspecialchars($p['telefono_whatsapp']) ?></td>
                    <td>
                        <?php if ($tieneAlerta): ?>
                            <span class="badge bg-danger">⚠️ Alergia: <?= htmlspecialchars($p['alergias']) ?> | Cond: <?= htmlspecialchars($p['condiciones_medicas']) ?></span>
                        <?php else: ?>
                            <span class="badge bg-success">Sin antecedentes de riesgo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?controller=odontograma&action=index&paciente_id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">🦷 Odontograma</a>
                        <a href="index.php?controller=documentos&action=consentimiento&paciente_id=<?= $p['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary">📄 Consentimiento</a>
                        <a href="index.php?controller=documentos&action=receta&paciente_id=<?= $p['id'] ?>" target="_blank" class="btn btn-sm btn-outline-success">💊 Receta</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalPaciente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Registrar Paciente & Ficha Anamnesis</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">CI / NIT</label><input type="text" name="ci_nit" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Nombres</label><input type="text" name="nombres" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Fecha Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Sexo</label><select name="sexo" class="form-select"><option value="M">Masculino</option><option value="F">Femenino</option></select></div>
                <div class="col-md-4"><label class="form-label">WhatsApp</label><input type="text" name="telefono_whatsapp" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control"></div>
                <hr>
                <h6 class="text-danger fw-bold mb-0">Anamnesis / Antecedentes Médicos Críticos</h6>
                <div class="col-md-4"><label class="form-label">Alergias (Anestesia/Penicilina)</label><input type="text" name="alergias" class="form-control" placeholder="Ninguna"></div>
                <div class="col-md-4"><label class="form-label">Enfermedades Sistémicas</label><input type="text" name="condiciones_medicas" class="form-control" placeholder="Diabetes, Hipertensión..."></div>
                <div class="col-md-4"><label class="form-label">Medicación Actual</label><input type="text" name="medicacion_actual" class="form-control" placeholder="Ninguna"></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar Ficha Clínica</button></div>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>