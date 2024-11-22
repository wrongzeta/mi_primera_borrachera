<?php
require 'conex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Modificar mesas existentes
    if (isset($_POST['guardar'])) {
        foreach ($_POST['guardar'] as $mesa_id => $value) {
            $numero = $_POST['numero'][$mesa_id];
            $estado = $_POST['estado'][$mesa_id];
            $sede_id = $_POST['sede'][$mesa_id];

            $query = "UPDATE mesas SET numero = ?, estado = ?, sede_id = ? WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("isii", $numero, $estado, $sede_id, $mesa_id);

            if ($stmt->execute()) {
                echo "Mesa actualizada correctamente.";
            } else {
                echo "Error al actualizar la mesa: " . $conexion->error;
            }
            $stmt->close();
        }
    }

    // Eliminar mesa
    if (isset($_POST['eliminar'])) {
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
    }

    header("Location: admin.php");
    exit;
}
?>
