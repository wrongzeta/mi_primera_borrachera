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
    imagen VARCHAR(255) -- Campo para la ruta de la imagen
);

CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    sede_id INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (sede_id) REFERENCES sedes(id),
    UNIQUE (producto_id, sede_id) -- Asegura que cada producto esté registrado una sola vez por sede
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

-- Insertar roles
INSERT INTO roles (nombre) VALUES ('mesero'), ('cajero'), ('admin');

-- Insertar sedes
INSERT INTO sedes (nombre) VALUES ('Restrepo'), ('Primera de Mayo'), ('Galerías'), ('Chía');

--Primera ejecucion SQL hasta aca

-- Insertar mesas
INSERT INTO mesas (numero, estado, sede_id) VALUES 
(1, 'libre', 1),
(2, 'libre', 1),
(3, 'libre', 2),
(4, 'libre', 2),
(5, 'libre', 3),
(6, 'libre', 3),
(7, 'libre', 4),
(8, 'libre', 4);

-- Insertar usuarios con contraseñas hasheadas
INSERT INTO `usuarios` (`username`, `password`, `rol_id`, `sede_id`) VALUES
('admin', '$2y$10$mvKM8u6CpdPyYujOrYOm6.kdibfG700P7l5jBIKxhT/wta1ANtFm.', 3, 1), -- Contraseña: admin2024
('David', '$2y$10$g5PVsK55BXOvk1QfiY8nNe3BxoEcJXb6TutaOEopEdbbUaJVbzX.W', 1, 1), -- Contraseña: Juanda2020
('JuanDavid', '$2y$10$bxabe.hMXbfnMStpY7Rz9.xXQV8/4HFKoeRstpzcWv0sKfYocIA.m', 2, 1), -- Contraseña: DavidContra123
('Carlos', '$2y$10$0R.8Jf/8nHrAzeqWUEvtoehy8HGG47cjCyvLMBm/nK97vsQGOMkUO', 1, 2), -- Contraseña: Mesero2024
('Sofia', '$2y$10$WoBjDNcEiqcruEYsTfUfb.qgTtAtB8v/uExGwZLu.arvR2vJDJZzO', 1, 3); -- Contraseña: Sofia2024


INSERT INTO `productos` (`id`, `nombre`, `precio`, `imagen`) VALUES
(25, 'Aguardiente Antioqueño', 50000.00, 'imagenes/aguardiente_antioqueñoazul.jpg'),
(26, 'Ron Medellín', 60000.00, 'imagenes/ron_medellin.jpg'),
(27, 'Cerveza Club Colombia', 8000.00, 'imagenes/club_colombia.jpg'),
(28, 'Whisky Johnnie Walker Black Label', 120000.00, 'imagenes/johnnie_walker.jpg'),
(29, 'Tequila José Cuervo', 90000.00, 'imagenes/jose_cuervo.jpg'),
(30, 'Vodka Absolut', 70000.00, 'imagenes/absolut.jpg'),
(31, 'Cigarros Marlboro', 12000.00, 'imagenes/marlboro.jpg'),
(32, 'Cigarros Lucky', 11000.00, 0, 'imagenes/lucky.jpg'),
(33, 'Paquete de Tabaco Pielroja', 6000.00, 'imagenes/pielroja.jpg'),
(34, 'Cerveza Águila', 7000.00, 'imagenes/aguila.jpg'),
(35, 'Cerveza Poker', 6500.00, 'imagenes/poker.jpg'),
(36, 'Cerveza Corona', 10000.00, 'imagenes/corona.jpg');