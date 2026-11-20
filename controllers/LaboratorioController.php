<?php
class LaboratorioController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $this->db->prepare("INSERT INTO laboratorio_ordenes (paciente_id, odontologo_id, laboratorio_nombre, trabajo_solicitado, pieza_fdi, color_guia, fecha_envio, fecha_entrega_prometida, costo, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'En Laboratorio')");
            $stmt->execute([
                (int)$_POST['paciente_id'], $_SESSION['usuario_id'], trim($_POST['laboratorio_nombre']),
                trim($_POST['trabajo_solicitado']), trim($_POST['pieza_fdi']), trim($_POST['color_guia']),
                $_POST['fecha_envio'], $_POST['fecha_entrega_prometida'], (float)$_POST['costo']
            ]);
            Auth::registrarAuditoria($this->db, 'laboratorio_ordenes', $this->db->lastInsertId(), 'INSERT', 'Orden laboratorio: ' . $_POST['trabajo_solicitado']);
            $mensaje = "Orden de laboratorio protésico registrada.";
        }

        if (isset($_GET['recibir'])) {
            $id = (int)$_GET['recibir'];
            $this->db->prepare("UPDATE laboratorio_ordenes SET estado = 'Recibido en Clínica' WHERE id = ?")->execute([$id]);
            header('Location: index.php?controller=laboratorio&action=index');
            exit;
        }

        $ordenes = $this->db->query("
            SELECT l.*, (p.nombres || ' ' || p.apellidos) AS paciente
            FROM laboratorio_ordenes l
            INNER JOIN pacientes p ON l.paciente_id = p.id
            ORDER BY l.id DESC
        ")->fetchAll();
        $pacientes = $this->db->query("SELECT id, nombres, apellidos FROM pacientes")->fetchAll();
        require dirname(__DIR__) . '/views/laboratorio/index.php';
    }
}
