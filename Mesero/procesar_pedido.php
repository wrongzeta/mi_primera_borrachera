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

    // Obtener el ID de la mesa
    $query = $pdo->prepare("SELECT id FROM mesas WHERE numero = :numero");
    $query->execute(['numero' => $mesa_numero]);
    $mesa = $query->fetch(PDO::FETCH_ASSOC);

    if ($mesa) {
        // Iniciar un nuevo pedido
        $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que tienes el ID del usuario en la sesión
        $query_pedido = $pdo->prepare("INSERT INTO pedidos (usuario_id, mesa_id) VALUES (:usuario_id, :mesa_id)");
        $query_pedido->execute(['usuario_id' => $usuario_id, 'mesa_id' => $mesa['id']]);
        $pedido_id = $pdo->lastInsertId(); // Obtener el ID del nuevo pedido

        // Cambiar el estado de la mesa a 'ocupado'
        $query_estado = $pdo->prepare("UPDATE mesas SET estado = 'ocupado' WHERE id = :id");
        $query_estado->execute(['id' => $mesa['id']]);

        // Verificar si se enviaron productos en el formulario
        if (isset($_POST['productos']) && !empty($_POST['productos'])) {
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

                            // Guardar en la tabla detalles_pedido
                            $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
                            $stmt_detalle = $pdo->prepare($sql_detalle);
                            $stmt_detalle->execute([$pedido_id, $id, $cantidad]);

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
    } else {
        $mensaje_error .= "Mesa no encontrada.";
    }
} else {
    $mensaje_error .= "No se ha seleccionado ninguna mesa.";
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
