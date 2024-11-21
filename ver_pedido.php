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

if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];

    // Obtener detalles del pedido
    $sql = "SELECT p.id, p.fecha, p.estado, m.nombre AS mesa, u.nombre AS usuario, d.producto, d.cantidad
            FROM pedidos p
            JOIN mesas m ON p.mesa_id = m.id
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN detalles_pedido d ON p.id = d.pedido_id
            WHERE p.id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $pedido = $result->fetch_assoc();
            echo "<h2>Detalles del Pedido #{$pedido['id']}</h2>";
            echo "<p>Fecha: {$pedido['fecha']}</p>";
            echo "<p>Estado: {$pedido['estado']}</p>";
            echo "<p>Mesa: {$pedido['mesa']}</p>";
            echo "<p>Usuario: {$pedido['usuario']}</p>";
            echo "<h3>Detalles del Pedido:</h3>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>Producto: {$row['producto']} - Cantidad: {$row['cantidad']}</li>";
            }
            echo "</ul>";
        } else {
            echo "Pedido no encontrado.";
        }
        $stmt->close();
    }

    $conn->close();
}
?>
