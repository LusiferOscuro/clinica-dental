<?php
class Odontograma {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function registrarHallazgo($pacienteId, $odontologoId, $tipoDenticion, $piezaFdi, $cara, $estadoHallazgo, $observacion = '') {
        $sql = "INSERT INTO odontograma_registros (paciente_id, odontologo_id, tipo_denticion, pieza_fdi, cara, estado_hallazgo, observacion)
                VALUES (:paciente_id, :odontologo_id, :tipo_denticion, :pieza_fdi, :cara, :estado_hallazgo, :observacion)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':paciente_id' => $pacienteId, ':odontologo_id' => $odontologoId,
            ':tipo_denticion' => $tipoDenticion, ':pieza_fdi' => $piezaFdi,
            ':cara' => $cara, ':estado_hallazgo' => $estadoHallazgo, ':observacion' => $observacion
        ]);
        return $this->db->lastInsertId();
    }

    public function obtenerPorPaciente($pacienteId) {
        $sql = "SELECT o.*, u.nombre_completo AS odontologo
                FROM odontograma_registros o
                INNER JOIN usuarios u ON o.odontologo_id = u.id
                WHERE o.paciente_id = ? ORDER BY o.fecha_registro DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pacienteId]);
        return $stmt->fetchAll();
    }
}
