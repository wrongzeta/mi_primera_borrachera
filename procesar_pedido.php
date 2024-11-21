<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 1) { // Verifica si es mesero
    // Si no está logueado, asignamos un valor predeterminado para usuario_id
    $usuario_id = null; // O puedes asignar un valor genérico si lo prefieres
} else {
    // Si está logueado, obtenemos el ID del usuario desde la sesión
    $usuario_id = $_SESSION['usuario_id'];
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
        // Si no hay usuario logueado, podemos asignar un usuario genérico o nulo
        $sede_id = $_SESSION['sede_id'] ?? 1; // Usar sede_id de la sesión o un valor predeterminado

        // Iniciar un nuevo pedido (sin usuario_id si no hay sesión)
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
                    // Consultar la cantidad disponible del producto en el inventario de la sede específica
                    $sql = "SELECT cantidad FROM inventarios WHERE producto_id = ? AND sede_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id, $sede_id]);

                    if ($stmt->rowCount() > 0) {
                        $inventario_db = $stmt->fetch(PDO::FETCH_ASSOC);
                        $cantidad_disponible = $inventario_db['cantidad'];

                        // Verificar que hay suficiente inventario para el pedido
                        if ($cantidad <= $cantidad_disponible) {
                            // Consultar el precio y nombre del producto
                            $sql_producto = "SELECT nombre, precio FROM productos WHERE id = ?";
                            $stmt_producto = $pdo->prepare($sql_producto);
                            $stmt_producto->execute([$id]);
                            $producto_db = $stmt_producto->fetch(PDO::FETCH_ASSOC);
                            $nombre = $producto_db['nombre'];
                            $precio = $producto_db['precio'];
                            $subtotal = $precio * $cantidad;

                            // Guardar en la tabla detalles_pedido
                            $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
                            $stmt_detalle = $pdo->prepare($sql_detalle);
                            $stmt_detalle->execute([$pedido_id, $id, $cantidad]);

                            // Actualizar la cantidad en el inventario
                            $nueva_cantidad = $cantidad_disponible - $cantidad;
                            $sql_actualizar = "UPDATE inventarios SET cantidad = ? WHERE producto_id = ? AND sede_id = ?";
                            $stmt_actualizar = $pdo->prepare($sql_actualizar);
                            $stmt_actualizar->execute([$nueva_cantidad, $id, $sede_id]);
                        } else {
                            // Acumular el mensaje de error si no hay suficiente stock
                            $mensaje_error .= "No hay suficiente stock para el producto: $nombre. ";
                        }
                    } else {
                        $mensaje_error .= "El producto con ID $id no está en el inventario de esta sede. ";
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
