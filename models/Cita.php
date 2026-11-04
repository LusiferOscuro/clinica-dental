<?php
namespace models;

class Cita {
    public static function listar(): array {
        return $_SESSION['citas'] ?? [];
    }

    public static function guardar(array $datos): void {
        $id = isset($datos['id']) && !empty($datos['id']) ? (int)$datos['id'] : 0;
        
        if ($id > 0 && isset($_SESSION['citas'][$id])) {
            $_SESSION['citas'][$id] = [
                'id' => $id,
                'paciente' => trim($datos['paciente']),
                'servicio' => trim($datos['servicio']),
                'fecha' => trim($datos['fecha']),
                'estado' => trim($datos['estado'])
            ];
        } else {
            $nuevoId = count($_SESSION['citas']) > 0 ? max(array_keys($_SESSION['citas'])) + 1 : 101;
            $_SESSION['citas'][$nuevoId] = [
                'id' => $nuevoId,
                'paciente' => trim($datos['paciente']),
                'servicio' => trim($datos['servicio']),
                'fecha' => trim($datos['fecha']),
                'estado' => trim($datos['estado'])
            ];
        }
    }

    public static function eliminar(int $id): void {
        if (isset($_SESSION['citas'][$id])) {
            unset($_SESSION['citas'][$id]);
        }
    }
}
