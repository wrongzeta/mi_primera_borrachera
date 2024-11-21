<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) { // Asumiendo que 3 es el rol de administrador
    header('Location: login.php'); // Redirige al login si no está autenticado
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_primera_borrachera";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los pedidos
$query = "SELECT p.id, p.mesa_id, p.fecha, u.username 
          FROM pedidos p 
          JOIN usuarios u ON p.usuario_id = u.id";

// Mostrar la consulta SQL para depurar
echo "Consulta SQL: " . $query . "<br>";

$result = $conn->query($query);

// Verifica si hubo un error en la consulta
if ($result === false) {
    echo "Error en la consulta: " . $conn->error;  // Si hay error en la consulta, lo muestra
    exit;
}

// Mostrar el número de filas retornadas
echo "Número de filas: " . $result->num_rows . "<br>";

if ($result->num_rows > 0) {
    // Muestra los pedidos
    echo '<table>';
    echo '<thead><tr><th>ID Pedido</th><th>Mesa</th><th>Fecha</th><th>Usuario</th><th>Acciones</th></tr></thead>';
    echo '<tbody>';
    
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $mesa_id = $row['mesa_id'];
        $fecha = $row['fecha'];
        $usuario = $row['username'];

        // Mostrar los detalles de cada pedido
        echo "<tr>
                <td>$id</td>
                <td>$mesa_id</td>
                <td>$fecha</td>
                <td>$usuario</td>
                <td>
                    <a href='ver_pedido.php?id=$id'>Ver Pedido</a> | 
                    <a href='cambiar_estado.php?id=$id'>Cambiar Estado</a>
                </td>
              </tr>";
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo "No se han encontrado pedidos.";
}

$conn->close();
?>
