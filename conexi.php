<?php
$host = 'localhost'; // O el servidor de tu base de datos
$user = 'root'; // Usuario de la base de datos (por defecto en XAMPP es 'root')
$password = ''; // Contraseña (por defecto en XAMPP es una cadena vacía)
$dbname = 'mi_primera_borrachera'; // Nombre correcto de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
