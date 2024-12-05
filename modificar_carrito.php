<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $producto_id = $_POST['producto_id'];
    $nueva_cantidad = $_POST['nueva_cantidad'];

    $stmt = $pdo->prepare("UPDATE carrito SET cantidad = :nueva_cantidad WHERE cliente_id = :cliente_id AND producto_id = :producto_id");
    $stmt->execute(['nueva_cantidad' => $nueva_cantidad, 'cliente_id' => $cliente_id, 'producto_id' => $producto_id]);

    header("Location: vercarrito.php");
    exit();
} else {
    echo "Debes iniciar sesión para modificar la cantidad.";
}
?>
