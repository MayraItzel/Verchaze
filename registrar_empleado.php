<?php
// Conectar a la base de datos
include('db_connection.php');

// Datos del nuevo empleado (cambia estos valores según sea necesario)
$nombre = "Anahi Estefania Reyes "; // Cambia este valor
$correoElectronico = "AnaEstefani1@gmail.com"; // Cambia este valor
$contrasena = "7297777150"; // Cambia este valor

// Cifrar la contraseña
$contrasenaCifrada = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO empleados (nombre, correoElectronico, contrasena) VALUES (:nombre, :correoElectronico, :contrasena)");
    $stmt->execute([
        'nombre' => $nombre,
        'correoElectronico' => $correoElectronico,
        'contrasena' => $contrasenaCifrada
    ]);
    echo "Empleado registrado con éxito.";
} catch (PDOException $e) {
    echo "Error al registrar al empleado: " . $e->getMessage();
}
?>
