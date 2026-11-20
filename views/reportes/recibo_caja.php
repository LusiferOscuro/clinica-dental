<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Caja #<?= (int)($mov['id'] ?? 1) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .recibo { max-width: 600px; margin: auto; border: 2px dashed #334155; padding: 25px; border-radius: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="recibo">
    <button class="no-print" onclick="window.print()" style="float:right; padding:6px 12px; background:#0d6efd; color:#fff; border:none; border-radius:6px; cursor:pointer;">🖨️ Imprimir Recibo</button>
    <h3 style="margin-top:0;">🦷 CLÍNICA DENTAL PRO XXI</h3>
    <p><strong>COMPROBANTE OFICIAL DE CAJA N° <?= str_pad((string)($mov['id'] ?? 1), 6, '0', STR_PAD_LEFT) ?></strong></p>
    <hr>
    <p><strong>Fecha y Hora:</strong> <?= htmlspecialchars($mov['fecha_movimiento'] ?? date('Y-m-d H:i')) ?></p>
    <p><strong>Tipo de Movimiento:</strong> <?= htmlspecialchars($mov['tipo'] ?? 'Ingreso') ?> (<?= htmlspecialchars($mov['metodo_pago'] ?? 'QR') ?>)</p>
    <p><strong>Paciente:</strong> <?= htmlspecialchars($mov['paciente'] ?? 'Paciente General') ?></p>
    <p><strong>Concepto:</strong> <?= htmlspecialchars($mov['categoria'] ?? '') ?> — <?= htmlspecialchars($mov['descripcion'] ?? '') ?></p>
    <h2 style="background:#f1f5f9; padding:12px; border-radius:6px; text-align:center;">MONTO: <?= number_format((float)($mov['monto'] ?? 0), 2) ?> Bs</h2>
    <p style="font-size:12px; color:#64748b;">Atendido por: <?= htmlspecialchars($mov['cajero'] ?? 'Administración') ?></p>
</div>
</body>
</html>