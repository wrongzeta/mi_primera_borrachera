<?php
// Conexión a la base de datos
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['producto_id'];
    $sede_id = $_POST['sede_id'];
    $cantidad = $_POST['cantidad'];

    // Actualizar la cantidad del inventario
    $sql = "UPDATE inventarios 
            SET cantidad = ? 
            WHERE producto_id = ? AND sede_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $producto_id, $sede_id);

    if ($stmt->execute()) {
        echo "Inventario actualizado correctamente.";
    } else {
        echo "Error al actualizar el inventario: " . $conn->error;
    }
    $stmt->close();
}

?>

<!-- Formulario para modificar el inventario -->
<form method="POST">
    <label for="producto_id">Producto:</label>
    <select name="producto_id" required>
        <?php
        // Obtener productos para el select
        $result = $conn->query("SELECT id, nombre FROM productos");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }
        ?>
    </select><br>

    <label for="sede_id">Sede:</label>
    <select name="sede_id" required>
        <?php
        // Obtener sedes para el select
        $result = $conn->query("SELECT id, nombre FROM sedes");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
        }
        ?>
    </select><br>

    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" required><br>

    <input type="submit" value="Modificar inventario">
</form>
