<?php
include 'conexion.php';  // Asegúrate de que la conexión sea correcta

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $costo_venta = $_POST['costo_venta'];
    $precio_venta = $_POST['precio_venta'];

    // Asegurarse de que los datos estén bien validados y sanitizados
    $id = $conn->real_escape_string($id);
    $nombre = $conn->real_escape_string($nombre);
    $precio = $conn->real_escape_string($precio);
    $costo_venta = $conn->real_escape_string($costo_venta);
    $precio_venta = $conn->real_escape_string($precio_venta);

    // Consulta de actualización
    $query = "UPDATE productos 
              SET nombre='$nombre', precio='$precio', costo_venta='$costo_venta', precio_venta='$precio_venta' 
              WHERE id='$id'";

    if ($conn->query($query) === TRUE) {
        echo "Producto actualizado con éxito.";
    } else {
        echo "Error al actualizar producto: " . $conn->error;
    }
    
    // Redirigir a admin.php después de la actualización
    header("Location: admin.php");
    exit; // Detener la ejecución del script
}
?>
