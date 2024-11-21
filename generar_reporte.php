<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene un rol adecuado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['sede_id'])) {
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

// Procesar el formulario de fechas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['formato'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $formato = $_POST['formato'];

    // Consulta SQL para obtener los detalles del reporte
    $query = $pdo->prepare("
        SELECT pr.nombre AS producto, dp.cantidad, pr.costo_venta, pr.precio_venta, 
               (pr.precio_venta - pr.costo_venta) AS ganancia, s.nombre AS sede
        FROM detalles_pedido dp
        JOIN productos pr ON dp.producto_id = pr.id
        JOIN pedidos p ON dp.pedido_id = p.id
        JOIN usuarios u ON p.usuario_id = u.id
        JOIN sedes s ON u.sede_id = s.id
        WHERE p.fecha BETWEEN :fecha_inicio AND :fecha_fin
    ");
    $query->execute([
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin
    ]);
    $ventas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Generar archivo CSV o XLSX
    if ($formato == 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="reporte_ventas.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Producto', 'Cantidad', 'Costo de Venta', 'Precio de Venta', 'Ganancia', 'Sede']); // Cabecera
        
        foreach ($ventas as $venta) {
            fputcsv($output, $venta);
        }
        
        fclose($output);
        exit();
    }

    // Generar archivo XLSX (usando PhpSpreadsheet)
    if ($formato == 'xlsx') {
        require 'vendor/autoload.php'; // Asegúrate de tener la librería PhpSpreadsheet instalada
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Cabecera
        $sheet->setCellValue('A1', 'Producto');
        $sheet->setCellValue('B1', 'Cantidad');
        $sheet->setCellValue('C1', 'Costo de Venta');
        $sheet->setCellValue('D1', 'Precio de Venta');
        $sheet->setCellValue('E1', 'Ganancia');
        $sheet->setCellValue('F1', 'Sede');
        
        // Llenar los datos
        $row = 2;
        foreach ($ventas as $venta) {
            $sheet->setCellValue('A' . $row, $venta['producto']);
            $sheet->setCellValue('B' . $row, $venta['cantidad']);
            $sheet->setCellValue('C' . $row, $venta['costo_venta']);
            $sheet->setCellValue('D' . $row, $venta['precio_venta']);
            $sheet->setCellValue('E' . $row, $venta['ganancia']);
            $sheet->setCellValue('F' . $row, $venta['sede']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_ventas.xlsx"');
        $writer->save('php://output');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_cajero.css">
    <title>Generar Reporte de Ventas</title>
    <script>
        function volver() {
            window.history.back(); // Vuelve a la página anterior
        }
    </script>
</head>
<body>

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

        <input type="submit" value="Generar Reporte">
    </form>
    
    <br>
    <!-- Botón para volver -->
    <button class="logout-button" onclick="volver()">Volver</button>

</body>
</html>
