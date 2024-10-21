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

// Verificar si se ha enviado un número de mesa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mesa'])) {
    $mesa_numero = $_POST['mesa'];

    // Aquí debes verificar si la mesa está ocupada o libre
    $query = $pdo->prepare("SELECT estado FROM mesas WHERE numero = :numero");
    $query->execute(['numero' => $mesa_numero]);
    $mesa = $query->fetch(PDO::FETCH_ASSOC);

    if ($mesa) {
        if ($mesa['estado'] === 'ocupado') {
            // Lógica para agregar más productos al pedido existente
            $_SESSION['mesa'] = $mesa_numero; // Almacenar el número de mesa en la sesión
            $_SESSION['mensaje_error'] = "Mesa " . htmlspecialchars($mesa_numero) . " está ocupada. Puedes agregar más productos.";
            header("Location: mesero.php");
            exit();
        } else {
            // Lógica para iniciar un nuevo pedido
            $_SESSION['mesa'] = $mesa_numero; // Almacenar el número de mesa en la sesión
            $_SESSION['mensaje_error'] = "Mesa " . htmlspecialchars($mesa_numero) . " está libre. Puedes iniciar un nuevo pedido.";
            header("Location: mesero.php");
            exit();
        }
    } else {
        $_SESSION['mensaje_error'] = "Mesa no encontrada.";
        header("Location: mesas.php");
        exit();
    }
} else {
    $_SESSION['mensaje_error'] = "No se ha seleccionado ninguna mesa.";
    header("Location: mesas.php");
    exit();
}
?>
