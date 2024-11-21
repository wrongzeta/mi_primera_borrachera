<?php
require 'conexion.php';

echo "<h2>Configuración del Sistema</h2>";
echo "<p>Aquí podrás gestionar usuarios, roles y parametrización del sistema.</p>";

// Ejemplo de formulario para agregar un usuario
echo '
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
        <button type="submit">Crear Usuario</button>
    </form>
';
?>
