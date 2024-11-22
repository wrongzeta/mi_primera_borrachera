<?php
require 'conex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['username'] as $id => $username) {
        $new_password = $_POST['password'][$id];
        $rol_id = $_POST['rol'][$id];
        $sede_id = $_POST['sede'][$id];

        // Actualizar datos del usuario
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $query = "UPDATE usuarios 
                      SET username = ?, password = ?, rol_id = ?, sede_id = ? 
                      WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('sssii', $username, $hashed_password, $rol_id, $sede_id, $id);
        } else {
            $query = "UPDATE usuarios 
                      SET username = ?, rol_id = ?, sede_id = ? 
                      WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('siii', $username, $rol_id, $sede_id, $id);
        }

        $stmt->execute();
    }

    header('Location: admin.php');
    exit;
}
?>
