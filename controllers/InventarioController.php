<?php
class InventarioController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_insumo'])) {
            $stmt = $this->db->prepare("INSERT INTO insumos (codigo, nombre, categoria, unidad_medida, stock_actual, stock_minimo, fecha_vencimiento, costo_unitario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['codigo']), trim($_POST['nombre']), trim($_POST['categoria']),
                trim($_POST['unidad_medida']), (int)$_POST['stock_actual'], (int)$_POST['stock_minimo'],
                $_POST['fecha_vencimiento'], (float)$_POST['costo_unitario']
            ]);
            Auth::registrarAuditoria($this->db, 'insumos', $this->db->lastInsertId(), 'INSERT', 'Nuevo insumo: ' . $_POST['nombre']);
            $mensaje = "Insumo agregado al Kardex.";
        }

        if (isset($_GET['ajustar'], $_GET['id'])) {
            $delta = (int)$_GET['ajustar'];
            $id = (int)$_GET['id'];
            $this->db->prepare("UPDATE insumos SET stock_actual = MAX(0, stock_actual + ?) WHERE id = ?")->execute([$delta, $id]);
            Auth::registrarAuditoria($this->db, 'insumos', $id, 'UPDATE', "Ajuste stock ({$delta})");
            header('Location: index.php?controller=inventario&action=index');
            exit;
        }

        $insumos = $this->db->query("SELECT * FROM insumos ORDER BY (stock_actual <= stock_minimo) DESC, nombre ASC")->fetchAll();
        require dirname(__DIR__) . '/views/inventario/index.php';
    }
}
