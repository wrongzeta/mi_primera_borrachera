<?php
session_start();

// Conexión a la base de datos
$servidor = "localhost"; // Cambia esto si es necesario
$usuario = "root"; // Cambia esto si tienes un usuario diferente
$contraseña = ""; // Cambia esto si tienes una contraseña
$base_datos = "mi_primera_borrachera";

$conn = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibe datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    // Consulta para buscar al usuario, incluyendo el sede_id
    $sql = "SELECT * FROM usuarios WHERE username = ?"; // Asegúrate de que 'username' es correcto
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica la contraseña
        if (password_verify($contraseña, $usuario['password'])) { // Cambié 'contraseña' a 'password'
            // Contraseña correcta, iniciar sesión
            $_SESSION['usuario'] = $usuario['username']; // Cambié 'nombre' a 'username'
            $_SESSION['rol'] = $usuario['rol_id']; // Suponiendo que 'rol_id' es el nombre de la columna del rol
            $_SESSION['sede_id'] = $usuario['sede_id']; // Asigna el sede_id a la sesión

            // Redirige a la página correspondiente según el rol
            if ($_SESSION['rol'] === 1) { // Asumiendo que 1 es el ID del rol de mesero
                header("Location: pagina_mesas.php");
            } elseif ($_SESSION['rol'] === 2) { // Asumiendo que 2 es el ID del rol de cajero
                header("Location: cajero.php"); // Cambia a la ruta de tu página del cajero
            } else {
                header("Location: admin.php"); // Cambia a la ruta de tu página de administrador
            }
            exit();
        } else {
            // Redirige a index.html con un parámetro de error
            header("Location: index.html?error=1");
            exit();
        }
    } else {
        echo "No se encontró el usuario.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mi Primera Borrachera</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Logo a la izquierda -->
        <div class="logo-container">
            <img src="logo.png" alt="Logo Mi Primera Borrachera">
        </div>

        <!-- Formulario a la derecha -->
        <div class="login-container">
            <h2>Iniciar sesión</h2>
            <form action="login.php" method="POST">
                <label for="nombre">Nombre de usuario:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <input type="submit" value="Iniciar sesión">
            </form>
        </div>
    </div>
</body>
</html>
