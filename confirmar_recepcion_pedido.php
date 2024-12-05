<?php
session_start();
include 'db_connection.php';

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['pedido_id'])) {
    $pedido_id = $_GET['pedido_id'];

    // Actualizar el estado del pedido a "Entregado"
    $sql = "UPDATE pedidos SET estado = 'Entregado' WHERE id = :pedido_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pedido_id' => $pedido_id]);

    // Redirigir al cliente a la página de "Mis Pedidos"
    header("Location: ver_mis_pedidos.php");
    exit;
}
?>
