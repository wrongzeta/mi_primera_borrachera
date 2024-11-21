<?php
session_start(); // Iniciar la sesión

// Destruir la sesión
session_destroy();

// Redirigir al index
header("Location: index.html");
exit();
?>
