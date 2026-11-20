<?php
class PublicoController {
    private $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function reservar() {
        $mensaje = null;
        $error = null;
        $resumenReserva = null;

        $doctores = $this->db->query("SELECT id, nombre_completo, especialidad FROM usuarios WHERE rol_id IN (1,2) AND estado = 1")->fetchAll();
        $servicios = $this->db->query("SELECT id, codigo, categoria, nombre, precio_base, duracion_minutos FROM servicios WHERE activo = 1 ORDER BY categoria, nombre")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $ci = trim($_POST['ci_nit']);
                $stmtP = $this->db->prepare("SELECT id FROM pacientes WHERE ci_nit = ?");
                $stmtP->execute([$ci]);
                $pacienteId = $stmtP->fetchColumn();

                if (!$pacienteId) {
                    $insP = $this->db->prepare("INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp, email, alergias, condiciones_medicas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insP->execute([
                        $ci,
                        trim($_POST['nombres']),
                        trim($_POST['apellidos']),
                        $_POST['fecha_nacimiento'],
                        $_POST['sexo'],
                        trim($_POST['telefono']),
                        trim($_POST['email'] ?? ''),
                        trim($_POST['alergias'] ?? '') ?: 'Ninguna',
                        trim($_POST['condiciones_medicas'] ?? '') ?: 'Ninguna'
                    ]);
                    $pacienteId = $this->db->lastInsertId();
                }

                // Procesar tratamientos seleccionados
                $serviciosSeleccionados = $_POST['servicios'] ?? [];
                if (empty($serviciosSeleccionados)) {
                    throw new Exception("Debes seleccionar al menos un tratamiento o consulta dental.");
                }

                $placeholders = implode(',', array_fill(0, count($serviciosSeleccionados), '?'));
                $stmtS = $this->db->prepare("SELECT * FROM servicios WHERE id IN ($placeholders)");
                $stmtS->execute(array_map('intval', $serviciosSeleccionados));
                $listaServicios = $stmtS->fetchAll();

                $totalBs = 0;
                $nombresTratamientos = [];
                foreach ($listaServicios as $srv) {
                    $totalBs += (float)$srv['precio_base'];
                    $nombresTratamientos[] = $srv['nombre'] . " ({$srv['precio_base']} Bs)";
                }

                $horaInicio = $_POST['hora_inicio'];
                $horaFin = date('H:i', strtotime($horaInicio . ' +45 minutes'));
                $modalidadPago = $_POST['modalidad_pago'] ?? 'QR Completo';
                $montoPagarAhora = ($modalidadPago === 'Reserva 50%') ? round($totalBs * 0.5, 2) : $totalBs;

                $citaModel = new Cita($this->db);
                $citaId = $citaModel->crear([
                    ':paciente_id' => $pacienteId,
                    ':odontologo_id' => (int)$_POST['odontologo_id'],
                    ':sillon_id' => 1,
                    ':servicio_id' => (int)$listaServicios[0]['id'],
                    ':fecha' => $_POST['fecha'],
                    ':hora_inicio' => $horaInicio,
                    ':hora_fin' => $horaFin,
                    ':motivo_consulta' => implode(' + ', $nombresTratamientos),
                    ':origen' => 'Portal Web',
                    ':notas' => "Modalidad: {$modalidadPago} | Total: {$totalBs} Bs | Obs: " . ($_POST['notas'] ?? ''),
                    ':creado_por' => null
                ]);

                // Crear automáticamente el presupuesto en el sistema clínico
                $stmtPres = $this->db->prepare("INSERT INTO presupuestos (paciente_id, odontologo_id, titulo, total, abonado, estado) VALUES (?, ?, ?, ?, 0, 'Pendiente Pago QR')");
                $stmtPres->execute([$pacienteId, (int)$_POST['odontologo_id'], 'Solicitud Online #' . $citaId, $totalBs]);
                $presId = $this->db->lastInsertId();

                $stmtItem = $this->db->prepare("INSERT INTO presupuesto_items (presupuesto_id, pieza_fdi, tratamiento, costo, estado_item) VALUES (?, 'Por evaluar', ?, ?, 'Pendiente')");
                foreach ($listaServicios as $srv) {
                    $stmtItem->execute([$presId, $srv['nombre'], $srv['precio_base']]);
                }

                Auth::registrarAuditoria($this->db, 'citas', $citaId, 'WEB_RESERVA', "Reserva online de {$_POST['nombres']} {$_POST['apellidos']} por {$totalBs} Bs");

                $resumenReserva = [
                    'cita_id' => $citaId,
                    'paciente' => trim($_POST['nombres'] . ' ' . $_POST['apellidos']),
                    'ci' => $ci,
                    'telefono' => trim($_POST['telefono']),
                    'fecha' => $_POST['fecha'],
                    'hora' => $horaInicio,
                    'tratamientos' => $listaServicios,
                    'total_bs' => $totalBs,
                    'pagar_ahora_bs' => $montoPagarAhora,
                    'modalidad' => $modalidadPago
                ];
                $mensaje = "¡Solicitud de tratamiento registrada! Escanea el QR para confirmar tu turno.";
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        require dirname(__DIR__) . '/views/publico/reservar_online.php';
    }
}
