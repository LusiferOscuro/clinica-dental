-- Base de Datos: clinica_db
CREATE DATABASE IF NOT EXISTS clinica_db;
USE clinica_db;

CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) DEFAULT 'General',
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente VARCHAR(100) NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    fecha DATETIME NOT NULL,
    estado VARCHAR(20) DEFAULT 'Confirmada'
);
