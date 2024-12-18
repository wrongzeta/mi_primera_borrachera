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

// Obtener las mesas de la sede correspondiente al mesero
$sede_id = $_SESSION['sede_id'];
// Obtener las mesas y su estado en función de los pedidos
$query = $pdo->prepare("
    SELECT mesas.numero, 
           IF(EXISTS (SELECT 1 FROM pedidos WHERE mesa_id = mesas.id AND estado = 'pendiente'), 'ocupado', 'libre') AS estado
    FROM mesas
    WHERE mesas.sede_id = :sede_id
");
$query->execute(['sede_id' => $sede_id]);
$mesas = $query->fetchAll(PDO::FETCH_ASSOC);

// Función para mostrar el estado de las mesas
function mostrarMesas($mesas) {
    echo "<ul>";
    foreach ($mesas as $mesa) {
        $color = ($mesa['estado'] === 'libre') ? 'green' : 'red';
        echo "<li style='color: $color;'>Mesa " . htmlspecialchars($mesa['numero']) . " - " . htmlspecialchars($mesa['estado']) . 
              "<form action='procesar_mesa.php' method='post' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de seleccionar esta mesa?\");'>
                <input type='hidden' name='mesa' value='" . htmlspecialchars($mesa['numero']) . "'>
                <button type='submit'>Seleccionar</button>
              </form>
              </li>";
    }
    echo "</ul>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_mesas.css"> <!-- Enlazar el CSS -->
    <title>Mesas - Mesero</title>
</head>
<body>
    <!-- Botón para cerrar sesión -->
    <form action="logout.php" method="POST" style="margin-top: 20px;">
        <input type="submit" value="Cerrar Sesión">
    </form>

    <?php
    // Mostrar el mensaje de bienvenida
    echo "<h1>Bienvenido a Mesero " . htmlspecialchars($_SESSION['usuario']) . "</h1>";

    // Llamar a la función para mostrar las mesas
    mostrarMesas($mesas);
    ?>

</body>
</html>
