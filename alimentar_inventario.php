<?php
// Incluir archivo de conexión a la base de datos
include('conexion.php');

// Suponiendo que la sede del cajero está guardada en la sesión
session_start();
$sede_id_cajero = $_SESSION['sede_id']; // Asegúrate de que esta variable esté disponible
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventarios - Sede <?php echo $sede_id_cajero; ?></title>
    <link rel="stylesheet" href="styles_alimentar_inventario.css">
    <script>
        function volver() {
            window.history.back(); // Vuelve a la página anterior
        }
    </script>
</head>
<body>

<?php
// Mostrar la tabla de inventarios para la sede del cajero
echo "<h2>Inventarios para la Sede " . $sede_id_cajero . "</h2>";
$sql = "SELECT p.nombre, i.id, i.cantidad, i.sede_id, p.precio_venta
        FROM inventarios i
        INNER JOIN productos p ON i.producto_id = p.id
        WHERE i.sede_id = ? ORDER BY p.nombre";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $sede_id_cajero); // Ligar el parámetro de la sede
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay resultados
if ($result->num_rows > 0) {
    echo "<table class='table-productos' border='1' cellpadding='10'>";
    echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio de Venta</th></tr>"; 

    // Mostrar productos de la sede del cajero
    while ($row = $result->fetch_assoc()) {
        $nombre_producto = $row['nombre'];
        $cantidad = $row['cantidad'];
        $precio_venta = number_format($row['precio_venta'], 2);
        $id = $row['id'];

        echo "<tr>";
        echo "<td>" . $nombre_producto . "</td>";
        echo "<td>
                <form method='POST' style='display:inline' action='procesar_inventario_cajero.php'>
                    <input type='number' name='cantidad' value='" . $cantidad . "' required>
                    <input type='hidden' name='id' value='" . $id . "'>
                    <input type='submit' name='update_inventory' value='Actualizar' class='btn-actualizar'>
                </form>
              </td>";
        echo "<td>$" . $precio_venta . "</td>";
        echo "</tr>";
    }

    echo "</table><br>";
} else {
    echo "No hay inventarios registrados para esta sede.";
}
?>
    <br>
    <button class="button" onclick="volver()">Volver</button>
</body>
</html>
