<?php
class Cita {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Valida que no exista cruce de horario ni para el odontólogo ni para el sillón dental
     */
    public function existeConflictoHorario($fecha, $horaInicio, $horaFin, $odontologoId, $sillonId, $excluirCitaId = null) {
        $sql = "SELECT COUNT(*) FROM citas 
                WHERE fecha = :fecha 
                  AND estado NOT IN ('Cancelada', 'No asistió')
                  AND (odontologo_id = :odontologo_id OR sillon_id = :sillon_id)
                  AND (:hora_inicio < hora_fin AND :hora_fin > hora_inicio)";
        if ($excluirCitaId) {
            $sql .= " AND id != :excluir_id";
        }
        $stmt = $this->db->prepare($sql);
        $params = [
            ':fecha' => $fecha,
            ':odontologo_id' => $odontologoId,
            ':sillon_id' => $sillonId,
            ':hora_inicio' => $horaInicio,
            ':hora_fin' => $horaFin
        ];
        if ($excluirCitaId) {
            $params[':excluir_id'] = $excluirCitaId;
        }
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    public function crear($datos) {
        if ($this->existeConflictoHorario($datos['fecha'], $datos['hora_inicio'], $datos['hora_fin'], $datos['odontologo_id'], $datos['sillon_id'])) {
            throw new Exception("Conflicto de horario: El odontólogo o el sillón seleccionado ya están ocupados en ese horario.");
        }
        $sql = "INSERT INTO citas (paciente_id, odontologo_id, sillon_id, servicio_id, fecha, hora_inicio, hora_fin, motivo_consulta, notas, creado_por)
                VALUES (:paciente_id, :odontologo_id, :sillon_id, :servicio_id, :fecha, :hora_inicio, :hora_fin, :motivo_consulta, :notas, :creado_por)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($datos);
        return $this->db->lastInsertId();
    }

    public function generarLinkWhatsApp($telefono, $nombrePaciente, $fecha, $hora, $doctor) {
        $numero = preg_replace('/[^0-9]/', '', $telefono);
        $mensaje = "Hola {$nombrePaciente}, le recordamos su cita en nuestra Clínica Dental el día {$fecha} a las {$hora} con el Dr(a). {$doctor}. Por favor responda CONFIRMAR para mantener su turno.";
        return "https://wa.me/{$numero}?text=" . urlencode($mensaje);
    }
}
