<?php
require 'conex.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['eliminar'] as $mesa_id => $value) {
        $query = "DELETE FROM mesas WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $mesa_id);

        if ($stmt->execute()) {
            echo "Mesa eliminada correctamente.";
        } else {
            echo "Error al eliminar la mesa: " . $conexion->error;
        }
        $stmt->close();
    }
    header("Location: admin.php");
    exit;
}
?>
