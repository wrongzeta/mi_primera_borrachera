<?php
// Conexión a la base de datos
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['producto_id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];
    $imagen = $_POST['imagen'];

    // Actualizar producto
    $sql = "UPDATE productos
            SET nombre = ?, precio = ?, imagen = ?, costo_venta = ?, precio_venta = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddi", $nombre, $precio, $imagen, $costo_venta, $precio_venta, $producto_id);

    if ($stmt->execute()) {
        echo "Producto actualizado correctamente.";
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
    }
    $stmt->close();
}

?>

<!-- Formulario para modificar producto -->
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

    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label for="precio">Precio:</label>
    <input type="text" name="precio" required><br>

    <label for="costo_venta">Costo de venta:</label>
    <input type="text" name="costo_venta" required><br>

    <label for="precio_venta">Precio de venta:</label>
    <input type="text" name="precio_venta" required><br>

    <label for="imagen">Imagen:</label>
    <input type="text" name="imagen" required><br>

    <input type="submit" value="Modificar producto">
</form>
