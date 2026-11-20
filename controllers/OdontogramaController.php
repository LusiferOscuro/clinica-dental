<?php
class OdontogramaController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso(['admin', 'odontologo']);
        $this->db = Database::getConnection();
    }

    public function index() {
        $pacienteId = (int)($_GET['paciente_id'] ?? 1);
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$pacienteId]);
        $paciente = $stmt->fetch();

        $model = new Odontograma($this->db);
        $historial = $model->obtenerPorPaciente($pacienteId);
        require dirname(__DIR__) . '/views/odontograma/index.php';
    }

    public function guardarAjax() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['ok' => false, 'msg' => 'Datos inválidos']);
            return;
        }
        try {
            $model = new Odontograma($this->db);
            $id = $model->registrarHallazgo(
                (int)$input['paciente_id'],
                $_SESSION['usuario_id'],
                $input['tipo_denticion'] ?? 'Adulto',
                $input['pieza_fdi'],
                $input['cara'],
                $input['estado_hallazgo'],
                $input['observacion'] ?? ''
            );
            Auth::registrarAuditoria($this->db, 'odontograma_registros', $id, 'INSERT', "Pieza {$input['pieza_fdi']} ({$input['cara']}) -> {$input['estado_hallazgo']}");
            echo json_encode(['ok' => true, 'id' => $id]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
        }
    }
}
