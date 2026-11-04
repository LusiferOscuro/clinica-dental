<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-calendar-check text-primary me-2"></i>Agenda de Citas Médicas</h3>
            <p class="text-muted small mb-0">Gestión de consultas programadas de pacientes.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 shadow-sm" onclick="modalNuevaCita()">
            <i class="bi bi-calendar-plus me-1"></i> Agendar Nueva Cita
        </button>
    </div>

    <div class="card custom-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>N° Cita</th>
                        <th>Nombre Paciente</th>
                        <th>Tratamiento</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas)): ?>
                        <?php foreach ($citas as $c): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?php echo $c['id']; ?></td>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($c['paciente']); ?></td>
                                <td><?php echo htmlspecialchars($c['servicio']); ?></td>
                                <td><i class="bi bi-clock me-1 text-muted"></i><?php echo htmlspecialchars($c['fecha']); ?></td>
                                <td>
                                    <span class="badge <?php echo $c['estado'] === 'Confirmada' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'; ?> px-2.5 py-1.5">
                                        <?php echo htmlspecialchars($c['estado']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1 fw-semibold" 
                                            onclick="modalEditarCita(<?php echo $c['id']; ?>, '<?php echo addslashes($c['paciente']); ?>', '<?php echo addslashes($c['servicio']); ?>', '<?php echo addslashes($c['fecha']); ?>', '<?php echo addslashes($c['estado']); ?>')">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </button>
                                    <a href="index.php?c=cita&a=eliminar&id=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger fw-semibold" 
                                       onclick="return confirm('¿Desea cancelar esta cita programada?');">
                                        <i class="bi bi-trash me-1"></i>Quitar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No existen citas registradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form action="index.php?c=cita&a=guardar" method="POST">
        <input type="hidden" name="id" id="c_id">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title fw-bold" id="modalCitaTitulo"><i class="bi bi-calendar-plus me-2"></i>Agendar Cita</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Paciente</label>
            <input type="text" name="paciente" id="c_paciente" class="form-control" required placeholder="Ej. Carlos Mendoza">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Tratamiento Asignado</label>
            <input type="text" name="servicio" id="c_servicio" class="form-control" required placeholder="Ej. Blanqueamiento Dental LED">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Fecha y Hora</label>
            <input type="text" name="fecha" id="c_fecha" class="form-control" required placeholder="Ej. 2026-11-05 10:00 AM">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Estado de Reserva</label>
            <select name="estado" id="c_estado" class="form-select">
                <option value="Confirmada">Confirmada</option>
                <option value="Pendiente">Pendiente</option>
            </select>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary rounded-2 fw-bold">Guardar Cita</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function modalNuevaCita() {
    document.getElementById('c_id').value = '';
    document.getElementById('c_paciente').value = '';
    document.getElementById('c_servicio').value = '';
    document.getElementById('c_fecha').value = '';
    document.getElementById('c_estado').value = 'Confirmada';
    document.getElementById('modalCitaTitulo').innerHTML = '<i class="bi bi-calendar-plus me-2"></i>Agendar Cita';
    new bootstrap.Modal(document.getElementById('modalCita')).show();
}

function modalEditarCita(id, paciente, servicio, fecha, estado) {
    document.getElementById('c_id').value = id;
    document.getElementById('c_paciente').value = paciente;
    document.getElementById('c_servicio').value = servicio;
    document.getElementById('c_fecha').value = fecha;
    document.getElementById('c_estado').value = estado;
    document.getElementById('modalCitaTitulo').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Cita';
    new bootstrap.Modal(document.getElementById('modalCita')).show();
}
</script>
