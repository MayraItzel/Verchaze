<?php
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $pedido_id = $_GET['id'];
    $nuevo_estado = $_GET['estado'];

    // Actualizar el estado del pedido
    $sql = "UPDATE pedidos SET estado = :estado WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['estado' => $nuevo_estado, 'id' => $pedido_id]);

    // Redirigir de vuelta a la página de pedidos
    header("Location: ver_pedidos.php");
    exit;
}
?>
