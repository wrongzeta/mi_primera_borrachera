<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) {
    header('Location: login.php');
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_primera_borrachera";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Variables para filtros (por fecha y mesa)
$filter_fecha = "";
$filter_mesa = "";

if (isset($_GET['fecha'])) {
    $filter_fecha = $_GET['fecha'];
}

if (isset($_GET['mesa'])) {
    $filter_mesa = $_GET['mesa'];
}

// Consulta SQL con filtros
$sql = "SELECT p.id, p.fecha, p.estado, m.nombre AS mesa, u.nombre AS usuario
        FROM pedidos p
        JOIN mesas m ON p.mesa_id = m.id
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE 1"; // Filtro base

// Filtrar por fecha si es necesario
if (!empty($filter_fecha)) {
    $sql .= " AND DATE(p.fecha) = '$filter_fecha'";
}

// Filtrar por mesa si es necesario
if (!empty($filter_mesa)) {
    $sql .= " AND m.nombre LIKE '%$filter_mesa%'";
}

$sql .= " ORDER BY p.fecha DESC"; // Ordenar por fecha de pedido (más reciente primero)

$result = $conn->query($sql);
?>

<h2>Gestión de Pedidos</h2>

<!-- Filtros -->
<form action="pedidos.php" method="get">
    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" value="<?php echo $filter_fecha; ?>">
    
    <label for="mesa">Mesa:</label>
    <input type="text" name="mesa" value="<?php echo $filter_mesa; ?>" placeholder="Buscar mesa...">
    
    <button type="submit">Filtrar</button>
</form>

<p>Aquí puedes gestionar los pedidos.</p>

<table>
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Fecha</th>
            <th>Mesa</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Mostrar los pedidos
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['fecha'] . "</td>";
                echo "<td>" . $row['mesa'] . "</td>";
                echo "<td>" . $row['usuario'] . "</td>";
                echo "<td>" . ucfirst($row['estado']) . "</td>";
                echo "<td>
                        <form action='cambiar_estado.php' method='post'>
                            <input type='hidden' name='pedido_id' value='" . $row['id'] . "'>
                            <button type='submit' name='cambiar_estado' value='cerrado'>Cerrar Pedido</button>
                        </form>
                        <a href='ver_pedido.php?id=" . $row['id'] . "'>Ver Detalles</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron pedidos.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
?>
