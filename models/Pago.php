<?php
class Pago {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registrarAbono($pacienteId, $presupuestoId, $monto, $metodoPago, $concepto, $usuarioId, $referencia = null) {
        $this->db->beginTransaction();
        try {
            $recibo = 'REC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $stmt = $this->db->prepare("INSERT INTO pagos (paciente_id, presupuesto_id, numero_recibo, monto, metodo_pago, referencia_transaccion, concepto, registrado_por)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$pacienteId, $presupuestoId, $recibo, $monto, $metodoPago, $referencia, $concepto, $usuarioId]);
            $pagoId = $this->db->lastInsertId();

            // Registrar automáticamente el ingreso en el libro diario de caja
            $stmtCaja = $this->db->prepare("INSERT INTO caja_movimientos (tipo, categoria, monto, metodo_pago, descripcion, pago_id, usuario_id)
                                            VALUES ('Ingreso', 'Cobro Tratamiento', ?, ?, ?, ?, ?)");
            $stmtCaja->execute([$monto, $metodoPago, "Recibo {$recibo}: {$concepto}", $pagoId, $usuarioId]);

            $this->db->commit();
            return ['pago_id' => $pagoId, 'numero_recibo' => $recibo];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function obtenerEstadoCuentaPresupuesto($presupuestoId) {
        $stmtTotal = $this->db->prepare("SELECT total FROM presupuestos WHERE id = ?");
        $stmtTotal->execute([$presupuestoId]);
        $total = (float) $stmtTotal->fetchColumn();

        $stmtAbonado = $this->db->prepare("SELECT COALESCE(SUM(monto), 0) FROM pagos WHERE presupuesto_id = ? AND estado = 'Pagado'");
        $stmtAbonado->execute([$presupuestoId]);
        $abonado = (float) $stmtAbonado->fetchColumn();

        return [
            'total' => $total,
            'abonado' => $abonado,
            'saldo_pendiente' => max(0, $total - $abonado)
        ];
    }
}
