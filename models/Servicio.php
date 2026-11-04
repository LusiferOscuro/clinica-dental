<?php
namespace models;

class Servicio {
    public static function listar(): array {
        return $_SESSION['servicios'] ?? [];
    }

    public static function guardar(array $datos): void {
        $id = isset($datos['id']) && !empty($datos['id']) ? (int)$datos['id'] : 0;
        
        if ($id > 0 && isset($_SESSION['servicios'][$id])) {
            $_SESSION['servicios'][$id] = [
                'id' => $id,
                'nombre' => trim($datos['nombre']),
                'categoria' => trim($datos['categoria'] ?? 'General'),
                'descripcion' => trim($datos['descripcion']),
                'precio' => trim($datos['precio'])
            ];
        } else {
            $nuevoId = count($_SESSION['servicios']) > 0 ? max(array_keys($_SESSION['servicios'])) + 1 : 1;
            $_SESSION['servicios'][$nuevoId] = [
                'id' => $nuevoId,
                'nombre' => trim($datos['nombre']),
                'categoria' => trim($datos['categoria'] ?? 'General'),
                'descripcion' => trim($datos['descripcion']),
                'precio' => trim($datos['precio'])
            ];
        }
    }

    public static function eliminar(int $id): void {
        if (isset($_SESSION['servicios'][$id])) {
            unset($_SESSION['servicios'][$id]);
        }
    }
}
