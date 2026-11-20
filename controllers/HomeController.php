<?php
class HomeController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $totalPacientes = $this->db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
        $citasHoy = $this->db->query("SELECT COUNT(*) FROM citas")->fetchColumn();
        $ingresosMes = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Ingreso'")->fetchColumn();
        $insumosCriticos = $this->db->query("SELECT COUNT(*) FROM insumos WHERE stock_actual <= stock_minimo")->fetchColumn();

        $citaModel = new Cita($this->db);
        $agenda = $citaModel->listarTodas();

        require dirname(__DIR__) . '/views/home/inicio.php';
    }
}
