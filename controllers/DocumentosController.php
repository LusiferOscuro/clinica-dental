<?php
class DocumentosController {
    private $db;
    public function __construct() {
        Auth::verificarAcceso();
        $this->db = Database::getConnection();
    }

    public function consentimiento() {
        $pacienteId = (int)($_GET['paciente_id'] ?? 1);
        $procedimiento = htmlspecialchars($_GET['procedimiento'] ?? 'Exodoncia / Cirugía Dental');
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$pacienteId]);
        $paciente = $stmt->fetch();
        require __DIR__ . '/../views/reportes/consentimiento_pdf.php';
    }

    public function receta() {
        $pacienteId = (int)($_GET['paciente_id'] ?? 1);
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = ?");
        $stmt->execute([$pacienteId]);
        $paciente = $stmt->fetch();
        require __DIR__ . '/../views/reportes/receta_pdf.php';
    }
}
