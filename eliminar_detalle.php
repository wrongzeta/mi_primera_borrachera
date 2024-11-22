<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header('Location: login.php');
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_primera_borrachera";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el detalle_id fue enviado
if (!isset($_POST['detalle_id']) || empty($_POST['detalle_id'])) {
    echo "ID del detalle no especificado.";
    exit;
}

$detalle_id = intval($_POST['detalle_id']);

// Eliminar el detalle del pedido
$sql = "DELETE FROM detalles_pedido WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $detalle_id);

if ($stmt->execute()) {
    echo "Producto eliminado correctamente.";
} else {
    echo "Error al eliminar el producto: " . $conn->error;
}

// Redirigir de vuelta a la página de gestión del pedido
header('Location: ' . $_SERVER['HTTP_REFERER']);
$conn->close();
exit;
?>
