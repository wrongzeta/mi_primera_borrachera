<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de mesero
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 1) { // Cambia 1 si el rol de mesero tiene un ID diferente
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz del Mesero</title>
    <link rel="stylesheet" href="styles.css"> <!-- Si tienes estilos -->
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
    <p>Aquí puedes gestionar los pedidos.</p>

    <h2>Tomar Pedido</h2>
    <form action="procesar_pedido.php" method="POST">
        <label for="producto">Selecciona un producto:</label>
        <select id="producto" name="producto_id" required>
            <?php
            // Conexión a la base de datos
            $servidor = "localhost";
            $usuario = "root";
            $contraseña = "";
            $base_datos = "mi_primera_borrachera";

            $conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Consulta para obtener productos
            $sql = "SELECT id, nombre FROM productos";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
                while ($producto = $resultado->fetch_assoc()) {
                    echo "<option value='" . $producto['id'] . "'>" . $producto['nombre'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay productos disponibles</option>";
            }

            $conn->close();
            ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" required>

        <input type="submit" value="Agregar a Pedido">
    </form>

    <!-- Aquí puedes añadir más funcionalidades para ver pedidos, cerrar pedidos, etc. -->

</body>
</html>