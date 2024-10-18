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
    <link rel="stylesheet" href="styles_cajero.css"> <!-- Archivo de estilos para cajero -->
</head>
<body>
    <div class="form-container">
        <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
        <p>Aquí puedes gestionar los pagos y ver reportes.</p>

        <h2>Registrar Pago</h2>
        <form action="registrar_pago.php" method="POST">
            <label for="pedido_id">ID del Pedido:</label>
            <input type="number" id="pedido_id" name="pedido_id" required>

            <label for="monto">Monto a pagar:</label>
            <input type="number" id="monto" name="monto" required>

            <input type="submit" value="Registrar Pago">
        </form>

        <h2>Generar Reporte</h2>
        <form action="generar_reporte.php" method="GET">
            <label for="fecha_inicio">Desde:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Hasta:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <input type="submit" value="Generar Reporte">
        </form>
    </div>
</body>
</html>
