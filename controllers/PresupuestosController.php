<?php
class PresupuestosController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_presupuesto'])) {
            $stmt = $this->db->prepare("INSERT INTO presupuestos (paciente_id, odontologo_id, titulo, total, abonado, estado) VALUES (?, ?, ?, ?, 0, 'Aprobado')");
            $stmt->execute([(int)$_POST['paciente_id'], $_SESSION['usuario_id'], trim($_POST['titulo']), (float)$_POST['costo']]);
            $presId = $this->db->lastInsertId();

            $stmtItem = $this->db->prepare("INSERT INTO presupuesto_items (presupuesto_id, pieza_fdi, tratamiento, costo, estado_item) VALUES (?, ?, ?, ?, 'Pendiente')");
            $stmtItem->execute([$presId, trim($_POST['pieza_fdi']), trim($_POST['tratamiento']), (float)$_POST['costo']]);

            Auth::registrarAuditoria($this->db, 'presupuestos', $presId, 'INSERT', 'Nuevo plan de tratamiento: ' . $_POST['titulo']);
            $mensaje = "Plan de tratamiento y presupuesto creado.";
        }

        if (isset($_GET['completar_item'])) {
            $itemId = (int)$_GET['completar_item'];
            $this->db->prepare("UPDATE presupuesto_items SET estado_item = 'Terminado' WHERE id = ?")->execute([$itemId]);
            Auth::registrarAuditoria($this->db, 'presupuesto_items', $itemId, 'UPDATE', 'Procedimiento marcado como Terminado');
            header('Location: index.php?controller=presupuestos&action=index');
            exit;
        }

        $presupuestos = $this->db->query("
            SELECT pr.*, (p.nombres || ' ' || p.apellidos) AS paciente, p.ci_nit
            FROM presupuestos pr
            INNER JOIN pacientes p ON pr.paciente_id = p.id
            ORDER BY pr.id DESC
        ")->fetchAll();

        foreach ($presupuestos as &$pr) {
            $stmtI = $this->db->prepare("SELECT * FROM presupuesto_items WHERE presupuesto_id = ?");
            $stmtI->execute([$pr['id']]);
            $pr['items'] = $stmtI->fetchAll();
        }

        $pacientes = $this->db->query("SELECT id, nombres, apellidos FROM pacientes")->fetchAll();
        require dirname(__DIR__) . '/views/presupuestos/index.php';
    }
}
