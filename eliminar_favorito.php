<?php
session_start();
include 'db_connection.php';  // Conexión a la base de datos

if (!isset($_SESSION['cliente_id'])) {
    echo "Debes iniciar sesión para eliminar productos de favoritos.";
    exit();
}

$cliente_id = $_SESSION['cliente_id'];

if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    // Eliminar el producto de favoritos
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE cliente_id = :cliente_id AND producto_id = :producto_id");
    $stmt->execute(['cliente_id' => $cliente_id, 'producto_id' => $producto_id]);

    // Redirigir de vuelta a la página de favoritos
    header("Location: ver_favoritos.php");
    exit();
} else {
    echo "No se proporcionó ningún producto.";
}
?>
