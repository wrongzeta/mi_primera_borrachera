<?php
session_start();  // Asegúrate de que la sesión esté iniciada

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_primera_borrachera";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los pedidos, incluyendo el nombre de la sede desde la tabla sedes
$query = "SELECT p.id, p.mesa_id, p.fecha, p.estado, s.nombre AS sede 
          FROM pedidos p
          JOIN mesas m ON p.mesa_id = m.id
          JOIN sedes s ON m.sede_id = s.id"; // Unimos con la tabla sedes usando sede_id de mesas

$result = $conn->query($query);

// Almacenar los resultados en un array para su posterior uso
$pedidos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;  // Guardamos los pedidos en un array
    }
} else {
    echo "No se han encontrado pedidos.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="styles_pedidos.css">
</head>
<body>

<div class="container">
    <h1>Pedidos en Sede <?php echo isset($_SESSION['sede_nombre']) ? $_SESSION['sede_nombre'] : 'No definida'; ?></h1> <!-- Asegúrate de tener la sede definida en la sesión -->

    <?php if (count($pedidos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Mesa</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Sede</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo $pedido['id']; ?></td>
                        <td><?php echo $pedido['mesa_id']; ?></td>
                        <td><?php echo $pedido['fecha']; ?></td>
                        <td><?php echo ucfirst($pedido['estado']); ?></td>
                        <td><?php echo $pedido['sede']; ?></td>
                        <td>
                            <button class="edit" onclick="location.href='gestion_pedidos.php?id=<?php echo $pedido['id']; ?>'">Editar</button>
                            <button class="close" onclick="location.href='cambiar_estado.php?id=<?php echo $pedido['id']; ?>'">Cerrar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay pedidos en esta sede.</p>
    <?php endif; ?>

</div>

</body>
</html>
