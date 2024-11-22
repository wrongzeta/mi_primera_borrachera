<?php
// Incluir el archivo de conexión
include('conexion.php');

// Mostrar errores para depuración (si es necesario)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar el mensaje de resultado
$mensaje = '';

// Verificar que la conexión esté funcionando
if ($conn === false) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Procesar la actualización del producto
if (isset($_POST['update_product'])) {
    // Validar que los datos estén presentes
    if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['precio']) &&
        isset($_POST['costo_venta']) && isset($_POST['precio_venta']) &&
        !empty($_POST['id']) && !empty($_POST['nombre']) && !empty($_POST['precio']) &&
        !empty($_POST['costo_venta']) && !empty($_POST['precio_venta'])) {

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $costo_venta = $_POST['costo_venta'];
        $precio_venta = $_POST['precio_venta'];

        // Sanitizar los datos
        $id = $conn->real_escape_string($id);
        $nombre = $conn->real_escape_string($nombre);
        $precio = $conn->real_escape_string($precio);
        $costo_venta = $conn->real_escape_string($costo_venta);
        $precio_venta = $conn->real_escape_string($precio_venta);

        // Consulta para actualizar el producto
        $query = "UPDATE productos 
                  SET nombre = '$nombre', precio = '$precio', costo_venta = '$costo_venta', precio_venta = '$precio_venta' 
                  WHERE id = '$id'";

        // Ejecutar la consulta
        if ($conn->query($query) === TRUE) {
            // Redirección a admin.php después de la actualización exitosa
            header('Location: admin.php');
            exit; // Es importante llamar a exit después de header para asegurar que no se ejecute más código.
        } else {
            $mensaje = 'Error al actualizar el producto: ' . $conn->error;
        }
    } else {
        $mensaje = 'Error: Faltan datos para procesar la actualización.';
    }
}

// Mostrar el mensaje con la opción de volver si hubo un error
if (!empty($mensaje)) {
    echo "<html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Resultado de la operación</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f4f4f4;
                    }
                    .resultado {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                    h2 {
                        color: #333;
                    }
                    .btn-volver {
                        background-color: #2e8bc0;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        font-size: 16px;
                        cursor: pointer;
                        border-radius: 5px;
                    }
                    .btn-volver:hover {
                        background-color: #1d6c8c;
                    }
                </style>
            </head>
            <body>
                <div class='resultado'>
                    <h2>$mensaje</h2>
                    <a href='admin.php'><button class='btn-volver'>Volver a Administrador</button></a>
                </div>
            </body>
          </html>";
    exit; // Detener la ejecución en caso de error
} else {
    echo "No se ha procesado ninguna operación.";
}
?>
