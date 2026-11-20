<?php require dirname(__DIR__) . '/layout/header.php'; ?>
<style>
    .arcada { display: flex; justify-content: center; gap: 18px; margin: 14px 0; flex-wrap: wrap; }
    .cuadrante { display: flex; gap: 6px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 12px; background: #f8fafc; }
    .diente { text-align: center; width: 46px; transition: transform 0.15s ease; }
    .diente:hover { transform: translateY(-3px); }
    .diente.pediatrico { width: 40px; }
    .diente-num { font-weight: 800; font-size: 12px; color: #1e293b; margin-bottom: 2px; }
    .diente-tipo { font-size: 9px; color: #64748b; display: block; margin-bottom: 3px; }
    .raiz-svg { width: 42px; height: 26px; display: block; margin: 0 auto; }
    .diente.pediatrico .raiz-svg { width: 34px; height: 20px; }
    .pieza-svg { width: 42px; height: 42px; cursor: pointer; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.08)); }
    .diente.pediatrico .pieza-svg { width: 35px; height: 35px; }
    .cara { fill: #ffffff; stroke: #475569; stroke-width: 2; transition: fill 0.15s; }
    .cara:hover { fill: #e2e8f0; }
    .estado-Caries { fill: #ef4444 !important; }
    .estado-Resina_Buena { fill: #3b82f6 !important; }
    .estado-Endodoncia { fill: #f59e0b !important; }
    .estado-Corona { fill: #8b5cf6 !important; }
    .estado-Ausente { fill: #1e293b !important; }
</style>

<?php if (($paciente['alergias'] ?? 'Ninguna') !== 'Ninguna' || ($paciente['condiciones_medicas'] ?? 'Ninguna') !== 'Ninguna'): ?>
<div class="alert alert-danger fw-bold shadow-sm">
    🚨 ALERTA MÉDICA DE ANAMNESIS: Alergias: <u><?= htmlspecialchars($paciente['alergias']) ?></u> | Condición: <u><?= htmlspecialchars($paciente['condiciones_medicas']) ?></u> | Medicación: <u><?= htmlspecialchars($paciente['medicacion_actual'] ?? 'Ninguna') ?></u>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">🦷 Odontograma Clínico Anatómico FDI</h4>
            <form method="GET" class="d-flex align-items-center gap-2">
                <input type="hidden" name="controller" value="odontograma">
                <input type="hidden" name="action" value="index">
                <select name="paciente_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($pacientes as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id']==$paciente['id']?'selected':'' ?>>Paciente: <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos'] . ' (CI: ' . $p['ci_nit'] . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary active" id="btnAdulto">🧑 Dentición Adulta (32)</button>
            <button class="btn btn-outline-primary" id="btnPediatrica">🧒 Dentición Pediátrica (20)</button>
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

    <div class="text-center small fw-bold text-muted">ARCADA SUPERIOR</div>
    <div class="arcada">
        <div class="cuadrante" id="c1"></div>
        <div class="cuadrante" id="c2"></div>
    </div>
    <div class="arcada">
        <div class="cuadrante" id="c4"></div>
        <div class="cuadrante" id="c3"></div>
    </div>
    <div class="text-center small fw-bold text-muted">ARCADA INFERIOR</div>

    <h6 class="fw-bold mt-4">Historial de Hallazgos Clínicos Guardados</h6>
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

function getSiluetaRaiz(num, esSuperior) {
    const unidad = num % 10;
    const esMolar = (tipoDenticion === 'Adulto' && unidad >= 6) || (tipoDenticion === 'Pediatrica' && unidad >= 4);
    const esPremolar = (tipoDenticion === 'Adulto' && (unidad === 4 || unidad === 5));
    const fill = tipoDenticion === 'Pediatrica' ? '#fef3c7' : '#f1f5f9';
    const stroke = '#64748b';

    let paths = '';
    if (esMolar) {
        paths = `<path d="M18,55 Q12,15 24,4 Q34,25 42,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>
                 <path d="M40,55 Q50,12 58,4 Q66,25 64,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>
                 <path d="M58,55 Q72,18 82,6 Q86,30 78,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>`;
    } else if (esPremolar) {
        paths = `<path d="M25,55 Q24,14 36,4 Q46,25 50,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>
                 <path d="M50,55 Q56,14 66,4 Q76,25 74,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>`;
    } else {
        paths = `<path d="M26,55 Q34,12 50,3 Q66,12 74,55 Z" fill="${fill}" stroke="${stroke}" stroke-width="2.5"/>`;
    }
    const transform = esSuperior ? '' : 'transform="scale(1,-1) translate(0,-60)"';
    return `<svg class="raiz-svg" viewBox="0 0 100 60"><g ${transform}>${paths}</g></svg>`;
}

function getEtiquetaPieza(num) {
    const u = num % 10;
    if (tipoDenticion === 'Pediatrica') return u >= 4 ? 'Molar Dec.' : (u === 3 ? 'Canino' : 'Incisivo');
    return u >= 6 ? 'Molar' : (u >= 4 ? 'Premolar' : (u === 3 ? 'Canino' : 'Incisivo'));
}

function renderOdontograma(tipo) {
    tipoDenticion = tipo;
    const clasePed = tipo === 'Pediatrica' ? 'pediatrico' : '';
    for (const [id, piezas] of Object.entries(mapas[tipo])) {
        const esSuperior = (id === 'c1' || id === 'c2');
        document.getElementById(id).innerHTML = piezas.map(num => {
            const raiz = getSiluetaRaiz(num, esSuperior);
            const corona = `
                <svg class="pieza-svg" viewBox="0 0 100 100" data-pieza="${num}">
                    <defs><clipPath id="clip-${num}"><rect x="4" y="4" width="92" height="92" rx="34" ry="34"/></clipPath></defs>
                    <g clip-path="url(#clip-${num})">
                        <polygon class="cara" data-cara="V" points="0,0 100,0 72,28 28,28" />
                        <polygon class="cara" data-cara="L" points="28,72 72,72 100,100 0,100" />
                        <polygon class="cara" data-cara="M" points="0,0 28,28 28,72 0,100" />
                        <polygon class="cara" data-cara="D" points="72,28 100,0 100,100 72,72" />
                        <ellipse class="cara" data-cara="O" cx="50" cy="50" rx="22" ry="22" />
                    </g>
                    <rect x="4" y="4" width="92" height="92" rx="34" ry="34" fill="none" stroke="#334155" stroke-width="3"/>
                </svg>`;
            return `
                <div class="diente ${clasePed}">
                    <div class="diente-num">${num}</div>
                    <span class="diente-tipo">${getEtiquetaPieza(num)}</span>
                    ${esSuperior ? raiz + corona : corona + raiz}
                </div>`;
        }).join('');
    }
    bindCaras();
}

function bindCaras() {
    document.querySelectorAll('.cara').forEach(cara => {
        cara.addEventListener('click', async function() {
            const pieza = this.closest('.pieza-svg').dataset.pieza;
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

document.getElementById('btnAdulto').onclick = function() {
    this.className = 'btn btn-primary active';
    document.getElementById('btnPediatrica').className = 'btn btn-outline-primary';
    renderOdontograma('Adulto');
};
document.getElementById('btnPediatrica').onclick = function() {
    this.className = 'btn btn-primary active';
    document.getElementById('btnAdulto').className = 'btn btn-outline-primary';
    renderOdontograma('Pediatrica');
};
renderOdontograma('Adulto');
</script>
<?php require dirname(__DIR__) . '/layout/footer.php'; ?>
