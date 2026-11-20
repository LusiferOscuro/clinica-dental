<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica Odontológica</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .box { border: 2px solid #0f172a; padding: 25px; border-radius: 8px; max-width: 750px; margin: auto; }
        .rx { font-size: 28px; font-weight: bold; color: #0d6efd; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="box">
    <button class="no-print" onclick="window.print()" style="float:right; padding:8px 14px; background:#198754; color:#fff; border:none; border-radius:6px; cursor:pointer;">🖨️ Imprimir / PDF</button>
    <h3>🦷 CLÍNICA ODONTOLÓGICA DENTAL PRO</h3>
    <p><strong>Paciente:</strong> <?= htmlspecialchars(($paciente['nombres'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?> &nbsp;|&nbsp; <strong>Fecha:</strong> <?= date('d/m/Y') ?></p>
    <hr>
    <div class="rx">Rp/</div>
    <ol>
        <li><strong>Amoxicilina + Ácido Clavulánico 875/125 mg</strong> — Caja x 14 comprimidos.<br><em>Tomar 1 comprimido cada 12 horas durante 7 días después de las comidas.</em></li>
        <li><strong>Ketorolaco 20 mg</strong> — Caja x 10 comprimidos sublinguales.<br><em>Tomar 1 comprimido cada 8 horas por 3 días en caso de dolor.</em></li>
        <li><strong>Clorhexidina 0.12% Enjuague Bucal</strong> — Frasco x 250 ml.<br><em>Realizar buches suaves durante 30 segundos cada 12 horas por 7 días (sin enjuagar con agua).</em></li>
    </ol>
    <hr>
    <p><strong>Indicaciones Postoperatorias:</strong> Mantener gasa mordida por 30 minutos, aplicar hielo local las primeras 6 horas, dieta blanda y fría hoy, no fumar ni realizar esfuerzos físicos.</p>
    <div style="margin-top:50px; text-align:right;">_______________________________<br>Firma y Sello Odontólogo</div>
</div>
</body>
</html>