<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) { // Cambia 1 si el rol de mesero tiene un ID diferente
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "mi_primera_borrachera";

$conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Asegúrate de que estos campos están siendo enviados
$producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : null; 
$cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : null;
$usuario_id = $_SESSION['usuario_id']; // Asegúrate de haber almacenado el ID del usuario en la sesión al iniciar sesión

// Comprobar si se recibieron los datos
if ($producto_id === null || $cantidad === null || $usuario_id === null) {
    die("Error: Datos incompletos.");
}

// Inserta el pedido en la base de datos
$sql = "INSERT INTO pedidos (usuario_id, estado) VALUES (?, 'pendiente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$pedido_id = $stmt->insert_id; // Obtiene el ID del pedido recién creado

// Inserta los detalles del pedido
$sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);
$stmt_detalle->bind_param("iii", $pedido_id, $producto_id, $cantidad);
$stmt_detalle->execute();

// Cierra la conexión
$stmt->close();
$stmt_detalle->close();
$conn->close();

// Redirige a la interfaz del mesero o muestra un mensaje
header("Location: mesero.php?mensaje=Pedido realizado con éxito");
exit();
?>
