<?php
class CajaController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso(['admin', 'recepcionista']);
        $this->db = Database::getConnection();
    }

    public function index() {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'];
            $monto = (float)$_POST['monto'];
            $presId = !empty($_POST['presupuesto_id']) ? (int)$_POST['presupuesto_id'] : null;
            $pacId = !empty($_POST['paciente_id']) ? (int)$_POST['paciente_id'] : null;

            $stmt = $this->db->prepare("INSERT INTO caja_movimientos (tipo, categoria, monto, metodo_pago, descripcion, paciente_id, presupuesto_id, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tipo, $_POST['categoria'], $monto, $_POST['metodo_pago'], trim($_POST['descripcion']), $pacId, $presId, $_SESSION['usuario_id']]);
            $movId = $this->db->lastInsertId();

            if ($tipo === 'Ingreso' && $presId) {
                $this->db->prepare("UPDATE presupuestos SET abonado = abonado + ? WHERE id = ?")->execute([$monto, $presId]);
            }

            Auth::registrarAuditoria($this->db, 'caja_movimientos', $movId, 'INSERT', "{$tipo} de {$monto} Bs ({$_POST['metodo_pago']})");
            $mensaje = "Movimiento registrado en caja y saldo actualizado.";
        }

        $movimientos = $this->db->query("
            SELECT m.*, (p.nombres || ' ' || p.apellidos) AS paciente
            FROM caja_movimientos m
            LEFT JOIN pacientes p ON m.paciente_id = p.id
            ORDER BY m.id DESC
        ")->fetchAll();

        $totalIngresos = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Ingreso'")->fetchColumn();
        $totalEgresos = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Egreso'")->fetchColumn();
        $presupuestos = $this->db->query("SELECT pr.id, pr.titulo, pr.total, pr.abonado, pr.paciente_id, (p.nombres || ' ' || p.apellidos) AS paciente FROM presupuestos pr INNER JOIN pacientes p ON pr.paciente_id = p.id WHERE pr.total > pr.abonado")->fetchAll();
        $pacientes = $this->db->query("SELECT id, nombres, apellidos FROM pacientes")->fetchAll();

        require dirname(__DIR__) . '/views/caja/index.php';
    }

    public function recibo() {
        $id = (int)($_GET['id'] ?? 1);
        $stmt = $this->db->prepare("
            SELECT m.*, (p.nombres || ' ' || p.apellidos) AS paciente, p.ci_nit, u.nombre_completo AS cajero
            FROM caja_movimientos m
            LEFT JOIN pacientes p ON m.paciente_id = p.id
            LEFT JOIN usuarios u ON m.usuario_id = u.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        $mov = $stmt->fetch();
        require dirname(__DIR__) . '/views/reportes/recibo_caja.php';
    }
}
