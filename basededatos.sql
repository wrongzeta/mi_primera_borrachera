-- Crear base de datos
CREATE DATABASE mi_primera_borrachera;

-- Usar la base de datos
USE mi_primera_borrachera;

-- Tabla para almacenar roles de usuario
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
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
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'cerrado') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
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

-- Insertar rol de usuario (ejemplo)
INSERT INTO roles (nombre) VALUES ('mesero'), ('cajero'), ('administrador');

-- Insertar usuario con contraseña hasheada
INSERT INTO usuarios (username, password, rol_id) VALUES ('usuario1', '$2y$10$D2lH0H/2hWl2zTeZT4vKfO0ZqBkJZPQ7xB9D2zRgCsdB3Cz60FWZi', 1);
