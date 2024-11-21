<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['cambiar_estado']) && $_POST['cambiar_estado'] == 'cerrado' && isset($_POST['pedido_id'])) {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mi_primera_borrachera";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $pedido_id = $_POST['pedido_id'];

    // Verificar si el pedido ya está cerrado
    $sql_check = "SELECT estado FROM pedidos WHERE id = ?";
    if ($stmt = $conn->prepare($sql_check)) {
        $stmt->bind_param('i', $pedido_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($estado);
        $stmt->fetch();
        if ($estado == 'cerrado') {
            echo "El pedido ya está cerrado.";
            $conn->close();
            exit;
        }
    }

    // Actualizar el estado del pedido a "cerrado"
    $sql = "UPDATE pedidos SET estado = 'cerrado' WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $pedido_id);
        if ($stmt->execute()) {
            echo "Pedido cerrado correctamente.";
        } else {
            echo "Error al cerrar el pedido.";
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
    }

    $conn->close();

    // Redirigir de vuelta a la página de gestión de pedidos
    header('Location: pedidos.php');
    exit;
}
?>
