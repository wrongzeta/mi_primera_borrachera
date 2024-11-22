<?php
include 'conexion.php';  // Asegúrate de que la conexión sea correcta

if (isset($_POST['add_product'])) {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];

    // Sanitizar los datos
    $nombre = $conn->real_escape_string($nombre);
    $precio = $conn->real_escape_string($precio);
    $costo_venta = $conn->real_escape_string($costo_venta);
    $precio_venta = $conn->real_escape_string($precio_venta);

    // Consulta para agregar el producto a la base de datos
    $query = "INSERT INTO productos (nombre, precio, costo_venta, precio_venta) 
              VALUES ('$nombre', '$precio', '$costo_venta', '$precio_venta')";

    if ($conn->query($query) === TRUE) {
        // Redirigir a admin.php después de agregar el producto
        header("Location: admin.php");
        exit; // Detener la ejecución del script
    } else {
        echo "Error al agregar producto: " . $conn->error;
    }
}
?>
