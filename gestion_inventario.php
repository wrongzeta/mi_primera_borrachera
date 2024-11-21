<?php
// Conexión a la base de datos
require 'conexion.php';

// Consulta para obtener los pedidos
$query = "SELECT p.id, p.fecha, p.estado, u.nombre AS usuario, s.nombre AS sede
          FROM pedidos p
          JOIN usuarios u ON p.usuario_id = u.id
          JOIN sedes s ON u.sede_id = s.id";
$result = $conn->query($query);

// HTML dinámico
if ($result->num_rows > 0) {
    echo "<h2>Gestión de Pedidos</h2>";
    echo "<table>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th>Sede</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['fecha']}</td>
                <td>{$row['estado']}</td>
                <td>{$row['usuario']}</td>
                <td>{$row['sede']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No hay pedidos registrados.</p>";
}

$conn->close();
?>
