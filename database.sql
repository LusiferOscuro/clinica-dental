-- ============================================================================
-- BASE DE DATOS INTEGRAL PARA CLÍNICA ODONTOLÓGICA (clinica_dental_db v2.0)
-- Motor: MySQL 8.0+ / MariaDB 10.6+ | Cotejamiento: utf8mb4_unicode_ci
-- ============================================================================

CREATE DATABASE IF NOT EXISTS clinica_dental_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_dental_db;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
);

INSERT INTO roles (nombre, descripcion) VALUES
('admin', 'Administrador general con acceso total a finanzas, usuarios y auditoría'),
('odontologo', 'Especialista clínico con acceso a agenda, anamnesis, odontograma y evolución'),
('recepcionista', 'Gestión de pacientes, citas, confirmación WhatsApp y cobros en caja');

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    nombre_completo VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(30),
    especialidad VARCHAR(100) NULL,
    matricula_profesional VARCHAR(50) NULL,
    estado TINYINT(1) DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Usuarios de prueba (Contraseña para todos: Admin123*)
INSERT INTO usuarios (rol_id, nombre_completo, email, password_hash, telefono, especialidad, matricula_profesional) VALUES
(1, 'Dr. Carlos Mendoza (Admin)', 'admin@clinicadental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000001', 'Dirección Clínica', 'MP-1020'),
(2, 'Dra. Valeria Rojas', 'valeria@clinicadental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000002', 'Endodoncia y Rehabilitación', 'MP-4589'),
(2, 'Dr. Andrés Torrico', 'andres@clinicadental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000003', 'Ortodoncia y Cirugía', 'MP-5120'),
(3, 'Sofía Fernández', 'recepcion@clinicadental.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+591 70000004', 'Atención al Paciente', NULL);

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci_nit VARCHAR(30) UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    sexo ENUM('M', 'F', 'Otro') NOT NULL,
    telefono_whatsapp VARCHAR(30) NOT NULL,
    email VARCHAR(120),
    direccion VARCHAR(255),
    contacto_emergencia_nombre VARCHAR(120),
    contacto_emergencia_telefono VARCHAR(30),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO pacientes (ci_nit, nombres, apellidos, fecha_nacimiento, sexo, telefono_whatsapp, email, direccion) VALUES
('8945120', 'Juan Pablo', 'Gutiérrez Arce', '1998-05-14', 'M', '+591 76091506', 'jp.gutierrez@mail.com', 'Av. Cristo Redentor 4to Anillo'),
('7412589', 'María Fernanda', 'Salvatierra Paz', '2002-11-22', 'F', '+591 75512345', 'mafer.paz@mail.com', 'Barrio Equipetrol Calle 5');

CREATE TABLE anamnesis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL UNIQUE,
    alergias TEXT,
    enfermedades_sistemicas TEXT,
    medicamentos_actuales TEXT,
    problemas_coagulacion TINYINT(1) DEFAULT 0,
    embarazo_lactancia TINYINT(1) DEFAULT 0,
    habitos TEXT,
    observaciones_medicas TEXT,
    actualizado_por INT,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (actualizado_por) REFERENCES usuarios(id)
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE,
    categoria ENUM('Diagnóstico', 'Operatoria', 'Endodoncia', 'Periodoncia', 'Cirugía', 'Ortodoncia', 'Prótesis', 'Estética') NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10,2) NOT NULL,
    duracion_minutos INT DEFAULT 30,
    activo TINYINT(1) DEFAULT 1
);

INSERT INTO servicios (codigo, categoria, nombre, precio_base, duracion_minutos) VALUES
('DIAG-01', 'Diagnóstico', 'Consulta, diagnóstico y plan de tratamiento', 50.00, 30),
('PER-01', 'Periodoncia', 'Profilaxis dental (Limpieza con ultrasonido)', 150.00, 45),
('OPE-01', 'Operatoria', 'Restauración con resina fotopolimerizable simple', 180.00, 45),
('END-01', 'Endodoncia', 'Endodoncia unirradicular', 650.00, 60),
('CIR-01', 'Cirugía', 'Exodoncia simple', 150.00, 30),
('CIR-02', 'Cirugía', 'Cirugía de tercer molar retenido', 550.00, 60),
('ORT-01', 'Ortodoncia', 'Control mensual de ortodoncia', 200.00, 30),
('PRO-01', 'Prótesis', 'Corona de Zirconio monolítico', 1400.00, 60);

CREATE TABLE sillones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    estado ENUM('Disponible', 'Mantenimiento', 'Inactivo') DEFAULT 'Disponible'
);

INSERT INTO sillones (nombre) VALUES ('Sillón 1 - General'), ('Sillón 2 - Especialidades'), ('Sillón 3 - Cirugía');

CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    odontologo_id INT NOT NULL,
    sillon_id INT NOT NULL,
    servicio_id INT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    motivo_consulta VARCHAR(255),
    estado ENUM('Pendiente', 'Confirmada', 'En sala de espera', 'En atención', 'Atendida', 'No asistió', 'Cancelada') DEFAULT 'Pendiente',
    recordatorio_enviado TINYINT(1) DEFAULT 0,
    origen ENUM('Recepción', 'Portal Web') DEFAULT 'Recepción',
    notas TEXT,
    creado_por INT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (odontologo_id) REFERENCES usuarios(id),
    FOREIGN KEY (sillon_id) REFERENCES sillones(id),
    FOREIGN KEY (servicio_id) REFERENCES servicios(id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

CREATE TABLE odontograma_registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    odontologo_id INT NOT NULL,
    tipo_denticion ENUM('Adulto', 'Pediatrica') DEFAULT 'Adulto',
    pieza_fdi VARCHAR(5) NOT NULL,
    cara ENUM('V', 'L', 'M', 'D', 'O', 'Completa') NOT NULL,
    estado_hallazgo ENUM('Sano', 'Caries', 'Resina_Buena', 'Resina_Defectuosa', 'Endodoncia', 'Corona', 'Implante', 'Extraccion_Indicada', 'Ausente', 'Protesis') NOT NULL,
    observacion VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (odontologo_id) REFERENCES usuarios(id)
);

CREATE TABLE presupuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    odontologo_id INT NOT NULL,
    fecha_emision DATE NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('Borrador', 'Aprobado', 'En progreso', 'Finalizado', 'Rechazado') DEFAULT 'Borrador',
    observaciones TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (odontologo_id) REFERENCES usuarios(id)
);

CREATE TABLE presupuesto_detalles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    presupuesto_id INT NOT NULL,
    servicio_id INT NOT NULL,
    pieza_fdi VARCHAR(5) NULL,
    cara VARCHAR(15) NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    estado_clinico ENUM('Pendiente', 'En proceso', 'Realizado') DEFAULT 'Pendiente',
    fecha_realizacion DATE NULL,
    FOREIGN KEY (presupuesto_id) REFERENCES presupuestos(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

CREATE TABLE evoluciones_clinicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    cita_id INT NULL,
    odontologo_id INT NOT NULL,
    pieza_fdi VARCHAR(5) NULL,
    procedimiento_realizado TEXT NOT NULL,
    medicacion_recetada TEXT,
    indicaciones_postoperatorias TEXT,
    fecha_atencion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (cita_id) REFERENCES citas(id),
    FOREIGN KEY (odontologo_id) REFERENCES usuarios(id)
);

CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    presupuesto_id INT NULL,
    numero_recibo VARCHAR(30) UNIQUE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('Efectivo', 'QR', 'Transferencia', 'Tarjeta') NOT NULL,
    referencia_transaccion VARCHAR(100) NULL,
    concepto VARCHAR(255) NOT NULL,
    estado ENUM('Pagado', 'Anulado') DEFAULT 'Pagado',
    registrado_por INT NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (presupuesto_id) REFERENCES presupuestos(id),
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id)
);

CREATE TABLE caja_movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Ingreso', 'Egreso') NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('Efectivo', 'QR', 'Transferencia', 'Tarjeta') NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    pago_id INT NULL,
    usuario_id INT NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pago_id) REFERENCES pagos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    unidad_medida VARCHAR(30) NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    fecha_vencimiento DATE NULL,
    costo_unitario DECIMAL(10,2) DEFAULT 0.00
);

INSERT INTO insumos (codigo, nombre, unidad_medida, stock_actual, stock_minimo, fecha_vencimiento, costo_unitario) VALUES
('INS-001', 'Lidocaína 2% con Epinefrina (Caja x50)', 'Caja', 12, 4, '2027-08-30', 140.00),
('INS-002', 'Resina Compuesta 3M Filtek Z350 A2', 'Jeringa', 8, 3, '2027-04-15', 210.00),
('INS-003', 'Guantes de Nitrilo Talla M (Caja x100)', 'Caja', 3, 5, '2028-01-10', 45.00);

CREATE TABLE insumo_movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    insumo_id INT NOT NULL,
    tipo ENUM('Entrada', 'Salida', 'Ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(200),
    usuario_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (insumo_id) REFERENCES insumos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE ordenes_laboratorio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    odontologo_id INT NOT NULL,
    laboratorio_nombre VARCHAR(120) NOT NULL,
    trabajo_solicitado VARCHAR(200) NOT NULL,
    color_guia_vita VARCHAR(20),
    fecha_envio DATE NOT NULL,
    fecha_entrega_estimada DATE NOT NULL,
    fecha_entrega_real DATE NULL,
    costo_laboratorio DECIMAL(10,2) DEFAULT 0.00,
    estado ENUM('Enviado', 'En proceso', 'Recibido en clínica', 'Entregado al paciente') DEFAULT 'Enviado',
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (odontologo_id) REFERENCES usuarios(id)
);

CREATE TABLE auditoria_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    tabla_afectada VARCHAR(60) NOT NULL,
    registro_id INT NOT NULL,
    accion ENUM('INSERT', 'UPDATE', 'DELETE', 'LOGIN') NOT NULL,
    detalle TEXT,
    ip_origen VARCHAR(45),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
