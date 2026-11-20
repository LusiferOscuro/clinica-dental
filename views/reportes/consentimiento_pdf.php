<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consentimiento Informado Odontológico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; color: #111; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 25px; }
        .firmas { display: flex; justify-content: space-between; margin-top: 80px; }
        .firma-box { width: 42%; border-top: 1px solid #000; text-align: center; padding-top: 8px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()" style="padding:10px 18px; background:#0d6efd; color:#fff; border:none; border-radius:6px; cursor:pointer;">🖨️ Imprimir / Guardar como PDF</button>
    <div class="header">
        <h2>CLÍNICA ODONTOLÓGICA INTEGRAL "DENTAL PRO XXI"</h2>
        <p><strong>CONSENTIMIENTO INFORMADO PARA PROCEDIMIENTO ODONTOLÓGICO</strong></p>
    </div>
    <p><strong>Fecha:</strong> <?= date('d/m/Y') ?></p>
    <p><strong>Paciente:</strong> <?= htmlspecialchars(($paciente['nombres'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?> | <strong>CI:</strong> <?= htmlspecialchars($paciente['ci_nit'] ?? '') ?></p>
    <p><strong>Antecedentes / Alergias Declaradas:</strong> <?= htmlspecialchars($paciente['alergias'] ?? 'Ninguna') ?> / <?= htmlspecialchars($paciente['condiciones_medicas'] ?? 'Ninguna') ?></p>
    <p><strong>Procedimiento a realizar:</strong> <?= $procedimiento ?></p>
    <p>Declaro haber sido informado(a) de manera clara y comprensible por el profesional odontólogo tratante sobre la naturaleza, objetivos, beneficios, alternativas y posibles riesgos inherentes al procedimiento odontológico detallado (incluyendo reacciones adversas a anestésicos locales, inflamación postoperatoria, sensibilidad o sangrado).</p>
    <p>Asimismo, confirmo haber declarado todos mis antecedentes médicos, alergias y medicación actual en la historia clínica (Anamnesis) y autorizo la ejecución del tratamiento.</p>
    <div class="firmas">
        <div class="firma-box">Firma del Paciente o Tutor Legal<br>CI: <?= htmlspecialchars($paciente['ci_nit'] ?? '') ?></div>
        <div class="firma-box">Firma y Sello del Odontólogo Tratante<br>Matrícula Profesional</div>
    </div>
</body>
</html>