<?php
require 'conex.php'; // Asegúrate de que este archivo define la variable $conexion

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $sede = $_POST['sede'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        echo "<p style='color: red;'>Las contraseñas no coinciden.</p>";
        exit;
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Obtener IDs de rol y sede
    $query_rol = "SELECT id FROM roles WHERE nombre = ?";
    $stmt_rol = $conexion->prepare($query_rol);
    $stmt_rol->bind_param("s", $rol);
    $stmt_rol->execute();
    $stmt_rol->bind_result($rol_id);
    $stmt_rol->fetch();
    $stmt_rol->close();

    $query_sede = "SELECT id FROM sedes WHERE nombre = ?";
    $stmt_sede = $conexion->prepare($query_sede);
    $stmt_sede->bind_param("s", $sede);
    $stmt_sede->execute();
    $stmt_sede->bind_result($sede_id);
    $stmt_sede->fetch();
    $stmt_sede->close();

    // Insertar el usuario en la base de datos
    $query = "INSERT INTO usuarios (username, password, rol_id, sede_id) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssii", $nombre, $hashed_password, $rol_id, $sede_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Usuario creado exitosamente.</p>";
    } else {
        echo "<p style='color: red;'>Error al crear el usuario: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conexion->close();
}
?>
