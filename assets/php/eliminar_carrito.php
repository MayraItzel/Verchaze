<?php
session_start();
include 'db_connection.php';  // Conexión a la base de datos

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$id_carrito = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_carrito) {
    // Eliminar el producto del carrito
    $sql = "DELETE FROM carrito WHERE id = :id AND cliente_id = :cliente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_carrito, 'cliente_id' => $cliente_id]);
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit;
?>
