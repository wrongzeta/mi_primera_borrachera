<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) {
    header("Location: login.php");
    exit();
}

// Conectar a la base de datos
$host = 'localhost';
$db   = 'mi_primera_borrachera';
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Variable para almacenar mensajes de error
$mensaje_error = "";

// Verificar si se ha enviado un pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mesa'])) {
    $mesa_numero = $_POST['mesa'];

    // Aquí debes verificar el estado de la mesa y manejar la lógica correspondiente
    $query = $pdo->prepare("SELECT estado FROM mesas WHERE numero = :numero");
    $query->execute(['numero' => $mesa_numero]);
    $mesa = $query->fetch(PDO::FETCH_ASSOC);

    if ($mesa) {
        if ($mesa['estado'] === 'ocupado') {
            // Lógica para agregar más productos al pedido existente
            echo "Mesa " . htmlspecialchars($mesa_numero) . " está ocupada. Puedes agregar más productos.";
            // Aquí puedes redirigir a una página donde se pueda agregar más productos
        } else {
            // Lógica para iniciar un nuevo pedido
            echo "Mesa " . htmlspecialchars($mesa_numero) . " está libre. Puedes iniciar un nuevo pedido.";
            // Aquí puedes redirigir a una página para crear un nuevo pedido
        }
    } else {
        echo "Mesa no encontrada.";
    }
} else {
    echo "No se ha seleccionado ninguna mesa.";
}

// Verificar si se enviaron productos en el formulario
if (isset($_POST['productos']) && !empty($_POST['productos'])) {
    // Asegurarse de que la sesión de pedidos está inicializada
    if (!isset($_SESSION['pedido'])) {
        $_SESSION['pedido'] = [];
    }

    // Procesar cada producto seleccionado
    foreach ($_POST['productos'] as $id => $detalle) {
        $cantidad = (int)$detalle['cantidad'];

        if ($cantidad > 0) {
            // Consultar la cantidad actual del producto en la base de datos
            $sql = "SELECT nombre, precio, cantidad FROM productos WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $producto_db = $stmt->fetch(PDO::FETCH_ASSOC);
                $nombre = $producto_db['nombre'];
                $cantidad_disponible = $producto_db['cantidad'];

                // Verificar que hay suficiente inventario para el pedido
                if ($cantidad <= $cantidad_disponible) {
                    $precio = $producto_db['precio'];
                    $subtotal = $precio * $cantidad;

                    // Agregar o actualizar el producto en la sesión del pedido
                    if (isset($_SESSION['pedido'][$id])) {
                        // Si ya existe, solo actualizamos la cantidad y el subtotal
                        $_SESSION['pedido'][$id]['cantidad'] += $cantidad;
                        $_SESSION['pedido'][$id]['subtotal'] += $subtotal;
                    } else {
                        // Si no existe, lo agregamos a la sesión
                        $_SESSION['pedido'][$id] = [
                            'nombre' => $nombre,
                            'precio' => $precio,
                            'cantidad' => $cantidad,
                            'subtotal' => $subtotal,
                        ];
                    }

                    // Actualizar la cantidad en la base de datos
                    $nueva_cantidad = $cantidad_disponible - $cantidad;
                    $sql_actualizar = "UPDATE productos SET cantidad = ? WHERE id = ?";
                    $stmt_actualizar = $pdo->prepare($sql_actualizar);
                    $stmt_actualizar->execute([$nueva_cantidad, $id]);
                } else {
                    // Acumular el mensaje de error si no hay suficiente stock
                    $mensaje_error .= "No hay suficiente stock para el producto: $nombre. ";
                }
            } else {
                $mensaje_error .= "El producto con ID $id no existe. ";
            }
        }
    }
}

// Cerrar conexión
$pdo = null;

// Si hay mensajes de error, almacenarlos en la sesión para mostrarlos en la siguiente página
if (!empty($mensaje_error)) {
    $_SESSION['mensaje_error'] = $mensaje_error;
}

// Redirigir a la página del mesero
header("Location: mesero.php");
exit();
?>
