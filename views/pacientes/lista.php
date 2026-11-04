<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-people text-primary me-2"></i>Gestión de Pacientes</h3>
            <p class="text-muted small mb-0">Listado de pacientes registrados en Clínica Dental Piedra.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 shadow-sm" onclick="modalNuevoPaciente()">
            <i class="bi bi-person-plus-fill me-1"></i> Agregar Paciente
        </button>
    </div>

    <div class="card custom-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Paciente</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $p): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?php echo $p['id']; ?></td>
                                <td class="fw-semibold text-dark"><?php echo htmlspecialchars($p['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($p['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($p['email']); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1 fw-semibold" 
                                            onclick="modalEditarPaciente(<?php echo $p['id']; ?>, '<?php echo addslashes($p['nombre']); ?>', '<?php echo addslashes($p['telefono']); ?>', '<?php echo addslashes($p['email']); ?>')">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </button>
                                    <a href="index.php?c=paciente&a=eliminar&id=<?php echo $p['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger fw-semibold" 
                                       onclick="return confirm('¿Confirma eliminar este registro de paciente?');">
                                        <i class="bi bi-trash me-1"></i>Quitar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No se encontraron pacientes registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Modal CRUD Pacientes -->
<div class="modal fade" id="modalPaciente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form action="index.php?c=paciente&a=guardar" method="POST">
        <input type="hidden" name="id" id="p_id">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title fw-bold" id="modalPacienteTitulo"><i class="bi bi-person-plus me-2"></i>Nuevo Paciente</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre Completo</label>
            <input type="text" name="nombre" id="p_nombre" class="form-control" required placeholder="Ej. Juan Pérez">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Teléfono de Contacto</label>
            <input type="text" name="telefono" id="p_telefono" class="form-control" placeholder="Ej. +591 70000000">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Correo Electrónico</label>
            <input type="email" name="email" id="p_email" class="form-control" placeholder="Ej. paciente@email.com">
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary rounded-2 fw-bold">Guardar Datos</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function modalNuevoPaciente() {
    document.getElementById('p_id').value = '';
    document.getElementById('p_nombre').value = '';
    document.getElementById('p_telefono').value = '';
    document.getElementById('p_email').value = '';
    document.getElementById('modalPacienteTitulo').innerHTML = '<i class="bi bi-person-plus me-2"></i>Nuevo Paciente';
    new bootstrap.Modal(document.getElementById('modalPaciente')).show();
}

function modalEditarPaciente(id, nombre, telefono, email) {
    document.getElementById('p_id').value = id;
    document.getElementById('p_nombre').value = nombre;
    document.getElementById('p_telefono').value = telefono;
    document.getElementById('p_email').value = email;
    document.getElementById('modalPacienteTitulo').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Paciente';
    new bootstrap.Modal(document.getElementById('modalPaciente')).show();
}
</script>
