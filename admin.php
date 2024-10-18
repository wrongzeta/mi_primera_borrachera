<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador (rol ID = 3)
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz del Administrador</title>
    <link rel="stylesheet" href="styles_administrador.css"> <!-- Archivo de estilos para administrador -->
</head>
<body>
    <h1>Bienvenido, Administrador <?php echo $_SESSION['usuario']; ?></h1>
    <p>Aquí puedes gestionar todos los aspectos del bar.</p>

    <h2>Gestión de Inventario</h2>
    <form action="actualizar_inventario.php" method="POST">
        <label for="producto_id">ID del Producto:</label>
        <input type="number" id="producto_id" name="producto_id" required>

        <label for="cantidad">Cantidad a actualizar:</label>
        <input type="number" id="cantidad" name="cantidad" required>

        <input type="submit" value="Actualizar Inventario">
    </form>

    <h2>Ver Reportes</h2>
    <form action="ver_reportes.php" method="GET">
        <label for="sede">Sede:</label>
        <select id="sede" name="sede">
            <option value="Restrepo">Restrepo</option>
            <option value="Primera de Mayo">Primera de Mayo</option>
            <option value="Galerías">Galerías</option>
            <option value="Chía">Chía</option>
        </select>

        <label for="fecha_inicio">Desde:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>

        <label for="fecha_fin">Hasta:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required>

        <input type="submit" value="Ver Reporte">
    </form>
</body>
</html>