<?php
// generar_hash.php (eliminar después de usar)
$password = '1324MMa1240$'; // ¡Cambia esto por tu contraseña!
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Tu contraseña hasheada es: <br>";
echo $hashedPassword;
// Ejemplo de salida: $2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
?>