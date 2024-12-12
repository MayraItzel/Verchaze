<?php
session_start(); // Iniciar la sesión

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio o a la página de inicio de sesión
header("Location: index.php");
exit;
?>
