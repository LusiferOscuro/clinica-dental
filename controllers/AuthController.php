<?php
class AuthController {
    private $db;
    public function __construct() { $this->db = Database::getConnection(); }

    public function login() {
        Auth::iniciarSesion();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['password'] ?? '';
            $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id WHERE u.email = ? AND u.estado = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($pass, $user['password_hash'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre_completo'];
                $_SESSION['rol'] = $user['rol'];
                Auth::registrarAuditoria($this->db, 'usuarios', $user['id'], 'LOGIN', 'Inicio de sesión exitoso');
                header('Location: index.php?controller=home&action=index');
                exit;
            } else {
                $error = "Credenciales incorrectas o usuario inactivo.";
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        Auth::iniciarSesion();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
