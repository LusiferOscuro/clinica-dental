<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<style>
    .arcada { display: flex; justify-content: center; gap: 14px; margin: 16px 0; flex-wrap: wrap; }
    .cuadrante { display: flex; gap: 6px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; }
    .diente { text-align: center; width: 44px; }
    .diente-num { font-weight: 700; font-size: 12px; margin-bottom: 3px; }
    .pieza-svg { width: 40px; height: 40px; cursor: pointer; }
    .cara { fill: #ffffff; stroke: #334155; stroke-width: 1.5; }
    .cara:hover { fill: #e2e8f0; }
    .estado-Caries { fill: #ef4444 !important; }
    .estado-Resina_Buena { fill: #3b82f6 !important; }
    .estado-Endodoncia { fill: #f59e0b !important; }
    .estado-Corona { fill: #8b5cf6 !important; }
    .estado-Ausente { fill: #1e293b !important; }
</style>

<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Odontograma Clínico FDI (Adulto & Pediátrico)</h4>
            <small class="text-muted">Paciente: <strong><?= htmlspecialchars(($paciente['nombres'] ?? 'Juan Pablo') . ' ' . ($paciente['apellidos'] ?? 'Gutiérrez')) ?></strong> | Guardado automático vía AJAX</small>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary active" id="btnAdulto">Dentición Adulta (32)</button>
            <button class="btn btn-outline-primary" id="btnPediatrica">Dentición Pediátrica (20)</button>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap mb-3" id="toolbar">
        <button class="btn btn-sm btn-dark tool-btn active" data-estado="Caries">🔴 Caries</button>
        <button class="btn btn-sm btn-outline-primary tool-btn" data-estado="Resina_Buena">🔵 Resina / Restauración</button>
        <button class="btn btn-sm btn-outline-warning tool-btn" data-estado="Endodoncia">🟠 Endodoncia</button>
        <button class="btn btn-sm btn-outline-secondary tool-btn" data-estado="Corona">🟣 Corona</button>
        <button class="btn btn-sm btn-outline-dark tool-btn" data-estado="Ausente">⚫ Pieza Ausente</button>
        <button class="btn btn-sm btn-outline-success tool-btn" data-estado="Sano">⚪ Limpiar (Sano)</button>
    </div>

    <div class="arcada">
        <div class="cuadrante" id="c1"></div>
        <div class="cuadrante" id="c2"></div>
    </div>
    <div class="arcada">
        <div class="cuadrante" id="c4"></div>
        <div class="cuadrante" id="c3"></div>
    </div>

    <h6 class="fw-bold mt-4">Historial Guardado en Base de Datos</h6>
    <ul class="list-group list-group-flush small" id="log-hallazgos">
        <?php foreach ($historial as $h): ?>
            <li class="list-group-item">Pieza <strong><?= htmlspecialchars($h['pieza_fdi']) ?></strong> (Cara <?= htmlspecialchars($h['cara']) ?>) &rarr; <span class="badge bg-secondary"><?= htmlspecialchars($h['estado_hallazgo']) ?></span> — <small class="text-muted"><?= $h['fecha_registro'] ?></small></li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
const pacienteId = <?= (int)($paciente['id'] ?? 1) ?>;
let estadoActual = 'Caries';
let tipoDenticion = 'Adulto';

const mapas = {
    Adulto: {
        c1: [18,17,16,15,14,13,12,11], c2: [21,22,23,24,25,26,27,28],
        c4: [48,47,46,45,44,43,42,41], c3: [31,32,33,34,35,36,37,38]
    },
    Pediatrica: {
        c1: [55,54,53,52,51], c2: [61,62,63,64,65],
        c4: [85,84,83,82,81], c3: [71,72,73,74,75]
    }
};

function renderOdontograma(tipo) {
    tipoDenticion = tipo;
    for (const [id, piezas] of Object.entries(mapas[tipo])) {
        document.getElementById(id).innerHTML = piezas.map(num => `
            <div class="diente">
                <div class="diente-num">${num}</div>
                <svg class="pieza-svg" viewBox="0 0 100 100" data-pieza="${num}">
                    <polygon class="cara" data-cara="V" points="0,0 100,0 75,25 25,25" />
                    <polygon class="cara" data-cara="L" points="25,75 75,75 100,100 0,100" />
                    <polygon class="cara" data-cara="M" points="0,0 25,25 25,75 0,100" />
                    <polygon class="cara" data-cara="D" points="75,25 100,0 100,100 75,75" />
                    <rect class="cara" data-cara="O" x="25" y="25" width="50" height="50" />
                </svg>
            </div>
        `).join('');
    }
    bindCaras();
}

function bindCaras() {
    document.querySelectorAll('.cara').forEach(cara => {
        cara.addEventListener('click', async function() {
            const pieza = this.parentElement.dataset.pieza;
            const nombreCara = this.dataset.cara;
            this.className.baseVal = 'cara' + (estadoActual !== 'Sano' ? ` estado-${estadoActual}` : '');

            const resp = await fetch('index.php?controller=odontograma&action=guardarAjax', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    paciente_id: pacienteId,
                    tipo_denticion: tipoDenticion,
                    pieza_fdi: pieza,
                    cara: nombreCara,
                    estado_hallazgo: estadoActual
                })
            });
            const data = await resp.json();
            if (data.ok) {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `Pieza <strong>${pieza}</strong> (Cara ${nombreCara}) &rarr; <span class="badge bg-primary">${estadoActual}</span> (Guardado ahora)`;
                document.getElementById('log-hallazgos').prepend(li);
            }
        });
    });
}

document.querySelectorAll('.tool-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active', 'btn-dark'));
        btn.classList.add('active', 'btn-dark');
        estadoActual = btn.dataset.estado;
    });
});

document.getElementById('btnAdulto').onclick = () => renderOdontograma('Adulto');
document.getElementById('btnPediatrica').onclick = () => renderOdontograma('Pediatrica');
renderOdontograma('Adulto');
</script>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>