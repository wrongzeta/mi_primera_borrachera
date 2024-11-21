<?php
require 'conexion.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$rol = $_POST['rol'];
$sede = $_POST['sede'];

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, rol, sede_id) 
        VALUES ('$nombre', '$rol', (SELECT id FROM sedes WHERE nombre = '$sede'))";

if ($conn->query($sql) === TRUE) {
    echo "Usuario creado exitosamente.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
