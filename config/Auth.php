<?php
class Auth {
    public static function iniciarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function verificarAcceso(array $rolesPermitidos = []) {
        self::iniciarSesion();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        if (!empty($rolesPermitidos) && !in_array($_SESSION['rol'], $rolesPermitidos)) {
            http_response_code(403);
            die("Acceso denegado: No tienes permisos para entrar a este módulo.");
        }
    }

    public static function registrarAuditoria($db, $tabla, $registroId, $accion, $detalle) {
        self::iniciarSesion();
        $stmt = $db->prepare("INSERT INTO auditoria_logs (usuario_id, tabla_afectada, registro_id, accion, detalle, ip_origen) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['usuario_id'] ?? null,
            $tabla,
            $registroId,
            $accion,
            $detalle,
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    }
}
