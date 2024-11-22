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

// Verificar si los datos fueron enviados
if (!isset($_POST['pedido_id'], $_POST['producto_id'], $_POST['cantidad']) || 
    empty($_POST['pedido_id']) || empty($_POST['producto_id']) || empty($_POST['cantidad'])) {
    echo "Faltan datos para agregar el producto.";
    exit;
}

$pedido_id = intval($_POST['pedido_id']);
$producto_id = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);

// Insertar el nuevo detalle del pedido
$sql = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $pedido_id, $producto_id, $cantidad);

if ($stmt->execute()) {
    echo "Producto agregado correctamente.";
} else {
    echo "Error al agregar el producto: " . $conn->error;
}

// Redirigir de vuelta a la página de gestión del pedido
header('Location: ' . $_SERVER['HTTP_REFERER']);
$conn->close();
exit;
?>
