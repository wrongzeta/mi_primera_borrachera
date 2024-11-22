<?php
$servername = "localhost"; // Cambia esto si tu servidor no está en localhost
$username = "root";        // Usuario por defecto de XAMPP
$password = "";            // Contraseña por defecto en XAMPP
$dbname = "mi_primera_borrachera"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
