<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de cajero (rol ID = 2)
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 2) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz del Cajero</title>
    <link rel="stylesheet" href="styles_cajero.css">
</head>
<body>
    <div class="form-container">
        <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
        <p>Selecciona una opción para continuar.</p>

        <div class="options-container">
            <!-- Botón para Cierre de Pedidos -->
            <a href="cerrar_pedido.php" class="logout-button">Cierre de Pedidos</a>

            <!-- Botón para Generar Reporte -->
            <a href="generar_reporte.php" class="logout-button">Generar Reporte</a>
        </div>

        <a href="logout.php" class="logout-button">Cerrar Sesión</a>
    </div>
</body>
</html>


