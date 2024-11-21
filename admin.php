<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene el rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 3) { // Asumiendo que 3 es el rol de administrador
    header('Location: login.php'); // Redirige al login si no está autenticado
    exit;
}

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php'); // Redirige al login después de cerrar sesión
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Mi Primera Borrachera</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles_admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Menú lateral -->
        <aside class="sidebar">
            <div class="logo">
                <img src="logo.png" alt="Logo" class="logo-img">
                <h2>Mi Primera Borrachera</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="pedidos.php" data-section="Pedidos" class="menu-link"><i class="fas fa-tasks"></i> Gestión de Pedidos</a></li>
                    <li><a href="inventario.php" data-section="Inventario" class="menu-link"><i class="fas fa-boxes"></i> Gestión de Inventario</a></li>
                    <li><a href="reportes.php" data-section="Reportes" class="menu-link"><i class="fas fa-chart-line"></i> Reportes</a></li>
                    <li><a href="configuracion.php" data-section="Configuracion" class="menu-link"><i class="fas fa-cogs"></i> Configuración</a></li>
                    <!-- Botón de Cerrar sesión en el menú lateral -->
                    <li><a href="?logout=true" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Sección dinámica -->
        <main class="main-content">
            <div id="content">
                <h1>Bienvenido, Administrador</h1>
                <p>Selecciona una opción del menú para comenzar.</p>
            </div>
        </main>
    </div>

    <script>
        const links = document.querySelectorAll('.menu-link');
        const content = document.getElementById('content');

        links.forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const section = e.target.href;

                try {
                    const response = await fetch(section);
                    if (response.ok) {
                        const html = await response.text();
                        content.innerHTML = html;
                    } else {
                        content.innerHTML = `<p>Error al cargar la sección.</p>`;
                    }
                } catch (error) {
                    content.innerHTML = `<p>Error al conectar con el servidor.</p>`;
                }
            });
        });
    </script>
</body>
</html>
