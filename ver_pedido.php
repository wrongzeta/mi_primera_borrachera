<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header('Location: login.php'); // Redirige al login si no está autenticado
    exit;
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

// Obtiene el ID del pedido desde la URL
$id_pedido = $_GET['id'];

// Consulta para obtener los detalles del pedido
$sql = "SELECT p.id, u.username, m.nombre AS mesa, p.estado, p.fecha, s.nombre AS sede, dp.producto_id, pr.nombre AS producto, dp.cantidad, dp.precio
        FROM detalles_pedido dp
        JOIN pedidos p ON dp.pedido_id = p.id
        JOIN productos pr ON dp.producto_id = pr.id
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN mesas m ON p.mesa_id = m.id
        JOIN sedes s ON u.sede_id = s.id
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="styles_admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <!-- Menú lateral (como lo definimos antes) -->
        </aside>

        <main class="main-content">
            <h1>Detalles del Pedido #<?php echo $id_pedido; ?></h1>
            <?php if ($resultado->num_rows > 0) {
                $pedido = $resultado->fetch_assoc();
                echo "<p>Usuario: " . $pedido['username'] . "</p>";
                echo "<p>Mesa: " . $pedido['mesa'] . "</p>";
                echo "<p>Sede: " . $pedido['sede'] . "</p>";
                echo "<p>Estado: " . $pedido['estado'] . "</p>";
                echo "<p>Fecha: " . $pedido['fecha'] . "</p>";
                echo "<h3>Productos del Pedido</h3>";
                echo "<table>";
                echo "<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr></thead>";
                echo "<tbody>";
                do {
                    echo "<tr>";
                    echo "<td>" . $pedido['producto'] . "</td>";
                    echo "<td>" . $pedido['cantidad'] . "</td>";
                    echo "<td>" . $pedido['precio'] . "</td>";
                    echo "</tr>";
                } while ($pedido = $resultado->fetch_assoc());
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No se encontró el pedido.</p>";
            }
            ?>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
