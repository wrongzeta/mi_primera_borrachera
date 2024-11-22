<?php
require 'conex.php'; // Conexión a la base de datos


// Obtener usuarios existentes
$query = "SELECT u.id, u.username, r.nombre AS rol, s.nombre AS sede 
          FROM usuarios u
          JOIN roles r ON u.rol_id = r.id
          JOIN sedes s ON u.sede_id = s.id";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sistema</title>
    <link rel="stylesheet" href="styles_configuracion.css">
</head>
<body>
    <div class="form-container">
        <h2>Configuración del Sistema</h2>
        <p>Aquí podrás gestionar usuarios, roles y parametrización del sistema.</p>

        <!-- Formulario para crear usuario -->
        <form method="POST" action="crear_usuario.php">
            <label for="nombre">Nombre del Usuario:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="rol">Rol:</label>
            <select name="rol" id="rol">
                <option value="mesero">Mesero</option>
                <option value="cajero">Cajero</option>
                <option value="administrador">Administrador</option>
            </select>

            <label for="sede">Sede:</label>
            <select name="sede" id="sede">
                <option value="Restrepo">Restrepo</option>
                <option value="Primera de Mayo">Primera de Mayo</option>
                <option value="Galerías">Galerías</option>
                <option value="Chía">Chía</option>
            </select>

            <label for="password">Contraseña:</label>
            <input type="text" name="password" id="password" required>

            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="text" name="confirm_password" id="confirm_password" required>

            <button type="submit">Crear Usuario</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Usuarios Registrados</h2>
        <form method="POST" action="editar_usuario.php">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Nueva Contraseña</th>
                        <th>Rol</th>
                        <th>Sede</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><input type="text" name="username[<?php echo $row['id']; ?>]" value="<?php echo $row['username']; ?>"></td>
                        <td><input type="text" name="password[<?php echo $row['id']; ?>]" placeholder="Nueva contraseña (opcional)"></td>
                        <td>
                            <select name="rol[<?php echo $row['id']; ?>]">
                                <option value="1" <?php echo $row['rol'] === 'Mesero' ? 'selected' : ''; ?>>Mesero</option>
                                <option value="2" <?php echo $row['rol'] === 'Cajero' ? 'selected' : ''; ?>>Cajero</option>
                                <option value="3" <?php echo $row['rol'] === 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </td>
                        <td>
                            <select name="sede[<?php echo $row['id']; ?>]">
                                <option value="1" <?php echo $row['sede'] === 'Restrepo' ? 'selected' : ''; ?>>Restrepo</option>
                                <option value="2" <?php echo $row['sede'] === 'Primera de Mayo' ? 'selected' : ''; ?>>Primera de Mayo</option>
                                <option value="3" <?php echo $row['sede'] === 'Galerías' ? 'selected' : ''; ?>>Galerías</option>
                                <option value="4" <?php echo $row['sede'] === 'Chía' ? 'selected' : ''; ?>>Chía</option>
                            </select>
                        </td>
                        <td><button type="submit" name="guardar[<?php echo $row['id']; ?>]">Guardar</button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
