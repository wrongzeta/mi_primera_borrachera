<?php
// Incluir archivo de conexión a la base de datos
include('conexion.php');

// Procesar la actualización del inventario
if (isset($_POST['update_inventory'])) {
    $id = $_POST['id'];
    $cantidad = $_POST['cantidad'];

    // Sanitizar los datos
    $id = $conn->real_escape_string($id);
    $cantidad = $conn->real_escape_string($cantidad);

    // Consulta para actualizar la cantidad del producto en el inventario
    $query = "UPDATE inventarios SET cantidad = '$cantidad' WHERE id = '$id'";

    if ($conn->query($query) === TRUE) {
        // Redirigir a la misma página para ver los cambios
        header("Location: alimentar_inventario.php");
        exit; // Detener la ejecución del script
    } else {
        echo "Error al actualizar el inventario: " . $conn->error;
    }
}

// Procesar la eliminación de un producto del inventario
if (isset($_POST['delete_inventory'])) {
    $id = $_POST['id'];

    // Consulta para eliminar el inventario
    $query = "DELETE FROM inventarios WHERE id = '$id'";

    if ($conn->query($query) === TRUE) {
        // Redirigir a la misma página después de eliminar el producto
        header("Location: alimentar_inventario.php");
        exit; // Detener la ejecución del script
    } else {
        echo "Error al eliminar el inventario: " . $conn->error;
    }
}
?>
