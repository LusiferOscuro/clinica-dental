<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-journal-medical text-primary me-2"></i>Tratamientos Médicos</h3>
            <p class="text-muted small mb-0">Catálogo de especialidades ofrecidas en Clínica Dental Piedra.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-3 shadow-sm" onclick="modalNuevoServicio()">
            <i class="bi bi-plus-lg me-1"></i> Agregar Tratamiento
        </button>
    </div>

    <div class="card custom-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Tratamiento</th>
                        <th>Especialidad</th>
                        <th>Descripción Breve</th>
                        <th>Precio ($)</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($servicios)): ?>
                        <?php foreach ($servicios as $s): ?>
                            <tr>
                                <td class="fw-bold text-secondary">#<?php echo $s['id']; ?></td>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($s['nombre']); ?></td>
                                <td><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1.5"><?php echo htmlspecialchars($s['categoria'] ?? 'General'); ?></span></td>
                                <td class="small text-muted"><?php echo htmlspecialchars($s['descripcion']); ?></td>
                                <td class="fw-bold text-success">$<?php echo htmlspecialchars($s['precio']); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning me-1 fw-semibold" 
                                            onclick="modalEditarServicio(<?php echo $s['id']; ?>, '<?php echo addslashes($s['nombre']); ?>', '<?php echo addslashes($s['categoria'] ?? 'General'); ?>', '<?php echo addslashes($s['descripcion']); ?>', '<?php echo addslashes($s['precio']); ?>')">
                                        <i class="bi bi-pencil me-1"></i>Editar
                                    </button>
                                    <a href="index.php?c=servicio&a=eliminar&id=<?php echo $s['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger fw-semibold" 
                                       onclick="return confirm('¿Confirms eliminar este tratamiento del catálogo?');">
                                        <i class="bi bi-trash me-1"></i>Quitar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No existen tratamientos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form action="index.php?c=servicio&a=guardar" method="POST">
        <input type="hidden" name="id" id="s_id">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title fw-bold" id="modalServicioTitulo"><i class="bi bi-plus-circle me-2"></i>Nuevo Tratamiento</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre del Tratamiento</label>
            <input type="text" name="nombre" id="s_nombre" class="form-control" required placeholder="Ej. Ortodoncia Invisible">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Especialidad</label>
            <select name="categoria" id="s_categoria" class="form-select">
                <option value="Estética Dental">Estética Dental</option>
                <option value="Ortodoncia">Ortodoncia</option>
                <option value="Implantología">Implantología</option>
                <option value="Endodoncia">Endodoncia</option>
                <option value="General">General</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Descripción Breve</label>
            <textarea name="descripcion" id="s_descripcion" class="form-control" rows="2" placeholder="Detalles del tratamiento..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Precio ($)</label>
            <input type="number" step="0.01" name="precio" id="s_precio" class="form-control" required placeholder="Ej. 150.00">
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary rounded-2 fw-bold">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function modalNuevoServicio() {
    document.getElementById('s_id').value = '';
    document.getElementById('s_nombre').value = '';
    document.getElementById('s_categoria').value = 'General';
    document.getElementById('s_descripcion').value = '';
    document.getElementById('s_precio').value = '';
    document.getElementById('modalServicioTitulo').innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nuevo Tratamiento';
    new bootstrap.Modal(document.getElementById('modalServicio')).show();
}

function modalEditarServicio(id, nombre, categoria, descripcion, precio) {
    document.getElementById('s_id').value = id;
    document.getElementById('s_nombre').value = nombre;
    document.getElementById('s_categoria').value = categoria;
    document.getElementById('s_descripcion').value = descripcion;
    document.getElementById('s_precio').value = precio;
    document.getElementById('modalServicioTitulo').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Tratamiento';
    new bootstrap.Modal(document.getElementById('modalServicio')).show();
}
</script>
