<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "mi_primera_borrachera";

$conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Variable para almacenar mensajes de error
$mensaje_error = "";

// Procesar el formulario de ocupación de mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numero_mesa'])) {
    $numero_mesa = (int)$_POST['numero_mesa'];

    // Verificar si la mesa existe y está libre
    $sql = "SELECT estado FROM mesas WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero_mesa);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mesa = $resultado->fetch_assoc();
        
        if ($mesa['estado'] === 'libre') {
            // Cambiar el estado de la mesa a ocupada
            $sql_actualizar = "UPDATE mesas SET estado = 'ocupada' WHERE numero = ?";
            $stmt_actualizar = $conn->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("i", $numero_mesa);
            if ($stmt_actualizar->execute()) {
                // Redirigir a la página del mesero
                header("Location: mesero.php");
                exit();
            } else {
                $mensaje_error = "Error al ocupar la mesa: " . $conn->error;
            }
        } else {
            $mensaje_error = "La mesa ya está ocupada.";
        }
    } else {
        $mensaje_error = "La mesa no existe.";
    }
}

// Consultar todas las mesas
$sql_mesas = "SELECT numero, estado FROM mesas";
$resultado_mesas = $conn->query($sql_mesas);
$mesas = [];

if ($resultado_mesas->num_rows > 0) {
    while ($fila = $resultado_mesas->fetch_assoc()) {
        $mesas[] = $fila;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocupar Mesa</title>
    <link rel="stylesheet" href="mesa.css">
</head>
<body>
    <h1>Ocupar Mesa</h1>
    
    <?php if (!empty($mensaje_error)): ?>
        <div class="error"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>
    
    <form action="" method="POST">
        <label for="numero_mesa">Número de Mesa:</label>
        <input type="number" id="numero_mesa" name="numero_mesa" required>
        <input type="submit" value="Ocupar Mesa">
    </form>

    <h2>Estado de las Mesas</h2>
    <table>
        <thead>
            <tr>
                <th>Número de Mesa</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mesas as $mesa): ?>
                <tr class="<?php echo $mesa['estado'] === 'ocupada' ? 'ocupada' : 'libre'; ?>">
                    <td><?php echo htmlspecialchars($mesa['numero']); ?></td>
                    <td><?php echo ucfirst($mesa['estado']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
