<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $producto_id = $_GET['producto_id'];

    // Verificar si el producto ya está en los favoritos en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM favoritos WHERE cliente_id = :cliente_id AND producto_id = :producto_id");
    $stmt->execute(['cliente_id' => $cliente_id, 'producto_id' => $producto_id]);
    $favorito = $stmt->fetch();

    if (!$favorito) {
        // Si no existe, agregar a la tabla de favoritos
        $stmt = $pdo->prepare("INSERT INTO favoritos (cliente_id, producto_id) VALUES (:cliente_id, :producto_id)");
        $stmt->execute(['cliente_id' => $cliente_id, 'producto_id' => $producto_id]);
    }

    header("Location: ver_favoritos.php");
    exit();
} else {
    echo "Por favor, inicia sesión para agregar productos a tus favoritos.";
}
?>
