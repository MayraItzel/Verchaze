<?php
session_start();
include 'db_connection.php'; // Conexión a la base de datos

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];  // Asociamos el cliente actual
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$tipo_pago = $_POST['metodo_pago'];
$total = 0; // Para calcular el total

// Obtener los productos del carrito para este cliente
$sql = "SELECT productos.id, productos.precio, carrito.cantidad
        FROM carrito
        JOIN productos ON carrito.producto_id = productos.id
        WHERE carrito.cliente_id = :cliente_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cliente_id' => $cliente_id]);
$productos_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total del pedido
foreach ($productos_carrito as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

// Insertar el pedido en la tabla `pedidos`
$sql = "INSERT INTO pedidos (cliente_id, nombre, direccion, total, tipo_pago, estado) 
        VALUES (:cliente_id, :nombre, :direccion, :total, :tipo_pago, 'En proceso')";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'cliente_id' => $cliente_id,
    'nombre' => $nombre,
    'direccion' => $direccion,
    'total' => $total,
    'tipo_pago' => $tipo_pago
]);

// Obtener el ID del pedido recién insertado
$pedido_id = $pdo->lastInsertId();

// Insertar los productos del pedido en la tabla `pedido_productos`
foreach ($productos_carrito as $producto) {
    $sql = "INSERT INTO pedido_productos (pedido_id, producto_id, cantidad, precio) 
            VALUES (:pedido_id, :producto_id, :cantidad, :precio)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'pedido_id' => $pedido_id,
        'producto_id' => $producto['id'],
        'cantidad' => $producto['cantidad'],
        'precio' => $producto['precio']
    ]);
}

// Vaciar el carrito del cliente después de la compra
$sql = "DELETE FROM carrito WHERE cliente_id = :cliente_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cliente_id' => $cliente_id]);

// Redirigir a una página de confirmación
header("Location: confirmacion.php?pedido_id=" . $pedido_id);
exit;

?>
