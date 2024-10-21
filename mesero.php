<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) {
    header("Location: index.html");
    exit();
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

// Obtener el número de mesa de la sesión
if (!isset($_SESSION['mesa'])) {
    header("Location: mesas.php");
    exit();
}

$mesa_numero = $_SESSION['mesa'];

// Obtener el pedido actual de la base de datos
$query = $pdo->prepare("SELECT p.id, p.fecha, dp.producto_id, dp.cantidad, pr.nombre, pr.precio FROM pedidos p LEFT JOIN detalles_pedido dp ON p.id = dp.pedido_id LEFT JOIN productos pr ON dp.producto_id = pr.id WHERE p.mesa_id = (SELECT id FROM mesas WHERE numero = :numero) AND p.estado = 'pendiente'");
$query->execute(['numero' => $mesa_numero]);
$pedido_actual = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz del Mesero</title>
    <link rel="stylesheet" href="styles_mesero.css"> 
    <script>
        function validarFormulario() {
            let productos = document.querySelectorAll('input[type="number"]');
            for (let i = 0; i < productos.length; i++) {
                if (productos[i].value < 0) {
                    alert("La cantidad no puede ser negativa.");
                    return false;
                }
            }
            return true; // Permitir el envío si todas las validaciones pasan
        }
    </script>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
    <p>Aquí puedes gestionar los pedidos.</p>

    <!-- Botón para volver a las mesas -->
    <form action="mesas.php" method="GET">
        <input type="submit" value="Volver a Mesas">
    </form>

    <!-- Botón para cerrar sesión -->
    <form action="logout.php" method="POST">
        <input type="submit" value="Cerrar Sesión">
    </form>

    <h2>Estás en la mesa número: <?php echo htmlspecialchars($mesa_numero); ?></h2>

    <?php
    // Mostrar mensaje de error si existe
    if (isset($_SESSION['mensaje_error'])) {
        echo "<div style='color: red;'>" . htmlspecialchars($_SESSION['mensaje_error']) . "</div>";
        unset($_SESSION['mensaje_error']);
    }
    ?>

    <h2>Tomar Pedido</h2>
    <form action="procesar_pedido.php" method="POST" onsubmit="return validarFormulario();">
        <input type="hidden" name="mesa" value="<?php echo htmlspecialchars($mesa_numero); ?>">
        <div class="productos-grid">
            <?php
            // Obtener los productos de la base de datos
            $query = $pdo->query("SELECT id, nombre, precio, imagen FROM productos");
            $productos = $query->fetchAll(PDO::FETCH_ASSOC);

            // Mostrar los productos en la cuadrícula
            foreach ($productos as $producto) {
                echo "<div class='producto-item'>";
                echo "<input type='checkbox' id='producto_" . $producto['id'] . "' name='productos[" . $producto['id'] . "][id]' value='" . $producto['id'] . "'>";
                echo "<label for='producto_" . $producto['id'] . "'>";
                echo "<img src='" . htmlspecialchars($producto['imagen']) . "' alt='" . htmlspecialchars($producto['nombre']) . "'><br>";
                echo "<strong>" . htmlspecialchars($producto['nombre']) . "</strong><br>";
                echo "Precio: $" . number_format($producto['precio'], 2) . "<br>";
                echo "Cantidad: <input type='number' name='productos[" . $producto['id'] . "][cantidad]' value='0' min='0'>";
                echo "</label>";
                echo "</div>";
            }
            ?>
        </div>
        <input type="submit" value="Enviar Pedido">
    </form>

    <h2>Pedido Actual</h2>
    <?php if (empty($pedido_actual)): ?>
        <p>No hay pedidos para esta mesa.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($pedido_actual as $pedido): ?>
                <li>
                    <?php echo htmlspecialchars($pedido['nombre']) . " (Cantidad: " . $pedido['cantidad'] . ") - Precio: $" . number_format($pedido['precio'], 2); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
