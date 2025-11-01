-- Base de datos para Med Tools Hub
-- Ejecutar este script en phpMyAdmin de tu hosting

-- Crear base de datos (si no existe)
-- CREATE DATABASE IF NOT EXISTS medtools_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE medtools_hub;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT '',
    institucion VARCHAR(150) DEFAULT '',
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    cat VARCHAR(100) DEFAULT '',
    avatar TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de medicamentos (Vademecum)
CREATE TABLE IF NOT EXISTS medications (
    id VARCHAR(36) PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    grupo VARCHAR(100) NOT NULL,
    dilucion TEXT,
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_grupo (grupo),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de anuncios globales
CREATE TABLE IF NOT EXISTS announcements (
    id VARCHAR(36) PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    fecha DATE NOT NULL,
    texto TEXT,
    img TEXT,
    is_global BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de guías clínicas
CREATE TABLE IF NOT EXISTS guides (
    id VARCHAR(36) PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    fecha DATE NOT NULL,
    texto TEXT,
    url TEXT,
    is_global BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de sugerencias
CREATE TABLE IF NOT EXISTS suggestions (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    mensaje TEXT NOT NULL,
    respuesta TEXT,
    fecha TIMESTAMP NOT NULL,
    respondida BOOLEAN DEFAULT FALSE,
    fecha_respuesta TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_respondida (respondida),
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tokens para reset de contraseña
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Contraseña: 1234 (cambiar después de la primera conexión)
INSERT INTO users (username, password, name, email, role, status, cat, institucion) VALUES
('admin', '$2y$10$AqyxiMJ9OT3bwmBc7BgrG.inDHhA8FM8w.w51Uk.Zof2oBOR9aGl2', 'Administrador', 'admin@medtoolshub.local', 'admin', 'aprobado', 'Pediatra', 'Med Tools Hub')
ON DUPLICATE KEY UPDATE username=username;

-- Insertar algunos medicamentos de ejemplo
INSERT INTO medications (id, nombre, grupo, dilucion, comentarios) VALUES
('4d6c619c-3fd2-4d1b-a397-c4975e77d260', 'Adrenalina', 'Vasopresores', '1 ampolla (1mg/1ml) en 9ml SF = 0.1mg/ml', 'Dosis: 0.01-0.03 mg/kg IV. RCP: 0.01-0.03 mg/kg cada 3-5 min'),
('3f1d43ad-0161-4f62-b5ac-6a528b50cb78', 'Amikacina', 'Antibióticos', 'Diluir en SF o SG 5% para infusión', 'Neonatos: 15-20 mg/kg/día c/24h. Niños: 15-22.5 mg/kg/día dividido c/8-12h'),
('3e7e91a2-f327-4c49-9ffa-296f450d8dc8', 'Ampicilina', 'Antibióticos', 'Reconstituir con agua estéril, diluir en SF o SG 5%', 'Neonatos <7 días: 50-100 mg/kg c/12h. >7 días: 50-100 mg/kg c/8h. Meningitis: dosis más altas'),
('c0e16ac6-e628-46b7-a72d-0c2f17e12c42', 'Cafeína', 'Estimulantes SNC', 'Citrato de cafeína 20mg/ml (oral o IV)', 'Carga: 20mg/kg. Mantenimiento: 5-10mg/kg/día. Para apnea del prematuro'),
('d1cd596d-185f-4965-99bc-ef309f5989a2', 'Cefotaxima', 'Antibióticos', 'Reconstituir y diluir en SF o SG 5%', 'Neonatos: 50mg/kg c/8-12h. Niños: 50-100mg/kg/día dividido c/6-8h. Meningitis: hasta 200mg/kg/día'),
('f5d9f2ee-f558-4dae-8601-b4e9060cce4f', 'Dexametasona', 'Corticoides', 'Puede diluirse en SF o SG 5%', 'Antiinflamatorio: 0.15-0.6 mg/kg/día. Edema cerebral: 0.5-1 mg/kg dosis inicial'),
('db054f0b-95d3-4929-9c0c-053c512ecf29', 'Dobutamina', 'Inotrópicos', '1 ampolla (250mg/20ml) + SF hasta 50ml = 5mg/ml', 'Dosis: 2-20 mcg/kg/min en infusión continua. Ajustar según respuesta hemodinámica'),
('1f7d8374-ecf7-46de-93ef-4cc6acee8e98', 'Dopamina', 'Vasopresores', '1 ampolla (200mg/5ml) + SF hasta 50ml = 4mg/ml', 'Dosis baja (2-5 mcg/kg/min): renal. Media (5-10): inotrópico. Alta (>10): vasopresor'),
('6d59bbc9-a6d8-474a-bbe0-417574dca8cb', 'Fentanilo', 'Analgésicos', 'Diluir en SF, concentración típica 10-50 mcg/ml', 'Analgesia: 1-2 mcg/kg IV. Sedación: 1-5 mcg/kg/h en infusión continua'),
('0d5bf362-56a0-4e87-b3a3-7de6b200c2b0', 'Furosemida', 'Diuréticos', 'Puede administrarse directo IV o diluido en SF', 'Neonatos: 1-2 mg/kg/dosis c/12-24h. Niños: 1-2 mg/kg/dosis c/6-12h'),
('2ccf813b-7b68-4c7f-97ca-19d0565353df', 'Gentamicina', 'Antibióticos', 'Diluir en SF o SG 5% para infusión 30-60 min', 'Neonatos: 4-5 mg/kg/día c/24-48h según edad. Niños: 5-7.5 mg/kg/día c/8h'),
('346a4ccf-9915-407b-93c6-51171c9d4a77', 'Hidrocortisona', 'Corticoides', 'Reconstituir con agua estéril, puede diluirse en SF', 'Insuficiencia suprarrenal: 1-2 mg/kg c/6-8h. Shock: 50-100 mg/m²/día'),
('35b3547d-ac22-490d-be88-c42eebfd1ae0', 'Midazolam', 'Sedantes', 'Puede diluirse en SF o SG 5%', 'Sedación: 0.05-0.15 mg/kg IV. Infusión continua: 1-6 mcg/kg/min'),
('7c06a73b-52b4-449a-965b-48eab667e9db', 'Morfina', 'Analgésicos', 'Diluir en SF, concentración típica 0.1-1 mg/ml', 'Analgesia: 0.05-0.2 mg/kg c/2-4h IV. Infusión: 10-40 mcg/kg/h'),
('9f58634b-6355-4669-9581-dde26e0d5f07', 'Surfactante', 'Pulmonares', 'Listo para usar intratraqueal', 'Dosis: 100-200 mg/kg intratraqueal. Puede repetirse según protocolo'),
('5567a90a-9c6a-4ce8-a797-51048666f661', 'Vancomicina', 'Antibióticos', 'Reconstituir y diluir en SF o SG 5%, infusión ≥60 min', 'Neonatos: 10-15 mg/kg c/8-24h según edad. Niños: 10-15 mg/kg c/6-8h. Monitorear niveles')
ON DUPLICATE KEY UPDATE id=id;

-- Insertar anuncio de bienvenida
INSERT INTO announcements (id, titulo, fecha, texto, is_global) VALUES
('d1e6d0eb-308f-40ec-b0d8-fea015dd5884', 'Bienvenidos a NBR WEB', '2025-10-06', 'Plataforma médica para profesionales de pediatría y neonatología.', TRUE)
ON DUPLICATE KEY UPDATE id=id;

-- Insertar guía de ejemplo
INSERT INTO guides (id, titulo, fecha, texto, is_global) VALUES
('4e5f1335-669b-4300-96b3-882313e5b00a', 'Guía RCP Neonatal 2024', '2025-10-06', 'Protocolo actualizado de reanimación cardiopulmonar neonatal.', TRUE)
ON DUPLICATE KEY UPDATE id=id;
