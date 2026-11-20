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

            if (!$host && getenv('RENDER')) {
                try {
                    $sqlitePath = sys_get_temp_dir() . '/clinica_dental_s21.sqlite';
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
            fecha_nacimiento TEXT, sexo TEXT, telefono_whatsapp TEXT, email TEXT, direccion TEXT,
            alergias TEXT DEFAULT 'Ninguna', condiciones_medicas TEXT DEFAULT 'Ninguna', medicacion_actual TEXT DEFAULT 'Ninguna'
        );
        INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp, email, direccion, alergias, condiciones_medicas, medicacion_actual) VALUES
        ('8945120', 'Juan Pablo', 'Gutiérrez Arce', '1998-05-14', 'M', '+591 76091506', 'jp@mail.com', 'Av. Cristo Redentor', 'Penicilina', 'Ninguna', 'Ninguna'),
        ('7412589', 'María Fernanda', 'Salvatierra Paz', '2002-11-22', 'F', '+591 75512345', 'mafer@mail.com', 'Equipetrol', 'Ninguna', 'Hipertensión controlada', 'Losartán 50mg');

        CREATE TABLE servicios (id INTEGER PRIMARY KEY AUTOINCREMENT, codigo TEXT, categoria TEXT, nombre TEXT, precio_base REAL, duracion_minutos INTEGER, activo INTEGER DEFAULT 1);
        INSERT INTO servicios (codigo, categoria, nombre, precio_base, duracion_minutos) VALUES
        ('DIAG-01','Diagnóstico','Consulta y Diagnóstico Integral',50,30),
        ('PER-01','Periodoncia','Profilaxis Dental con Ultrasonido',150,45),
        ('OPE-01','Operatoria','Restauración Resina Fotopolimerizable',180,45),
        ('END-01','Endodoncia','Endodoncia Unirradicular',650,60),
        ('ORT-01','Ortodoncia','Tratamiento Ortodoncia Metálica (Completo)',4500,45),
        ('PRO-01','Prótesis','Corona de Zirconio Monolítico',1400,60),
        ('CIR-01','Cirugía','Exodoncia Simple',150,30);

        CREATE TABLE sillones (id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT, estado TEXT DEFAULT 'Disponible');
        INSERT INTO sillones (nombre) VALUES ('Sillón 1 - General'), ('Sillón 2 - Especialidades'), ('Sillón 3 - Cirugía');

        CREATE TABLE citas (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER, sillon_id INTEGER,
            servicio_id INTEGER, fecha TEXT, hora_inicio TEXT, hora_fin TEXT, motivo_consulta TEXT,
            estado TEXT DEFAULT 'Pendiente', origen TEXT DEFAULT 'Recepción', notas TEXT, creado_por INTEGER
        );
        INSERT INTO citas (paciente_id, odontologo_id, sillon_id, servicio_id, fecha, hora_inicio, hora_fin, motivo_consulta, estado, origen) VALUES
        (1, 2, 1, 4, date('now'), '09:30', '10:30', 'Dolor molar superior derecho', 'Confirmada', 'Recepción'),
        (2, 2, 2, 2, date('now'), '11:00', '11:45', 'Control semestral y limpieza', 'En sala de espera', 'Portal Web');

        CREATE TABLE odontograma_registros (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER,
            tipo_denticion TEXT DEFAULT 'Adulto', pieza_fdi TEXT, cara TEXT, estado_hallazgo TEXT,
            observacion TEXT, fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        INSERT INTO odontograma_registros (paciente_id, odontologo_id, tipo_denticion, pieza_fdi, cara, estado_hallazgo, observacion) VALUES
        (1, 2, 'Adulto', '16', 'O', 'Caries', 'Caries profunda oclusal'),
        (1, 2, 'Adulto', '21', 'M', 'Resina_Buena', 'Restauración estética previa');

        CREATE TABLE presupuestos (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER,
            titulo TEXT, total REAL DEFAULT 0, abonado REAL DEFAULT 0, estado TEXT DEFAULT 'Aprobado',
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        INSERT INTO presupuestos (paciente_id, odontologo_id, titulo, total, abonado, estado) VALUES
        (1, 2, 'Rehabilitación Integral Molar 16 + Profilaxis', 800, 350, 'En proceso'),
        (2, 2, 'Ortodoncia Correctiva Integral', 4500, 1500, 'En proceso');

        CREATE TABLE presupuesto_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT, presupuesto_id INTEGER, pieza_fdi TEXT,
            tratamiento TEXT, costo REAL, estado_item TEXT DEFAULT 'Pendiente'
        );
        INSERT INTO presupuesto_items (presupuesto_id, pieza_fdi, tratamiento, costo, estado_item) VALUES
        (1, '16', 'Endodoncia Unirradicular', 650, 'En proceso'),
        (1, 'General', 'Profilaxis Dental con Ultrasonido', 150, 'Terminado'),
        (2, 'Arcada Sup/Inf', 'Instalación Brackets + Controles', 4500, 'En proceso');

        CREATE TABLE caja_movimientos (
            id INTEGER PRIMARY KEY AUTOINCREMENT, tipo TEXT, categoria TEXT, monto REAL,
            metodo_pago TEXT, descripcion TEXT, paciente_id INTEGER, presupuesto_id INTEGER,
            usuario_id INTEGER, fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        INSERT INTO caja_movimientos (tipo, categoria, monto, metodo_pago, descripcion, paciente_id, presupuesto_id, usuario_id) VALUES
        ('Ingreso','Abono Tratamiento',350,'QR','Abono inicial Endodoncia Pieza 16 - Juan Pablo Gutiérrez',1,1,1),
        ('Ingreso','Abono Ortodoncia',1500,'Transferencia','Cuota inicial Ortodoncia - María Fernanda Salvatierra',2,2,1),
        ('Egreso','Laboratorio Dental',280,'Efectivo','Anticipo Corona Zirconio Lab Dental Sur',NULL,NULL,1);

        CREATE TABLE insumos (
            id INTEGER PRIMARY KEY AUTOINCREMENT, codigo TEXT, nombre TEXT, categoria TEXT,
            unidad_medida TEXT, stock_actual INTEGER, stock_minimo INTEGER, fecha_vencimiento TEXT, costo_unitario REAL
        );
        INSERT INTO insumos (codigo, nombre, categoria, unidad_medida, stock_actual, stock_minimo, fecha_vencimiento, costo_unitario) VALUES
        ('INS-01','Lidocaína 2% con Epinefrina (Caja x50)','Anestesia','Caja',10,4,'2027-08-30',140),
        ('INS-02','Guantes Nitrilo Talla M (Caja x100)','Bioseguridad','Caja',2,5,'2028-01-10',45),
        ('INS-03','Resina Fotopolimerizable A2 3M','Operatoria','Jeringa',6,3,'2027-05-15',190),
        ('INS-04','Hipoclorito de Sodio 5.25% 1L','Endodoncia','Frasco',1,3,'2026-12-20',35);

        CREATE TABLE laboratorio_ordenes (
            id INTEGER PRIMARY KEY AUTOINCREMENT, paciente_id INTEGER, odontologo_id INTEGER,
            laboratorio_nombre TEXT, trabajo_solicitado TEXT, pieza_fdi TEXT, color_guia TEXT,
            fecha_envio TEXT, fecha_entrega_prometida TEXT, costo REAL, estado TEXT DEFAULT 'En Laboratorio'
        );
        INSERT INTO laboratorio_ordenes (paciente_id, odontologo_id, laboratorio_nombre, trabajo_solicitado, pieza_fdi, color_guia, fecha_envio, fecha_entrega_prometida, costo, estado) VALUES
        (1, 2, 'Lab Dental Protesis Sur', 'Corona de Zirconio Monolítico', '16', 'A2', date('now','-2 days'), date('now','+3 days'), 450, 'En Laboratorio');

        CREATE TABLE auditoria_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT, usuario_id INTEGER, tabla_afectada TEXT,
            registro_id INTEGER, accion TEXT, detalle TEXT, ip_origen TEXT,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        INSERT INTO auditoria_logs (usuario_id, tabla_afectada, registro_id, accion, detalle, ip_origen) VALUES
        (1, 'sistema', 1, 'INIT', 'Inicialización de Clínica Dental Pro Siglo XXI', '127.0.0.1');
        ";
        $pdo->exec($sql);
    }
}
