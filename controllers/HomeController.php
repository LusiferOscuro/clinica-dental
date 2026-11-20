<?php
class HomeController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function index() {
        $totalPacientes = $this->db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
        $citasHoy = $this->db->query("SELECT COUNT(*) FROM citas WHERE fecha = CURDATE()")->fetchColumn();
        $ingresosMes = $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM caja_movimientos WHERE tipo='Ingreso' AND MONTH(fecha_movimiento)=MONTH(CURDATE())")->fetchColumn();
        $insumosCriticos = $this->db->query("SELECT COUNT(*) FROM insumos WHERE stock_actual <= stock_minimo")->fetchColumn();

        $citaModel = new Cita($this->db);
        $agendaHoy = $citaModel->listarPorFecha(date('Y-m-d'));

        require __DIR__ . '/../views/home/inicio.php';
    }
}
