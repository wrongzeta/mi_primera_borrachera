<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_generar_reporte.css">
    <title>Generar Reporte de Ventas</title>
    <script>
        function volver() {
            window.history.back(); // Vuelve a la página anterior
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Generar Reporte de Ventas</h1>

        <form action="generar_reporte.php" method="POST">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" required><br><br>
            
            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" name="fecha_fin" required><br><br>

            <label for="formato">Seleccionar Formato de Reporte:</label>
            <select name="formato" required>
                <option value="csv">CSV</option>
                <option value="xlsx">XLSX</option>
            </select><br><br>

            <input type="submit" class="button" value="Generar Reporte">
        </form>

        <br>
        <button class="button" onclick="volver()">Volver</button>
    </div>

</body>
</html>
