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

// Recuperar ID del pedido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de pedido no especificado.";
    exit;
}
$pedido_id = intval($_GET['id']);

// Consulta para obtener información del pedido
$sql_pedido = "SELECT p.id, p.fecha, p.estado, m.nombre AS mesa, u.username AS usuario
               FROM pedidos p
               JOIN mesas m ON p.mesa_id = m.id
               JOIN usuarios u ON p.usuario_id = u.id
               WHERE p.id = ?";
$stmt = $conn->prepare($sql_pedido);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result_pedido = $stmt->get_result();

if ($result_pedido->num_rows === 0) {
    echo "Pedido no encontrado.";
    exit;
}

$pedido = $result_pedido->fetch_assoc();

// Consulta para obtener los detalles del pedido
$sql_detalles = "SELECT dp.id, dp.producto_id, dp.cantidad, prod.nombre AS producto, prod.precio
                 FROM detalles_pedido dp
                 JOIN productos prod ON dp.producto_id = prod.id
                 WHERE dp.pedido_id = ?";
$stmt_detalles = $conn->prepare($sql_detalles);
$stmt_detalles->bind_param("i", $pedido_id);
$stmt_detalles->execute();
$result_detalles = $stmt_detalles->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del Pedido</title>
    <link rel="stylesheet" href="styles_gestion.css"> <!-- Enlace al archivo de estilos -->
</head>
<body>
    <h2>Gestión del Pedido #<?php echo $pedido['id']; ?></h2>
    <p>
        <strong>Fecha:</strong> <?php echo $pedido['fecha']; ?><br>
        <strong>Mesa:</strong> <?php echo $pedido['mesa']; ?><br>
        <strong>Usuario:</strong> <?php echo $pedido['usuario']; ?><br>
        <strong>Estado:</strong> <?php echo ucfirst($pedido['estado']); ?>
    </p>

    <h3>Detalles del Pedido</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_pedido = 0;
            while ($detalle = $result_detalles->fetch_assoc()) {
                $total_producto = $detalle['cantidad'] * $detalle['precio'];
                $total_pedido += $total_producto;
                echo "<tr>";
                echo "<td>" . htmlspecialchars($detalle['producto']) . "</td>";
                echo "<td>" . intval($detalle['cantidad']) . "</td>";
                echo "<td>$" . number_format($detalle['precio'], 2) . "</td>";
                echo "<td>$" . number_format($total_producto, 2) . "</td>";
                echo "<td>
                        <form action='eliminar_detalle.php' method='post' style='display:inline;'>
                            <input type='hidden' name='detalle_id' value='" . intval($detalle['id']) . "'>
                            <button type='submit'>Eliminar</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <p><strong>Total del Pedido:</strong> $<?php echo number_format($total_pedido, 2); ?></p>

    <h3>Agregar Producto</h3>
    <form action="agregar_producto.php" method="post">
        <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
        <label for="producto_id">Producto:</label>
        <select name="producto_id" required>
            <?php
            // Consulta para obtener los productos disponibles
            $sql_productos = "SELECT id, nombre FROM productos";
            $result_productos = $conn->query($sql_productos);

            while ($producto = $result_productos->fetch_assoc()) {
                echo "<option value='" . intval($producto['id']) . "'>" . htmlspecialchars($producto['nombre']) . "</option>";
            }
            ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" min="1" required>

        <button type="submit">Agregar</button>
    </form>

    <a href="pedidos.php" class="btn-volver">Volver</a> <!-- Botón para volver -->
</body>
</html>

<?php
$conn->close();
?>
