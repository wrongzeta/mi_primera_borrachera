<?php
// Contraseña a hashear
$password = 'DavidContra123';

// Generar el hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Mostrar el hash
echo $hashed_password;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Primera Borrachera</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
</body>
</html>