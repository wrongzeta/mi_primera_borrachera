-- Crear base de datos
CREATE DATABASE mi_primera_borrachera;

-- Usar la base de datos
USE mi_primera_borrachera;

-- Tabla de sedes
CREATE TABLE sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT,
    sede_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id),
    FOREIGN KEY (sede_id) REFERENCES sedes(id)
);

-- Tabla de mesas
CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    estado ENUM('libre', 'ocupada') DEFAULT 'libre',
    sede_id INT,
    FOREIGN KEY (sede_id) REFERENCES sedes(id)
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    costo_venta DECIMAL(10, 2) NOT NULL, 
    precio_venta DECIMAL(10, 2) NOT NULL 
);

-- Tabla de inventarios
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    sede_id INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (sede_id) REFERENCES sedes(id),
    UNIQUE (producto_id, sede_id)
);

-- Tabla de pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    mesa_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'cerrado') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

-- Tabla de detalles de pedido
CREATE TABLE detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    ip_address VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN NOT NULL
);


-- Insertar roles
INSERT INTO roles (nombre) VALUES ('mesero'), ('cajero'), ('admin');

-- Insertar sedes
INSERT INTO sedes (nombre) VALUES ('Restrepo'), ('Primera de Mayo'), ('Galerías'), ('Chía');

-- Insertar mesas con nombre
INSERT INTO mesas (numero, estado, sede_id, nombre) VALUES 
(1, 'libre', 1, 'Mesa 1'),
(2, 'libre', 1, 'Mesa 2'),
(3, 'libre', 2, 'Mesa 3'),
(4, 'libre', 2, 'Mesa 4'),
(5, 'libre', 3, 'Mesa 5'),
(6, 'libre', 3, 'Mesa 6'),
(7, 'libre', 4, 'Mesa 7'),
(8, 'libre', 4, 'Mesa 8');

-- Insertar usuarios con contraseñas hasheadas
INSERT INTO usuarios (username, password, rol_id, sede_id) VALUES
('admin', '$2y$10$mvKM8u6CpdPyYujOrYOm6.kdibfG700P7l5jBIKxhT/wta1ANtFm.', 3, 1), -- Contraseña: admin2024
('David', '$2y$10$g5PVsK55BXOvk1QfiY8nNe3BxoEcJXb6TutaOEopEdbbUaJVbzX.W', 1, 1), -- Contraseña: Juanda2020
('JuanDavid', '$2y$10$bxabe.hMXbfnMStpY7Rz9.xXQV8/4HFKoeRstpzcWv0sKfYocIA.m', 2, 1), -- Contraseña: DavidContra123
('Carlos', '$2y$10$0R.8Jf/8nHrAzeqWUEvtoehy8HGG47cjCyvLMBm/nK97vsQGOMkUO', 1, 2), -- Contraseña: CarlosMesero2014
('Sofia', '$2y$10$WoBjDNcEiqcruEYsTfUfb.qgTtAtB8v/uExGwZLu.arvR2vJDJZzO', 1, 3); -- Contraseña: Sofiase2024

-- Insertar productos
INSERT INTO productos (id, nombre, precio, imagen, costo_venta, precio_venta) VALUES
(25, 'Aguardiente Antioqueño', 50000.00, 'imagenes/aguardiente_antioqueñoazul.jpg', 30000.00, 50000.00),
(26, 'Ron Medellín', 60000.00, 'imagenes/ron_medellin.jpg', 40000.00, 60000.00),
(27, 'Cerveza Club Colombia', 8000.00, 'imagenes/club_colombia.jpg', 4000.00, 8000.00),
(28, 'Whisky Johnnie Walker Black Label', 120000.00, 'imagenes/johnnie_walker.jpg', 80000.00, 120000.00),
(29, 'Tequila José Cuervo', 90000.00, 'imagenes/jose_cuervo.jpg', 60000.00, 90000.00),
(30, 'Vodka Absolut', 70000.00, 'imagenes/absolut.jpg', 40000.00, 70000.00),
(31, 'Cigarros Marlboro', 12000.00, 'imagenes/marlboro.jpg', 7000.00, 12000.00),
(32, 'Cigarros Lucky', 11000.00, 'imagenes/lucky.jpg', 6000.00, 11000.00),
(33, 'Paquete de Tabaco Pielroja', 6000.00, 'imagenes/pielroja.jpg', 3000.00, 6000.00),
(34, 'Cerveza Águila', 7000.00, 'imagenes/aguila.jpg', 3500.00, 7000.00),
(35, 'Cerveza Poker', 6500.00, 'imagenes/poker.jpg', 3200.00, 6500.00),
(36, 'Cerveza Corona', 10000.00, 'imagenes/corona.jpg', 5000.00, 10000.00);

-- Insertar inventarios
INSERT INTO inventarios (producto_id, sede_id, cantidad) VALUES
(25, 1, 50), (25, 2, 40), (25, 3, 60), (25, 4, 30),
(26, 1, 70), (26, 2, 50), (26, 3, 80), (26, 4, 60),
(27, 1, 100), (27, 2, 90), (27, 3, 110), (27, 4, 95),
(28, 1, 10), (28, 2, 5), (28, 3, 15), (28, 4, 7),
(29, 1, 20), (29, 2, 18), (29, 3, 25), (29, 4, 22),
(30, 1, 35), (30, 2, 30), (30, 3, 40), (30, 4, 28),
(31, 1, 60), (31, 2, 55), (31, 3, 65), (31, 4, 50),
(32, 1, 70), (32, 2, 65), (32, 3, 80), (32, 4, 72),
(33, 1, 50), (33, 2, 45), (33, 3, 55), (33, 4, 48),
(34, 1, 120), (34, 2, 115), (34, 3, 125), (34, 4, 110),
(35, 1, 130), (35, 2, 125), (35, 3, 140), (35, 4, 118),
(36, 1, 90), (36, 2, 85), (36, 3, 100), (36, 4, 88);
