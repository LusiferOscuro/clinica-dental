<?php
class HomeController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_cita'])) {
            try {
                $citaModel = new Cita($this->db);
                $horaInicio = $_POST['hora_inicio'];
                $horaFin = date('H:i', strtotime($horaInicio . ' +45 minutes'));
                $id = $citaModel->crear([
                    ':paciente_id' => (int)$_POST['paciente_id'],
                    ':odontologo_id' => (int)$_POST['odontologo_id'],
                    ':sillon_id' => (int)$_POST['sillon_id'],
                    ':servicio_id' => (int)$_POST['servicio_id'],
                    ':fecha' => $_POST['fecha'],
                    ':hora_inicio' => $horaInicio,
                    ':hora_fin' => $horaFin,
                    ':motivo_consulta' => $_POST['motivo_consulta'],
                    ':origen' => 'Recepción',
                    ':notas' => '',
                    ':creado_por' => $_SESSION['usuario_id']
                ]);
                Auth::registrarAuditoria($this->db, 'citas', $id, 'INSERT', 'Nueva cita agendada desde recepción');
                $msgCita = "Cita agendada correctamente sin conflictos.";
            } catch (Exception $e) {
                $errCita = $e->getMessage();
            }
        }

        if (isset($_GET['cambiar_estado'], $_GET['cita_id'])) {
            $stmt = $this->db->prepare("UPDATE citas SET estado = ? WHERE id = ?");
            $stmt->execute([$_GET['cambiar_estado'], (int)$_GET['cita_id']]);
            Auth::registrarAuditoria($this->db, 'citas', (int)$_GET['cita_id'], 'UPDATE', 'Estado cambiado a ' . $_GET['cambiar_estado']);
            header('Location: index.php?controller=home&action=index');
            exit;
        }

        $totalPacientes = $this->db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
        $citasHoy = $this->db->query("SELECT COUNT(*) FROM citas")->fetchColumn();
        $ingresosMes = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Ingreso'")->fetchColumn();
        $egresosMes = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Egreso'")->fetchColumn();
        $insumosCriticos = $this->db->query("SELECT COUNT(*) FROM insumos WHERE stock_actual <= stock_minimo")->fetchColumn();
        $saldoPorCobrar = $this->db->query("SELECT COALESCE(SUM(total - abonado), 0) FROM presupuestos")->fetchColumn();

        $citaModel = new Cita($this->db);
        $agenda = $citaModel->listarTodas();
        $pacientes = $this->db->query("SELECT id, nombres, apellidos, ci_nit FROM pacientes ORDER BY nombres")->fetchAll();
        $doctores = $this->db->query("SELECT id, nombre_completo FROM usuarios WHERE rol_id IN (1,2)")->fetchAll();
        $sillones = $this->db->query("SELECT * FROM sillones")->fetchAll();
        $servicios = $this->db->query("SELECT * FROM servicios WHERE activo = 1")->fetchAll();
        $auditoria = $this->db->query("SELECT a.*, u.nombre_completo FROM auditoria_logs a LEFT JOIN usuarios u ON a.usuario_id = u.id ORDER BY a.id DESC LIMIT 6")->fetchAll();

        require dirname(__DIR__) . '/views/home/inicio.php';
    }
}
