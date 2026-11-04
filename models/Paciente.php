<?php
namespace models;

class Paciente {
    public static function listar(): array {
        return $_SESSION['pacientes'] ?? [];
    }

    public static function guardar(array $datos): void {
        $id = isset($datos['id']) && !empty($datos['id']) ? (int)$datos['id'] : 0;
        
        if ($id > 0 && isset($_SESSION['pacientes'][$id])) {
            $_SESSION['pacientes'][$id] = [
                'id' => $id,
                'nombre' => trim($datos['nombre']),
                'telefono' => trim($datos['telefono']),
                'email' => trim($datos['email'])
            ];
        } else {
            $nuevoId = count($_SESSION['pacientes']) > 0 ? max(array_keys($_SESSION['pacientes'])) + 1 : 1;
            $_SESSION['pacientes'][$nuevoId] = [
                'id' => $nuevoId,
                'nombre' => trim($datos['nombre']),
                'telefono' => trim($datos['telefono']),
                'email' => trim($datos['email'])
            ];
        }
    }

    public static function eliminar(int $id): void {
        if (isset($_SESSION['pacientes'][$id])) {
            unset($_SESSION['pacientes'][$id]);
        }
    }
}
