<?php
session_start();
include 'db_connection.php';  // Conexión a la base de datos

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$producto_id = isset($_GET['producto_id']) ? $_GET['producto_id'] : null;
$cantidad = isset($_GET['cantidad']) ? (int)$_GET['cantidad'] : 1;

if ($producto_id) {
    // Verificar el stock disponible
    $sql_stock = "SELECT stock FROM productos WHERE id = :producto_id";
    $stmt_stock = $pdo->prepare($sql_stock);
    $stmt_stock->execute(['producto_id' => $producto_id]);
    $stock_disponible = $stmt_stock->fetchColumn();

    if ($stock_disponible >= $cantidad) {
        // Insertar o actualizar el carrito
        $sql = "INSERT INTO carrito (cliente_id, producto_id, cantidad) 
                VALUES (:cliente_id, :producto_id, :cantidad)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + :cantidad";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'cliente_id' => $cliente_id,
            'producto_id' => $producto_id,
            'cantidad' => $cantidad
        ]);

        // Restar la cantidad del stock
        $sql_actualizar_stock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :producto_id";
        $stmt_actualizar_stock = $pdo->prepare($sql_actualizar_stock);
        $stmt_actualizar_stock->execute([
            'cantidad' => $cantidad,
            'producto_id' => $producto_id
        ]);

        header("Location: ver_carrito.php");
        exit;
    } else {
        echo "No hay suficiente stock disponible.";
    }
}
?>
