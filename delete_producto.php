<?php
include 'conexion.php';  // Asegúrate de que la conexión sea correcta

if (isset($_POST['delete_product'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM productos WHERE id='$id'";

    if ($conn->query($query) === TRUE) {
        echo "Producto eliminado con éxito.";
    } else {
        echo "Error al eliminar producto: " . $conn->error;
    }
    
    // Redirigir a admin.php después de la eliminación
    header("Location: admin.php");
    exit; // Detener la ejecución del script
}
?>
