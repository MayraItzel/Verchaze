<?php
// Reemplaza 'tu_contraseña_aqui' con la contraseña que quieres cifrar
$contrasenaPlana = 'tu_contraseña_aqui'; 

// Cifrar la contraseña
$contrasenaCifrada = password_hash($contrasenaPlana, PASSWORD_DEFAULT);

// Mostrar la contraseña cifrada
echo $contrasenaCifrada;
?>
