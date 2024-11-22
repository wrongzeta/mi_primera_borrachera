<?php
include 'conexion.php';  // Asegúrate de que la conexión sea correcta

// Lógica para agregar, actualizar y eliminar productos (similar al código previo)
if (isset($_POST['add_product'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];

    $query = "INSERT INTO productos (nombre, precio, costo_venta, precio_venta)
              VALUES ('$nombre', '$precio', '$costo_venta', '$precio_venta')";

    if ($conn->query($query) === TRUE) {
        echo "Producto agregado con éxito.";
    } else {
        echo "Error al agregar producto: " . $conn->error;
    }
}

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];

    $query = "UPDATE productos 
              SET nombre='$nombre', precio='$precio', costo_venta='$costo_venta', precio_venta='$precio_venta' 
              WHERE id=$id";

    if ($conn->query($query) === TRUE) {
        echo "Producto actualizado con éxito.";
    } else {
        echo "Error al actualizar producto: " . $conn->error;
    }
}

if (isset($_POST['delete_product'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM productos WHERE id=$id";

    if ($conn->query($query) === TRUE) {
        echo "Producto eliminado con éxito.";
    } else {
        echo "Error al eliminar producto: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventario de Productos</title>
    <link rel="stylesheet" href="styles_inventario.css">
</head>
<body>

<?php
// Mostrar la tabla de productos
echo "<h2>Tabla de Productos</h2>";
$query = "SELECT * FROM productos";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table class='table-productos' border='1' cellpadding='10'>";
    echo "<tr><th>Producto</th><th>Precio</th><th>Costo Venta</th><th>Precio Venta</th><th>Acciones</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>$" . number_format($row['precio'], 2) . "</td>";
        echo "<td>$" . number_format($row['costo_venta'], 2) . "</td>";
        echo "<td>$" . number_format($row['precio_venta'], 2) . "</td>";
        echo "<td class='acciones'>
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='text' name='nombre' value='" . $row['nombre'] . "' required>
                    <input type='number' name='precio' value='" . $row['precio'] . "' step='0.01' required>
                    <input type='number' name='costo_venta' value='" . $row['costo_venta'] . "' step='0.01' required>
                    <input type='number' name='precio_venta' value='" . $row['precio_venta'] . "' step='0.01' required>
                    <input type='submit' name='update_product' value='Actualizar' class='btn-actualizar'>
                </form>
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' name='delete_product' value='Eliminar' class='btn-eliminar'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "No hay productos registrados.";
}
?>


<!-- Formulario para agregar un nuevo producto -->
<h3>Agregar Nuevo Producto</h3>
<form class="form-agregar-producto" method="POST">
    <label for="nombre">Nombre del Producto:</label>
    <input type="text" name="nombre" required><br>
    
    <label for="precio">Precio:</label>
    <input type="number" name="precio" step="0.01" required><br>
    
    <label for="costo_venta">Costo de Venta:</label>
    <input type="number" name="costo_venta" step="0.01" required><br>
    
    <label for="precio_venta">Precio de Venta:</label>
    <input type="number" name="precio_venta" step="0.01" required><br>
    
    <button type="submit" name="add_product">Agregar Producto</button>
</form>

<hr>

<?php
// Consulta para obtener los productos y sus inventarios por sede
$sql = "SELECT p.nombre, i.cantidad, i.sede_id, p.precio_venta
        FROM inventarios i
        INNER JOIN productos p ON i.producto_id = p.id
        ORDER BY i.sede_id, p.nombre";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Array para almacenar los inventarios por sede
    $inventarios_por_sede = [];

    // Organizar los productos por sede
    while ($row = $result->fetch_assoc()) {
        $sede_id = $row['sede_id'];
        $nombre_producto = $row['nombre'];
        $cantidad = $row['cantidad'];
        $precio_venta = number_format($row['precio_venta'], 2);

        // Si no existe la sede, la creamos
        if (!isset($inventarios_por_sede[$sede_id])) {
            $inventarios_por_sede[$sede_id] = [];
        }

        // Agregar el producto al inventario de la sede correspondiente
        $inventarios_por_sede[$sede_id][] = [
            'nombre' => $nombre_producto,
            'cantidad' => $cantidad,
            'precio_venta' => $precio_venta
        ];
    }

    // Mostrar inventarios por sede
    foreach ($inventarios_por_sede as $sede_id => $productos) {
        // Mostrar el nombre de la sede
        echo "<h2>Sede " . $sede_id . "</h2>";
        echo "<table class='table-productos' border='1' cellpadding='10'>";
        echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio de Venta</th></tr>";

        // Mostrar productos para cada sede
        foreach ($productos as $producto) {
            echo "<tr>";
            echo "<td>" . $producto['nombre'] . "</td>";
            echo "<td>" . $producto['cantidad'] . "</td>";
            echo "<td>$" . $producto['precio_venta'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
} else {
    echo "No se encontraron inventarios.";
}

?>

</body>
</html>
