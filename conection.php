<?php
$host = 'localhost';  // Dirección del servidor de la base de datos
$dbname = 'mi_primera_borrachera';  // Nombre de la base de datos
$username = 'root';  // Usuario de la base de datos (usualmente 'root' en XAMPP)
$password = '';  // Contraseña del usuario (en XAMPP generalmente está vacía)

// Crear la conexión
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}
?>
