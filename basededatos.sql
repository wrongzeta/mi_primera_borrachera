-- Crear base de datos
CREATE DATABASE mi_primera_borrachera;

-- Usar la base de datos
USE mi_primera_borrachera;

-- Tabla para almacenar roles de usuario
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla para almacenar mesas
CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    estado ENUM('libre', 'ocupado') DEFAULT 'libre',
    sede VARCHAR(100) NOT NULL
);

-- Tabla para almacenar usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla para almacenar productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL
);

-- Tabla para almacenar pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    mesa_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'cerrado') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

-- Tabla para almacenar los detalles de cada pedido
CREATE TABLE detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Insertar roles
INSERT INTO roles (nombre) VALUES ('mesero'), ('cajero'), ('admin');

-- Insertar usuarios con contraseñas hasheadas
INSERT INTO usuarios (username, password, rol_id) 
VALUES 
('admin', '$2y$10$Df3c6aeGdhrzVf3RQlEvTu07S1BrXm.GIHYP7Nw2y2ozW9dj9x7/O', 3),  -- Contraseña: admin2024
('David', '$2y$10$P19EWo.PsWJoGe8aYl3.8eI3hHdwmY/sI5HptA7OdE0KE7xlzz6Au', 1),  -- Contraseña: David123
('JuanDavid', '$2y$10$U84isAvpzV4..eO/5gfLpO14evA2kGbhjE.lC2u2cvWt2P4J20FLO', 2);  -- Contraseña: Juanda2020 
