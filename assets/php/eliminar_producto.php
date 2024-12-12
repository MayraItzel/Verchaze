<?php
// Iniciar sesión y conexión a la base de datos
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

// Verificar si se recibió un ID de producto válido
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Preparar la consulta para eliminar el producto
    $sql = "DELETE FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la consulta y pasar el ID del producto
    if ($stmt->execute([':id' => $producto_id])) {
        // Redirigir al panel de administración después de eliminar el producto
        header("Location: admin.php?mensaje=Producto eliminado");
    } else {
        // Si ocurre un error, redirigir con un mensaje de error
        header("Location: admin.php?mensaje=Error al eliminar el producto");
    }
} else {
    // Redirigir si no se proporciona un ID de producto
    header("Location: admin.php?mensaje=ID de producto no válido");
}

exit; // Asegurar que no se ejecute más código después de la redirección
?>
