<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Odontograma Interactivo FDI - Clínica Dental</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f4f7f9; color: #1e293b; padding: 20px; }
        .card { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); max-width: 1100px; margin: auto; }
        .arcada { display: flex; justify-content: center; gap: 10px; margin: 20px 0; flex-wrap: wrap; }
        .cuadrante { display: flex; gap: 6px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; }
        .diente { text-align: center; width: 46px; }
        .diente-num { font-weight: bold; font-size: 13px; margin-bottom: 4px; }
        .pieza-svg { width: 42px; height: 42px; cursor: pointer; }
        .cara { fill: #ffffff; stroke: #334155; stroke-width: 1.5; transition: fill 0.15s; }
        .cara:hover { fill: #e2e8f0; }
        .estado-Caries { fill: #ef4444 !important; }
        .estado-Resina_Buena { fill: #3b82f6 !important; }
        .estado-Endodoncia { fill: #f59e0b !important; }
        .estado-Corona { fill: #8b5cf6 !important; }
        .estado-Ausente { fill: #1e293b !important; }
        .toolbar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; }
        .btn-tool { padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; background: #fff; font-weight: 600; }
        .btn-tool.active { border-color: #0f172a; background: #0f172a; color: #fff; }
    </style>
</head>
<body>
<div class="card">
    <h2>Odontograma Clínico (Estándar Internacional FDI)</h2>
    <p>Selecciona un hallazgo o tratamiento y haz clic sobre la cara dental correspondiente (Vestibular, Lingual/Palatina, Mesial, Distal, Oclusal).</p>

    <div class="toolbar" id="toolbar">
        <button class="btn-tool active" data-estado="Caries">🔴 Caries</button>
        <button class="btn-tool" data-estado="Resina_Buena">🔵 Resina / Restauración</button>
        <button class="btn-tool" data-estado="Endodoncia">🟠 Endodoncia</button>
        <button class="btn-tool" data-estado="Corona">🟣 Corona / Prótesis</button>
        <button class="btn-tool" data-estado="Ausente">⚫ Pieza Ausente</button>
        <button class="btn-tool" data-estado="Sano">⚪ Limpiar (Sano)</button>
    </div>

    <h4>Arcada Superior (Cuadrante 1 | Cuadrante 2)</h4>
    <div class="arcada">
        <div class="cuadrante" id="c1"></div>
        <div class="cuadrante" id="c2"></div>
    </div>

    <h4>Arcada Inferior (Cuadrante 4 | Cuadrante 3)</h4>
    <div class="arcada">
        <div class="cuadrante" id="c4"></div>
        <div class="cuadrante" id="c3"></div>
    </div>

    <h4>Registro de Hallazgos Seleccionados</h4>
    <ul id="log-hallazgos"></ul>
</div>

<script>
    let estadoActual = 'Caries';
    document.querySelectorAll('.btn-tool').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.btn-tool').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            estadoActual = btn.dataset.estado;
        });
    });

    const cuadrantes = {
        c1: [18,17,16,15,14,13,12,11],
        c2: [21,22,23,24,25,26,27,28],
        c4: [48,47,46,45,44,43,42,41],
        c3: [31,32,33,34,35,36,37,38]
    };

    function crearDienteHTML(num) {
        return `
        <div class="diente">
            <div class="diente-num">${num}</div>
            <svg class="pieza-svg" viewBox="0 0 100 100" data-pieza="${num}">
                <polygon class="cara" data-cara="V" points="0,0 100,0 75,25 25,25" />
                <polygon class="cara" data-cara="L" points="25,75 75,75 100,100 0,100" />
                <polygon class="cara" data-cara="M" points="0,0 25,25 25,75 0,100" />
                <polygon class="cara" data-cara="D" points="75,25 100,0 100,100 75,75" />
                <rect class="cara" data-cara="O" x="25" y="25" width="50" height="50" />
            </svg>
        </div>`;
    }

    for (const [id, piezas] of Object.entries(cuadrantes)) {
        const cont = document.getElementById(id);
        cont.innerHTML = piezas.map(crearDienteHTML).join('');
    }

    document.querySelectorAll('.cara').forEach(cara => {
        cara.addEventListener('click', function() {
            const pieza = this.parentElement.dataset.pieza;
            const nombreCara = this.dataset.cara;
            this.className.baseVal = 'cara' + (estadoActual !== 'Sano' ? ` estado-${estadoActual}` : '');
            const li = document.createElement('li');
            li.textContent = `Pieza ${pieza} | Cara ${nombreCara} -> ${estadoActual}`;
            document.getElementById('log-hallazgos').prepend(li);
        });
    });
</script>
</body>
</html>
