<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) { // Suponiendo que 3 es el rol de administrador
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

// Consulta para obtener todos los pedidos
$sql = "SELECT p.id, u.username, m.nombre AS mesa, p.estado, p.fecha, s.nombre AS sede
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN mesas m ON p.mesa_id = m.id
        JOIN sedes s ON u.sede_id = s.id"; // Asumiendo que tienes una tabla 'sedes'

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Administrador</title>
    <link rel="stylesheet" href="styles_admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <!-- Menú lateral (como lo definimos antes) -->
        </aside>

        <main class="main-content">
            <h1>Gestión de Pedidos</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Usuario</th>
                        <th>Mesa</th>
                        <th>Estado</th>
                        <th>Sede</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0) {
                        while($pedido = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $pedido['id'] . "</td>";
                            echo "<td>" . $pedido['username'] . "</td>";
                            echo "<td>" . $pedido['mesa'] . "</td>";
                            echo "<td>" . $pedido['estado'] . "</td>";
                            echo "<td>" . $pedido['sede'] . "</td>";
                            echo "<td>" . $pedido['fecha'] . "</td>";
                            echo "<td><a href='ver_pedido.php?id=" . $pedido['id'] . "'>Ver detalles</a> | 
                                      <a href='cambiar_estado.php?id=" . $pedido['id'] . "'>Cambiar estado</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay pedidos registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>

<?php
$conn->close();
?>
