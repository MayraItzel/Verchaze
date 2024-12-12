<?php
// Parámetros de conexión a la base de datos
$host = 'localhost';    // Servidor de la base de datosor
$db = 'tienda_bolsas';  // Nombre de la base de datos
$user = 'root';         // Usuario de la base de datos
$pass = '';       

try {
    // Crea una instancia de PDO para conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

    // Configura el modo de error de PDO para que lance excepciones si ocurre un error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si ocurre un error de conexión, muestra un mensaje y termina la ejecución del script
    die("Error en la conexión: " . $e->getMessage());
}
?>
