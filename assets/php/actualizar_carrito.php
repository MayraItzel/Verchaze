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
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

if ($id_carrito && $accion) {
    if ($accion == 'sumar') {
        // Incrementar la cantidad
        $sql = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id = :id AND cliente_id = :cliente_id";
    } elseif ($accion == 'restar') {
        // Verificar si la cantidad es mayor a 1 antes de restar
        $sql = "UPDATE carrito SET cantidad = GREATEST(cantidad - 1, 1) WHERE id = :id AND cliente_id = :cliente_id";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_carrito, 'cliente_id' => $cliente_id]);
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit;
?>
