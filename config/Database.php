<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $host     = getenv('DB_HOST');
            $port     = getenv('DB_PORT') ?: '3306';
            $db_name  = getenv('DB_NAME') ?: 'clinica_dental_db';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: '';

            // Si estamos en Render y aún no configuraron variables DB_HOST, usamos SQLite embebido auto-inicializado
            // para que la página abra sin errores en la defensa y guarde datos reales.
            if (!$host && getenv('RENDER')) {
                try {
                    $sqlitePath = sys_get_temp_dir() . '/clinica_dental.sqlite';
                    $isNew = !file_exists($sqlitePath);
                    self::$conn = new PDO("sqlite:" . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                    if ($isNew) {
                        self::inicializarSQLite(self::$conn);
                    }
                    return self::$conn;
                } catch (PDOException $e) {
                    die("Error SQLite: " . $e->getMessage());
                }
            }

            $host = $host ?: '127.0.0.1';
            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
                self::$conn = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                die("Error de conexión a MySQL: " . $e->getMessage());
            }
        }
        return self::$conn;
    }

    private static function inicializarSQLite($pdo) {
        $sql = "
        CREATE TABLE roles (id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT UNIQUE, descripcion TEXT);
        INSERT INTO roles (nombre, descripcion) VALUES ('admin','Administrador'),('odontologo','Odontólogo'),('recepcionista','Recepción');

        CREATE TABLE usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT, rol_id INTEGER, nombre_completo TEXT, email TEXT UNIQUE,
            password_hash TEXT, telefono TEXT, especialidad TEXT, matricula_profesional TEXT, estado INTEGER DEFAULT 1
        );
        INSERT INTO usuarios (rol_id, nombre_completo, email, password_hash, telefono, especialidad, matricula_profesional) VALUES
        (1, 'Dr. Carlos Mendoza (Admin)', 'admin@clinicadental.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000001', 'Dirección Clínica', 'MP-1020'),
        (2, 'Dra. Valeria Rojas', 'valeria@clinicadental.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000002', 'Endodoncia', 'MP-4589'),
        (3, 'Sofía Fernández', 'recepcion@clinicadental.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000004', 'Recepción', '');

        CREATE TABLE pacientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT, ci_nit TEXT UNIQUE, nombres TEXT, apellidos TEXT,
            fecha_nacimiento TEXT, sexo TEXT, telefono_whatsapp TEXT, email TEXT, direccion TEXT
        );
        INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp, email, direccion) VALUES
        ('8945120', 'Juan Pablo', 'Gutiérrez Arce', '1998-05-14', 'M', '+591 76091506', 'jp@mail.com', 'Av. Cristo Redentor'),
        ('7412589', 'María Fernanda', 'Salvatierra Paz', '2002-11-22', 'F', '+591 75512345', 'mafer@mail.com', 'Equipetrol');

        CREATE TABLE servicios (id INTEGER PRIMARY KEY AUTOINCREMENT, codigo TEXT, categoria TEXT, nombre TEXT, precio_base REAL, duracion_minutos INTEGER, activo INTEGER DEFAULT 1);
        INSERT INTO servicios (codigo, categoria, nombre, precio_base, duracion_minutos) VALUES
        ('DIAG-01','Diagnóstico','Consulta y Diagnóstico Integral',50,30),
        ('PER-01','Periodoncia','Profilaxis Dental con Ultrasonido',150,45),
        ('OPE-01','Operatoria','Restauración Resina Fotopolimerizable',180,45),
        ('END-01','Endodoncia','Endodoncia Unirradicular',650,60),
        ('CIR-01','Cirugía','Exodoncia Simple',150,30);

        CREATE TABLE sillones (id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT, estado TEXT DEFAULT 'Disponible');
        INSERT INTO sillones (nombre) VALUES ('Sillón 1 - General'), ('Sillón 2 - Especialidades');

        CREATE TABLE citas (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER, sillon_id INTEGER,
            servicio_id INTEGER, fecha TEXT, hora_inicio TEXT, hora_fin TEXT, motivo_consulta TEXT,
            estado TEXT DEFAULT 'Pendiente', origen TEXT DEFAULT 'Recepción', notas TEXT, creado_por INTEGER
        );

        CREATE TABLE odontograma_registros (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER,
            tipo_denticion TEXT DEFAULT 'Adulto', pieza_fdi TEXT, cara TEXT, estado_hallazgo TEXT,
            observacion TEXT, fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE caja_movimientos (
            id INTEGER PRIMARY KEY AUTOINCREMENT, tipo TEXT, categoria TEXT, monto REAL,
            metodo_pago TEXT, descripcion TEXT, pago_id INTEGER, usuario_id INTEGER,
            fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        INSERT INTO caja_movimientos (tipo, categoria, monto, metodo_pago, descripcion, usuario_id) VALUES
        ('Ingreso','Consulta',1250,'QR','Ingresos acumulados semana',1);

        CREATE TABLE insumos (
            id INTEGER PRIMARY KEY AUTOINCREMENT, codigo TEXT, nombre TEXT, unidad_medida TEXT,
            stock_actual INTEGER, stock_minimo INTEGER, fecha_vencimiento TEXT, costo_unitario REAL
        );
        INSERT INTO insumos (codigo, nombre, unidad_medida, stock_actual, stock_minimo, fecha_vencimiento, costo_unitario) VALUES
        ('INS-01','Lidocaína 2% Caja x50','Caja',10,4,'2027-08-30',140),
        ('INS-02','Guantes Nitrilo M','Caja',2,5,'2028-01-10',45);

        CREATE TABLE auditoria_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT, usuario_id INTEGER, tabla_afectada TEXT,
            registro_id INTEGER, accion TEXT, detalle TEXT, ip_origen TEXT,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        ";
        $pdo->exec($sql);
    }
}
