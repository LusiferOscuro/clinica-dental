<?php
class PublicoController {
    private $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function reservar() {
        $mensaje = null;
        $error = null;
        $doctores = $this->db->query("SELECT id, nombre_completo, especialidad FROM usuarios WHERE rol_id = 2 AND estado = 1")->fetchAll();
        $servicios = $this->db->query("SELECT id, nombre, precio_base, duracion_minutos FROM servicios WHERE activo = 1")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ci = trim($_POST['ci_nit']);
                $stmtP = $this->db->prepare("SELECT id FROM pacientes WHERE ci_nit = ?");
                $stmtP->execute([$ci]);
                $pacienteId = $stmtP->fetchColumn();

                if (!$pacienteId) {
                    $insP = $this->db->prepare("INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp) VALUES (?, ?, ?, ?, ?, ?)");
                    $insP->execute([$ci, $_POST['nombres'], $_POST['apellidos'], $_POST['fecha_nacimiento'], $_POST['sexo'], $_POST['telefono']]);
                    $pacienteId = $this->db->lastInsertId();
                }

                $horaInicio = $_POST['hora_inicio'];
                $horaFin = date('H:i:s', strtotime($horaInicio . ' +30 minutes'));
                $citaModel = new Cita($this->db);
                $citaModel->crear([
                    ':paciente_id' => $pacienteId,
                    ':odontologo_id' => (int)$_POST['odontologo_id'],
                    ':sillon_id' => 1,
                    ':servicio_id' => (int)$_POST['servicio_id'],
                    ':fecha' => $_POST['fecha'],
                    ':hora_inicio' => $horaInicio,
                    ':hora_fin' => $horaFin,
                    ':motivo_consulta' => 'Reserva Online Web',
                    ':origen' => 'Portal Web',
                    ':notas' => $_POST['notas'] ?? '',
                    ':creado_por' => null
                ]);
                $mensaje = "¡Tu cita fue reservada con éxito! Te confirmaremos por WhatsApp.";
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        require __DIR__ . '/../views/publico/reservar_online.php';
    }
}
