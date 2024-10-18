<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) {
    header("Location: login.php");
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

// Obtener los productos de la base de datos
$query = $pdo->query("SELECT id, nombre, precio, imagen FROM productos");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz del Mesero</title>
    <link rel="stylesheet" href="styles_mesero.css"> 
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
    <p>Aquí puedes gestionar los pedidos.</p>

    <h2>Tomar Pedido</h2>
    <form action="procesar_pedido.php" method="POST">
        <div class="productos-grid">
            <?php
            // Mostrar los productos en la cuadrícula
            foreach ($productos as $producto) {
                // Obtener la imagen directamente de la base de datos
                $imagen = $producto['imagen'];

                echo "<div class='producto-item'>";
                echo "<input type='checkbox' id='producto_" . $producto['id'] . "' name='productos[" . $producto['id'] . "][id]' value='" . $producto['id'] . "'>";
                echo "<label for='producto_" . $producto['id'] . "'>";
                echo "<img src='" . $imagen . "' alt='" . $producto['nombre'] . "' style='width:100px;height:auto;'><br>"; // Mostrar la imagen
                echo "<strong>" . $producto['nombre'] . "</strong><br>";
                echo "Precio: $" . number_format($producto['precio'], 2) . "<br>";
                echo "</label>";
                echo "<label for='cantidad_" . $producto['id'] . "'>Cantidad:</label>";
                echo "<input type='number' id='cantidad_" . $producto['id'] . "' name='productos[" . $producto['id'] . "][cantidad]' min='0' value='0'><br>";
                echo "</div>";
            }
            ?>
        </div>
        <br>
        <input type="submit" value="Agregar a Pedido">
    </form>

    <h2>Ver Pedido</h2>
    <div id="pedido-actual">
        <?php
        // Mostrar el pedido actual si hay productos en la sesión
        if (isset($_SESSION['pedido']) && !empty($_SESSION['pedido'])) {
            echo "<ul>";
            foreach ($_SESSION['pedido'] as $id => $detalle) {
                echo "<li>" . $detalle['nombre'] . " - Cantidad: " . $detalle['cantidad'] . " - Precio: $" . number_format($detalle['precio'] * $detalle['cantidad'], 2) . "</li>";
            }
            echo "</ul>";
            echo "<strong>Total: $" . number_format(array_sum(array_column($_SESSION['pedido'], 'subtotal')), 2) . "</strong>";
        } else {
            echo "<p>No hay productos en el pedido actual.</p>";
        }
        ?>
    </div>

    <!-- Botón para volver a las mesas -->
    <form action="pagina_mesas.php" method="GET">
        <input type="submit" value="Volver a Mesas">
    </form>

    <!-- Botón para cerrar sesión -->
    <form action="logout.php" method="POST">
        <input type="submit" value="Cerrar Sesión">
    </form>
    
</body>
</html>
