<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene un sede_id
if (!isset($_SESSION['usuario']) || !isset($_SESSION['sede_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar que se ha recibido el mesa_id
if (!isset($_GET['mesa_id'])) {
    die("No se ha seleccionado ninguna mesa.");
}

// Conectar a la base de datos
$host = 'localhost';
$db   = 'mi_primera_borrachera';
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener el mesa_id desde la URL
$mesa_id = $_GET['mesa_id'];

// Obtener los pedidos de la mesa seleccionada, incluyendo el nombre del producto, precio y cantidad
$query = $pdo->prepare("
    SELECT p.id, p.fecha, pr.nombre AS producto, dp.cantidad, pr.precio, (dp.cantidad * pr.precio) AS total_producto
    FROM pedidos p
    JOIN detalles_pedido dp ON p.id = dp.pedido_id
    JOIN productos pr ON dp.producto_id = pr.id
    WHERE p.mesa_id = :mesa_id AND p.estado = 'pendiente'
");
$query->execute(['mesa_id' => $mesa_id]);
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total del pedido
$total_pedido = 0;
foreach ($pedidos as $pedido) {
    $total_pedido += $pedido['total_producto'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_cajero.css">
    <title>Pedidos de la Mesa</title>
</head>
<body>

    <!-- Mostrar el mensaje de bienvenida -->
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>. Estás en la sede <?php echo htmlspecialchars($_SESSION['sede_id']); ?>.</h1>

    <h2>Pedidos de la Mesa #<?php echo htmlspecialchars($mesa_id); ?></h2>

    <!-- Mostrar los pedidos de la mesa -->
    <ul>
        <?php
        if ($pedidos) {
            foreach ($pedidos as $pedido) {
                echo "<li>Producto: " . htmlspecialchars($pedido['producto']) . " - Cantidad: " . htmlspecialchars($pedido['cantidad']) . " - Precio: " . htmlspecialchars($pedido['precio']) . " - Total: " . htmlspecialchars($pedido['total_producto']) . "</li>";
            }
        } else {
            echo "<li>No hay pedidos pendientes para esta mesa.</li>";
        }
        ?>
    </ul>

    <!-- Mostrar el total del pedido -->
    <h3>Total del Pedido: <?php echo htmlspecialchars($total_pedido); ?> </h3>

    <!-- Botón para cerrar el pedido -->
    <form action="procesar_cierre_pedido.php" method="POST">
        <input type="hidden" name="mesa_id" value="<?php echo htmlspecialchars($mesa_id); ?>">
        <input type="submit" value="Cerrar Pedido">
    </form>

</body>
</html>
