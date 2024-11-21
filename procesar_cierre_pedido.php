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

// Obtener los pedidos de la mesa seleccionada
$query = $pdo->prepare("
    SELECT p.id, p.fecha, dp.producto, dp.cantidad, dp.precio
    FROM pedidos p
    JOIN detalles_pedido dp ON p.id = dp.pedido_id
    WHERE p.mesa_id = :mesa_id AND p.estado = 'pendiente'
");
$query->execute(['mesa_id' => $mesa_id]);
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se encontraron pedidos
if (empty($pedidos)) {
    echo "No hay pedidos pendientes para esta mesa.";
} else {
    echo "<h1>Pedidos de la Mesa #$mesa_id</h1>";
    echo "<ul>";
    foreach ($pedidos as $pedido) {
        echo "<li>Producto: " . htmlspecialchars($pedido['producto']) . " - Cantidad: " . htmlspecialchars($pedido['cantidad']) . " - Precio: " . htmlspecialchars($pedido['precio']) . "</li>";
    }
    echo "</ul>";

    // Formulario para cerrar el pedido
    echo "<form action='cerrar_pedido.php' method='POST'>
            <input type='hidden' name='mesa_id' value='" . htmlspecialchars($mesa_id) . "'>
            <input type='submit' value='Cerrar Pedido'>
          </form>";
}
?>
