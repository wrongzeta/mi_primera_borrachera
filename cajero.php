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
    <!-- Enlace a FontAwesome para íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
        <p>Selecciona una opción para continuar.</p>

        <div class="options-container">
            <!-- Botón para Cierre de Pedidos -->
            <a href="cerrar_pedido.php" class="logout-button">
                <i class="fas fa-check-circle"></i> Cierre de Pedidos
            </a>

            <!-- Botón para Generar Reporte -->
            <a href="generar_reporte.php" class="logout-button">
                <i class="fas fa-chart-line"></i> Generar Reporte
            </a>

            <!-- Botón para Alimentar Inventario -->
            <a href="alimentar_inventario.php" class="logout-button">
                <i class="fas fa-box-open"></i> Alimentar Inventario
            </a>
        </div>

        <a href="logout.php" class="logout-button">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </div>
</body>
</html>
