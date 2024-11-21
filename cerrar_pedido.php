<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene un sede_id
if (!isset($_SESSION['usuario']) || !isset($_SESSION['sede_id'])) {
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

// Obtener la sede del cajero
$sede_id = $_SESSION['sede_id'];
$usuario = $_SESSION['usuario'];

// Obtener las mesas y su estado (libre u ocupado)
$query = $pdo->prepare("
    SELECT mesas.id, mesas.numero, 
           IF(EXISTS (SELECT 1 FROM pedidos WHERE mesa_id = mesas.id AND estado = 'pendiente'), 'ocupado', 'libre') AS estado
    FROM mesas
    WHERE mesas.sede_id = :sede_id
");
$query->execute(['sede_id' => $sede_id]);
$mesas = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_cerrar_pedido.css"> <!-- Enlazar el CSS -->
    <title>Mesas - Cajero</title>
</head>
<body>

    <!-- Mostrar el mensaje de bienvenida -->
    <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?>. Estás en la sede <?php echo htmlspecialchars($sede_id); ?>.</h1>

    <!-- Mostrar las mesas con su estado -->
    <ul>
        <?php
        foreach ($mesas as $mesa) {
            $estado = htmlspecialchars($mesa['estado']);
            $numero = htmlspecialchars($mesa['numero']);
            echo "<li style='color: " . ($estado == 'ocupado' ? 'red' : 'green') . ";'>";
            echo "Mesa $numero - $estado";

            // Si la mesa está ocupada, mostrar un botón para acceder a los pedidos
            if ($estado === 'ocupado') {
                echo "<form action='ver_pedidos.php' method='get' style='display:inline;'>
                        <input type='hidden' name='mesa_id' value='" . $mesa['id'] . "'>
                        <button type='submit'>Ver Pedidos</button>
                      </form>";
            }

            echo "</li>";
        }
        ?>
    </ul>

</body>
</html>
