<?php require __DIR__ . '/../layout/header.php'; ?>
<h3 class="fw-bold mb-4">Panel Gerencial de Clínica Odontológica</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Pacientes Registrados</span>
            <h2 class="fw-bold text-primary mb-0"><?= (int)$totalPacientes ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Citas Programadas Hoy</span>
            <h2 class="fw-bold text-success mb-0"><?= (int)$citasHoy ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Ingresos del Mes (Bs)</span>
            <h2 class="fw-bold text-dark mb-0"><?= number_format((float)$ingresosMes, 2) ?> Bs</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small">Alertas Stock Mínimo</span>
            <h2 class="fw-bold text-danger mb-0"><?= (int)$insumosCriticos ?></h2>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold mb-3">Ingresos vs. Egresos Operativos (Últimos 6 Meses - Bs)</h6>
            <canvas id="chartFinanzas" height="130"></canvas>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold mb-3">Distribución de Tratamientos Realizados</h6>
            <canvas id="chartTratamientos" height="175"></canvas>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('chartFinanzas'), {
    type: 'bar',
    data: {
        labels: ['Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov'],
        datasets: [
            { label: 'Ingresos (Bs)', data: [18500, 21200, 19800, 24500, 26100, 28900], backgroundColor: '#0d6efd' },
            { label: 'Egresos / Lab (Bs)', data: [5200, 6100, 5800, 7200, 6900, 7400], backgroundColor: '#dc3545' }
        ]
    }
});

new Chart(document.getElementById('chartTratamientos'), {
    type: 'doughnut',
    data: {
        labels: ['Operatoria/Resinas', 'Ortodoncia', 'Endodoncia', 'Cirugía', 'Prótesis'],
        datasets: [{
            data: [38, 25, 15, 12, 10],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
        }]
    }
});
</script>
<?php require __DIR__ . '/../layout/footer.php'; ?>
