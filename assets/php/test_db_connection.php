<?php
include 'db_connection.php';

if ($pdo) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "No se pudo conectar a la base de datos.";
}
?>
