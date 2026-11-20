<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Inventario & Kardex de Insumos Dentales</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInsumo">+ Nuevo Insumo</button>
</div>

<?php if (!empty($mensaje)): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="card border-0 shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>Código</th><th>Insumo</th><th>Categoría</th><th>Stock Actual</th><th>Mínimo</th><th>Vencimiento</th><th>Estado</th><th>Kardex Rápido</th></tr>
            </thead>
            <tbody>
            <?php foreach ($insumos as $i): 
                $critico = $i['stock_actual'] <= $i['stock_minimo'];
            ?>
                <tr class="<?= $critico ? 'table-danger' : '' ?>">
                    <td><code><?= htmlspecialchars($i['codigo']) ?></code></td>
                    <td><strong><?= htmlspecialchars($i['nombre']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($i['unidad_medida']) ?></small></td>
                    <td><?= htmlspecialchars($i['categoria']) ?></td>
                    <td class="fs-5 fw-bold"><?= (int)$i['stock_actual'] ?></td>
                    <td><?= (int)$i['stock_minimo'] ?></td>
                    <td><?= htmlspecialchars($i['fecha_vencimiento']) ?></td>
                    <td><?= $critico ? '<span class="badge bg-danger">⚠️ Reponer Stock</span>' : '<span class="badge bg-success">Óptimo</span>' ?></td>
                    <td>
                        <a href="index.php?controller=inventario&action=index&ajustar=1&id=<?= $i['id'] ?>" class="btn btn-sm btn-outline-success">+1 Entrada</a>
                        <a href="index.php?controller=inventario&action=index&ajustar=-1&id=<?= $i['id'] ?>" class="btn btn-sm btn-outline-danger">-1 Consumo</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalInsumo" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="nuevo_insumo" value="1">
            <div class="modal-header"><h5 class="modal-title">Registrar Insumo en Kardex</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">Código</label><input type="text" name="codigo" class="form-control" placeholder="INS-05" required></div>
                <div class="col-md-8"><label class="form-label">Nombre del Insumo</label><input type="text" name="nombre" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Categoría</label><input type="text" name="categoria" class="form-control" value="Operatoria"></div>
                <div class="col-md-6"><label class="form-label">Unidad</label><input type="text" name="unidad_medida" class="form-control" value="Caja"></div>
                <div class="col-md-4"><label class="form-label">Stock Actual</label><input type="number" name="stock_actual" class="form-control" value="5" required></div>
                <div class="col-md-4"><label class="form-label">Stock Mínimo</label><input type="number" name="stock_minimo" class="form-control" value="3" required></div>
                <div class="col-md-4"><label class="form-label">Costo Unit. (Bs)</label><input type="number" step="0.01" name="costo_unitario" class="form-control" value="80"></div>
                <div class="col-12"><label class="form-label">Fecha Vencimiento</label><input type="date" name="fecha_vencimiento" class="form-control" value="2028-06-30" required></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar Insumo</button></div>
        </form>
    </div>
</div>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>