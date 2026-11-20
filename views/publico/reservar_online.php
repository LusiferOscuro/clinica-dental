<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Tratamiento & Pago QR - Clínica Dental Pro XXI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .servicio-card { cursor: pointer; transition: all 0.15s; border: 2px solid #e2e8f0; }
        .servicio-card:hover { border-color: #0d6efd; background: #f8fafc; }
        .servicio-check:checked + .servicio-info { color: #0d6efd; font-weight: 600; }
        .qr-box { background: #155e2b; border-radius: 18px; padding: 16px; color: #fff; max-width: 360px; margin: 0 auto; }
        .qr-inner { background: #fff; color: #111; border-radius: 14px; padding: 16px; }
    </style>
</head>
<body class="bg-light py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h3 class="fw-bold text-primary mb-0">🦷 Solicitud de Tratamiento & Pago QR</h3>
                        <small class="text-muted">Selecciona los tratamientos que necesitas, calcula tu presupuesto en Bs y paga por QR</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/59160028073?text=Hola%20Cl%C3%ADnica%20Dental%2C%20quisiera%20hacer%20una%20consulta" target="_blank" class="btn btn-sm btn-success">📲 WhatsApp: 60028073</a>
                        <a href="index.php?controller=auth&action=login" class="btn btn-sm btn-outline-secondary">Acceso Staff</a>
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($resumenReserva)): 
                    $listaTxt = [];
                    foreach ($resumenReserva['tratamientos'] as $t) {
                        $listaTxt[] = "• {$t['nombre']} ({$t['precio_base']} Bs)";
                    }
                    $textoWhatsApp = urlencode(
                        "🦷 *NUEVA RESERVA + COMPROBANTE QR*\n" .
                        "📋 *Orden:* #" . str_pad((string)$resumenReserva['cita_id'], 5, '0', STR_PAD_LEFT) . "\n" .
                        "👤 *Paciente:* {$resumenReserva['paciente']} (CI: {$resumenReserva['ci']})\n" .
                        "📅 *Cita:* {$resumenReserva['fecha']} a las {$resumenReserva['hora']}\n" .
                        "💉 *Tratamientos:*\n" . implode("\n", $listaTxt) . "\n" .
                        "💰 *Total Presupuesto:* {$resumenReserva['total_bs']} Bs\n" .
                        "✅ *Monto Transferido por QR ({$resumenReserva['modalidad']}):* {$resumenReserva['pagar_ahora_bs']} Bs\n" .
                        "_(Adjunto captura de transferencia Banco Ganadero)_"
                    );
                ?>
                    <!-- PANTALLA DE PAGO QR BANCO GANADERO + WHATSAPP -->
                    <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($mensaje) ?></div>

                    <div class="row g-4 align-items-center mt-1">
                        <div class="col-md-6 text-center">
                            <div class="qr-box shadow">
                                <div class="fw-bold mb-2" style="letter-spacing:1px;">BANCO GANADERO</div>
                                <div class="qr-inner">
                                    <img src="publico/img/qr-pago.png" alt="QR Banco Ganadero" class="img-fluid rounded mb-2" style="max-height: 250px;"
                                         onerror="this.style.display='none'; document.getElementById('qr-fallback').style.display='block';">
                                    <div id="qr-fallback" style="display:none;" class="p-3 border rounded bg-light small text-muted">
                                        [Sube tu imagen QR a <code>public/img/qr-pago.png</code>]
                                    </div>
                                    <h3 class="fw-bold text-success mb-1">Bs <?= number_format($resumenReserva['pagar_ahora_bs'], 2) ?></h3>
                                    <div class="fw-bold small">VON BORRIES GUARDIA WILSON PERCY</div>
                                    <div class="text-success small fw-semibold">Cuenta de abono CA 1311057871</div>
                                    <div class="text-muted" style="font-size: 11px;">Vence el 3 de noviembre, 2028</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light border-0 p-3 rounded-3">
                                <h5 class="fw-bold text-dark">Resumen de tu Solicitud #<?= str_pad((string)$resumenReserva['cita_id'], 5, '0', STR_PAD_LEFT) ?></h5>
                                <p class="small mb-2">
                                    <strong>Paciente:</strong> <?= htmlspecialchars($resumenReserva['paciente']) ?> (CI: <?= htmlspecialchars($resumenReserva['ci']) ?>)<br>
                                    <strong>Fecha y Hora:</strong> <?= htmlspecialchars($resumenReserva['fecha']) ?> — <?= htmlspecialchars($resumenReserva['hora']) ?>
                                </p>
                                <ul class="list-group list-group-flush small mb-3 rounded">
                                    <?php foreach ($resumenReserva['tratamientos'] as $t): ?>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span><?= htmlspecialchars($t['nombre']) ?></span>
                                            <strong><?= number_format((float)$t['precio_base'], 2) ?> Bs</strong>
                                        </li>
                                    <?php endforeach; ?>
                                    <li class="list-group-item d-flex justify-content-between bg-white fw-bold">
                                        <span>Total Tratamiento:</span>
                                        <span><?= number_format($resumenReserva['total_bs'], 2) ?> Bs</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between bg-success text-white fw-bold">
                                        <span>Monto a pagar por QR (<?= htmlspecialchars($resumenReserva['modalidad']) ?>):</span>
                                        <span><?= number_format($resumenReserva['pagar_ahora_bs'], 2) ?> Bs</span>
                                    </li>
                                </ul>

                                <a href="https://wa.me/59160028073?text=<?= $textoWhatsApp ?>" target="_blank" class="btn btn-success w-100 py-2 fw-bold shadow-sm mb-2">
                                    📲 Enviar Comprobante QR al WhatsApp (60028073)
                                </a>
                                <a href="index.php?controller=publico&action=reservar" class="btn btn-outline-secondary btn-sm w-100">Hacer otra solicitud</a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- FORMULARIO DE SELECCIÓN DE TRATAMIENTOS Y DATOS DEL PACIENTE -->
                    <form method="POST" class="row g-3" id="formReserva">
                        <div class="col-12">
                            <label class="form-label fw-bold text-primary">1. Selecciona el/los Tratamientos que Necesitas (Precios en Bs)</label>
                            <div class="row g-2">
                                <?php foreach ($servicios as $s): ?>
                                    <div class="col-md-6">
                                        <label class="card servicio-card p-2 h-100 d-flex flex-row align-items-center gap-2">
                                            <input type="checkbox" name="servicios[]" value="<?= $s['id'] ?>" data-precio="<?= $s['precio_base'] ?>" class="form-check-input servicio-check mt-0">
                                            <div class="servicio-info flex-grow-1 small">
                                                <div><?= htmlspecialchars($s['nombre']) ?></div>
                                                <span class="text-muted" style="font-size:11px;"><?= htmlspecialchars($s['categoria']) ?> • <?= $s['duracion_minutos'] ?> min</span>
                                            </div>
                                            <span class="badge bg-primary fs-6"><?= number_format((float)$s['precio_base'], 0) ?> Bs</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-primary d-flex justify-content-between align-items-center py-2 mb-0">
                                <span><strong>Presupuesto Estimado Seleccionado:</strong></span>
                                <span class="fs-4 fw-bold" id="totalDisplay">0.00 Bs</span>
                            </div>
                        </div>

                        <div class="col-12"><hr class="my-1"><label class="form-label fw-bold text-primary mb-0">2. Datos Personales y Ficha Clínica del Paciente</label></div>

                        <div class="col-md-4"><label class="form-label small fw-semibold">CI / Documento *</label><input type="text" name="ci_nit" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Nombres *</label><input type="text" name="nombres" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Apellidos *</label><input type="text" name="apellidos" class="form-control" required></div>

                        <div class="col-md-4"><label class="form-label small fw-semibold">Fecha Nacimiento *</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Sexo</label><select name="sexo" class="form-select"><option value="M">Masculino</option><option value="F">Femenino</option></select></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Tu Celular / WhatsApp *</label><input type="text" name="telefono" class="form-control" placeholder="Ej. 7XXXXXXX" required></div>

                        <div class="col-md-6"><label class="form-label small fw-semibold">Alergias (Medicamentos / Anestesia)</label><input type="text" name="alergias" class="form-control" placeholder="Ej. Penicilina, Lidocaína o Ninguna"></div>
                        <div class="col-md-6"><label class="form-label small fw-semibold">Enfermedades / Condición Médica</label><input type="text" name="condiciones_medicas" class="form-control" placeholder="Ej. Diabetes, Hipertensión, Embarazo o Ninguna"></div>

                        <div class="col-12"><hr class="my-1"><label class="form-label fw-bold text-primary mb-0">3. Fecha, Especialista y Modalidad de Pago QR</label></div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Especialista</label>
                            <select name="odontologo_id" class="form-select" required>
                                <?php foreach ($doctores as $d): ?><option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre_completo'] . ' (' . $d['especialidad'] . ')') ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Fecha de Atención *</label><input type="date" name="fecha" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Hora Preferida *</label><input type="time" name="hora_inicio" class="form-control" min="08:00" max="19:30" value="10:00" required></div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Modalidad de Pago por QR</label>
                            <select name="modalidad_pago" class="form-select">
                                <option value="Pago Total 100%">Pago Total del Tratamiento (100% por QR)</option>
                                <option value="Reserva 50%">Anticipo de Reserva (50% QR y 50% en clínica)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Síntomas o Detalle Adicional</label>
                            <input type="text" name="notas" class="form-control" placeholder="Ej. Dolor en muela superior derecha al tomar frío">
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold fs-5">Generar Presupuesto y Mostrar QR de Pago &rarr;</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
const checks = document.querySelectorAll('.servicio-check');
const totalDisplay = document.getElementById('totalDisplay');
if (checks.length && totalDisplay) {
    const actualizarTotal = () => {
        let suma = 0;
        checks.forEach(c => { if (c.checked) suma += parseFloat(c.dataset.precio || 0); });
        totalDisplay.textContent = suma.toFixed(2) + ' Bs';
    };
    checks.forEach(c => c.addEventListener('change', actualizarTotal));
}
</script>
</body>
</html>
