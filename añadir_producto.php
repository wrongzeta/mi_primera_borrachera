<?php
// Conexión a la base de datos
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];
    $imagen = $_POST['imagen'];

    // Insertar nuevo producto
    $sql = "INSERT INTO productos (nombre, precio, imagen, costo_venta, precio_venta)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddd", $nombre, $precio, $imagen, $costo_venta, $precio_venta);

    if ($stmt->execute()) {
        echo "Producto añadido correctamente.";
    } else {
        echo "Error al añadir producto: " . $conn->error;
    }
    $stmt->close();
}

?>

<!-- Formulario para añadir un nuevo producto -->
<form method="POST">
    <label for="nombre">Nombre del producto:</label>
    <input type="text" name="nombre" required><br>

    <label for="precio">Precio:</label>
    <input type="text" name="precio" required><br>

    <label for="costo_venta">Costo de venta:</label>
    <input type="text" name="costo_venta" required><br>

    <label for="precio_venta">Precio de venta:</label>
    <input type="text" name="precio_venta" required><br>

    <label for="imagen">Imagen:</label>
    <input type="text" name="imagen" required><br>

    <input type="submit" value="Añadir producto">
</form>
