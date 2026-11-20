<?php
class PacientesController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp, email, direccion, alergias, condiciones_medicas, medicacion_actual) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['ci_nit']),
                trim($_POST['nombres']),
                trim($_POST['apellidos']),
                $_POST['fecha_nacimiento'],
                $_POST['sexo'],
                trim($_POST['telefono_whatsapp']),
                trim($_POST['email']),
                trim($_POST['direccion']),
                trim($_POST['alergias']) ?: 'Ninguna',
                trim($_POST['condiciones_medicas']) ?: 'Ninguna',
                trim($_POST['medicacion_actual']) ?: 'Ninguna'
            ]);
            $id = $this->db->lastInsertId();
            Auth::registrarAuditoria($this->db, 'pacientes', $id, 'INSERT', 'Registro de paciente + anamnesis: ' . $_POST['nombres']);
            $mensaje = "Paciente registrado con su ficha de Anamnesis médica.";
        }

        $q = trim($_GET['q'] ?? '');
        if ($q !== '') {
            $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE nombres LIKE ? OR apellidos LIKE ? OR ci_nit LIKE ? ORDER BY id DESC");
            $like = "%{$q}%";
            $stmt->execute([$like, $like, $like]);
            $pacientes = $stmt->fetchAll();
        } else {
            $pacientes = $this->db->query("SELECT * FROM pacientes ORDER BY id DESC")->fetchAll();
        }
        require dirname(__DIR__) . '/views/pacientes/index.php';
    }
}
