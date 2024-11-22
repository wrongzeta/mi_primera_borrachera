<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header('Location: login.php');
    exit;
}

// Incluir el archivo de conexión
include('conection.php');

// Verifica si la conexión fue exitosa
if (!$conexion) {
    die("Error de conexión a la base de datos.");
} else {
    echo "Conexión exitosa";  // Solo para depuración
}

// Obtener las sedes disponibles para el administrador
$query_sedes = "SELECT id, nombre FROM sedes";
$resultado_sedes = $conexion->query($query_sedes);

// Verificar si se ha enviado el formulario de reportes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sede_id = $_POST['sede'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $formato = $_POST['formato'];

    // Consulta SQL para obtener los detalles de ventas en el rango de fechas y por sede
    $consulta_ventas = "SELECT p.nombre AS producto, dp.cantidad AS cantidad_vendida, p.precio_venta, pe.fecha AS fecha_pedido
                        FROM detalles_pedido dp
                        JOIN productos p ON dp.producto_id = p.id
                        JOIN pedidos pe ON dp.pedido_id = pe.id
                        JOIN mesas m ON pe.mesa_id = m.id
                        WHERE m.sede_id = ? AND pe.fecha BETWEEN ? AND ?";
    $stmt = $conexion->prepare($consulta_ventas);
    $stmt->bind_param("iss", $sede_id, $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $resultado_ventas = $stmt->get_result();

    // Generar el reporte según el formato seleccionado
    if ($formato == 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="reporte_ventas_sede_' . $sede_id . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Producto', 'Cantidad', 'Precio de Venta', 'Fecha de Pedido']); // Cabecera
        while ($row = $resultado_ventas->fetch_assoc()) {
            fputcsv($output, $row);
        }
        fclose($output);
    } elseif ($formato == 'xlsx') {
        // Lógica para generar un archivo XLSX (utilizando la librería PhpSpreadsheet, por ejemplo)
        require_once 'vendor/autoload.php'; // Si usas PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Producto');
        $sheet->setCellValue('B1', 'Cantidad');
        $sheet->setCellValue('C1', 'Precio de Venta');
        $sheet->setCellValue('D1', 'Fecha de Pedido');

        $row_num = 2;
        while ($row = $resultado_ventas->fetch_assoc()) {
            $sheet->setCellValue('A' . $row_num, $row['producto']);
            $sheet->setCellValue('B' . $row_num, $row['cantidad_vendida']);
            $sheet->setCellValue('C' . $row_num, $row['precio_venta']);
            $sheet->setCellValue('D' . $row_num, $row['fecha_pedido']);
            $row_num++;
        }

        // Guardar el archivo XLSX
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $file_name = 'reporte_ventas_sede_' . $sede_id . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        $writer->save('php://output');
    }

    // Cerrar la conexión después de generar el reporte
    $conexion->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte de Ventas - Administrador</title>
    <link rel="stylesheet" href="styles_reportes.css">
</head>
<body>

    <div class="container">
        <h1>Generar Reporte de Ventas</h1>

        <form action="reportes.php" method="POST">
            <label for="sede">Seleccionar Sede:</label>
            <select name="sede" required>
                <?php
                    // Mostrar las sedes disponibles en el formulario
                    while ($sede = $resultado_sedes->fetch_assoc()) {
                        echo "<option value='" . $sede['id'] . "'>" . $sede['nombre'] . "</option>";
                    }
                ?>
            </select><br><br>
            
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
        <button class="button" onclick="window.history.back()">Volver</button>
    </div>

</body>
</html>
