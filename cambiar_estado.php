<?php
session_start();  // Asegúrate de que la sesión esté iniciada

// Verifica si el ID del pedido fue enviado
if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mi_primera_borrachera";

    // Conectar a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para cambiar el estado del pedido a 'cerrado'
    $query = "UPDATE pedidos SET estado = 'cerrado' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $pedido_id);

    if ($stmt->execute()) {
        // Si el cambio fue exitoso, redirigimos a la página admin.php con un mensaje de éxito
        header("Location: admin.php?mensaje=Pedido cerrado correctamente.");
        exit(); // Asegúrate de terminar el script después de la redirección
    } else {
        // Si hubo un error en la ejecución, redirigimos con un mensaje de error
        header("Location: admin.php?mensaje=Hubo un error al cerrar el pedido.");
        exit();
    }

    // Cerrar la conexión (este código ya no es necesario después de la redirección)
    // $stmt->close();
    // $conn->close();
} else {
    // Si no se pasó un ID de pedido, redirigimos a admin.php
    header("Location: admin.php?mensaje=No se especificó el pedido.");
    exit();
}
?>