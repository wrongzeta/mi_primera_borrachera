<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.php'); // Redirige al login si no está autenticado
    exit;
}

// Conexión a la base de datos
$servidor = "localhost"; 
$usuario = "root"; 
$contraseña = ""; 
$base_datos = "mi_primera_borrachera";

$conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Cambiar el estado del pedido
if (isset($_GET['cambiar_estado']) && isset($_GET['id'])) {
    $pedido_id = $_GET['id'];
    $nuevo_estado = $_GET['estado'] == 'pendiente' ? 'cerrado' : 'pendiente';

    $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    
    if ($stmt->execute()) {
        echo "Estado actualizado correctamente.";
    } else {
        echo "Error al actualizar el estado.";
    }

    // Redirige después de cambiar el estado
    header("Location: gestion_pedidos.php");
    exit();
}

// Consulta para obtener los pedidos
$sql = "SELECT * FROM pedidos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Mi Primera Borrachera</title>
    <link rel="stylesheet" href="styles_admin.css">
</head>
<body>
    <h1>Gestión de Pedidos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Sede</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php while ($pedido = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $pedido['id']; ?></td>
            <td><?php echo $pedido['usuario_id']; ?></td>
            <td><?php echo $pedido['sede_id']; ?></td>
            <td><?php echo $pedido['fecha']; ?></td>
            <td><?php echo $pedido['estado']; ?></td>
            <td>
                <a href="gestion_pedidos.php?cambiar_estado=true&id=<?php echo $pedido['id']; ?>&estado=<?php echo $pedido['estado']; ?>">
                    Cambiar Estado
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
