<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['sede_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar que se ha recibido el mesa_id
if (!isset($_POST['mesa_id'])) {
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

// Obtener el mesa_id desde el formulario
$mesa_id = $_POST['mesa_id'];

// Obtener los detalles del pedido
$query = $pdo->prepare("
    SELECT dp.producto_id, p.id AS pedido_id, dp.cantidad, pr.precio 
    FROM detalles_pedido dp 
    JOIN pedidos p ON dp.pedido_id = p.id
    JOIN productos pr ON dp.producto_id = pr.id
    WHERE p.mesa_id = :mesa_id AND p.estado = 'pendiente'
");
$query->execute(['mesa_id' => $mesa_id]);
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total del pedido
$total = 0;
foreach ($pedidos as $pedido) {
    $total += $pedido['cantidad'] * $pedido['precio'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_cajero.css">
    <title>Cierre de Pedido</title>
</head>
<body>

    <!-- Mostrar el mensaje de bienvenida -->
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>. Estás en la sede <?php echo htmlspecialchars($_SESSION['sede_id']); ?>.</h1>

    <h2>Pedidos de la Mesa #<?php echo htmlspecialchars($mesa_id); ?></h2>

    <!-- Mostrar los detalles del pedido -->
    <ul>
        <?php
        if ($pedidos) {
            foreach ($pedidos as $pedido) {
                echo "<li>Producto: " . htmlspecialchars($pedido['producto_id']) . " - Cantidad: " . htmlspecialchars($pedido['cantidad']) . " - Precio: " . htmlspecialchars($pedido['precio']) . " - Subtotal: " . htmlspecialchars($pedido['cantidad'] * $pedido['precio']) . "</li>";
            }
        } else {
            echo "<li>No hay pedidos pendientes para esta mesa.</li>";
        }
        ?>
    </ul>

    <h3>Total del Pedido: $<?php echo number_format($total, 2); ?></h3>

    <!-- Formulario para seleccionar método de pago -->
    <form action="procesar_cierre_pedido.php" method="POST">
        <label for="pago">Seleccionar Método de Pago:</label><br>
        <select name="pago" id="pago" required>
            <option value="">Seleccione...</option>
            <option value="efectivo">Efectivo</option>
            <option value="credito">Crédito</option>
            <option value="tarjeta">Tarjeta Débito</option>
        </select><br><br>

        <!-- Botón para cerrar el pedido -->
        <input type="hidden" name="mesa_id" value="<?php echo htmlspecialchars($mesa_id); ?>">
        <input type="submit" value="Cerrar Pedido">
    </form>

</body>
</html>

<?php
// Lógica para cerrar el pedido si se selecciona el método de pago
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pago']) && isset($_POST['mesa_id'])) {
    // Obtener el método de pago seleccionado
    $pago = $_POST['pago'];

    // Validar el método de pago
    if (empty($pago)) {
        die("Debe seleccionar un método de pago.");
    }

    // Cerrar el pedido: actualizar el estado a 'cerrado'
    $query = $pdo->prepare("UPDATE pedidos SET estado = 'cerrado' WHERE mesa_id = :mesa_id AND estado = 'pendiente'");
    $query->execute(['mesa_id' => $_POST['mesa_id']]);

    // Liberar la mesa (actualizar su estado a 'libre')
    $query = $pdo->prepare("UPDATE mesas SET estado = 'libre' WHERE id = :mesa_id");
    $query->execute(['mesa_id' => $_POST['mesa_id']]);

    // Redirigir a la vista de pedidos
    header("Location: cajero.php");
    exit();
}
?>
