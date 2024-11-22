<?php
require 'conex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $sede_id = $_POST['sede'];

    $query = "INSERT INTO mesas (numero, sede_id) VALUES (?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $numero, $sede_id);

    if ($stmt->execute()) {
        echo "Mesa añadida correctamente.";
    } else {
        echo "Error al añadir la mesa: " . $conexion->error;
    }
    $stmt->close();
    header("Location: admin.php");
    exit;
}
?>
