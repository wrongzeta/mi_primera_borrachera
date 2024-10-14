<?php
// Contraseña a hashear
$password = 'contraseña3';

// Generar el hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Mostrar el hash
echo $hashed_password;
?>
