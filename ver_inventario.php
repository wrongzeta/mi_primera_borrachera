<?php
// Conexión a la base de datos
include('conexion.php');

// Consulta para obtener el inventario por sede
$sql = "SELECT i.id, p.nombre, p.precio, i.cantidad, s.nombre AS sede
        FROM inventarios i
        JOIN productos p ON i.producto_id = p.id
        JOIN sedes s ON i.sede_id = s.id";
$result = $conn->query($sql);

echo "<h2>Inventarios por sede</h2>";
echo "<table border='1'>
        <tr>
            <th>Producto</th>
            <th>Sede</th>
            <th>Cantidad</th>
            <th>Precio</th>
        </tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nombre'] . "</td>
                <td>" . $row['sede'] . "</td>
                <td>" . $row['cantidad'] . "</td>
                <td>" . $row['precio'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron productos en el inventario.";
}

$conn->close();
?>
