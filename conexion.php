<?php
// Datos de conexión
$host = 'localhost'; // Servidor
$dbname = 'mi_primera_borrachera'; // Base de datos
$username = 'root'; // Usuario por defecto en XAMPP
$password = ''; // Contraseña vacía por defecto

// Conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
