<?php
session_start();
include 'db_connection.php';  // Conexión a la base de datos

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Inicializar la variable para los productos del carrito
$productos_carrito = [];
$total_compra = 0;  // Variable para el total de la compra

// Obtener productos en el carrito para este cliente
try {
    $sql = "SELECT carrito.id, productos.nombre, productos.precio, carrito.cantidad, productos.imagen
            FROM carrito
            JOIN productos ON carrito.producto_id = productos.id
            WHERE carrito.cliente_id = :cliente_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cliente_id' => $cliente_id]);
    $productos_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular el total de la compra
    foreach ($productos_carrito as $producto) {
        $total_compra += $producto['precio'] * $producto['cantidad'];
    }
} catch (Exception $e) {
    echo "Error al obtener los productos del carrito: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Personalización del carrito */
        body {
            background-color: #F5F5DC;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #8c6239;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background-color: #8c6239;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Botones */
        .btn-outline-secondary {
            color: #8c6239;
            border-color: #8c6239;
        }

        .btn-outline-secondary:hover {
            background-color: #8c6239;
            color: white;
        }

        .btn-delete {
            background-color: transparent;
            border: none;
            padding: 0;
        }

        .img-thumbnail {
            border-radius: 8px;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        /* Tabla personalizada */
        .table tr:hover {
            background-color: #f1f1f1;
        }

        .table .btn {
            padding: 0.3rem 0.8rem;
        }

        /* Estilos del pie de página */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #8c6239;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .total-carrito {
            font-size: 1.5rem;
            text-align: right;
            margin-top: 20px;
            color: #8c6239;
        }

        .btn-compra {
            background-color: #8c6239;
            border-color: #8c6239;
            color: white;
        }

        .btn-compra:hover {
            background-color: #704214;
            border-color: #704214;
        }
        /* Estilos del pie de página */
footer {
    text-align: center;
    padding: 20px;
    background-color: #8c6239;
    color: white;
    position: relative; /* Cambiar a 'relative' para que no esté fijo */
    width: 100%;
    margin-top: 20px; /* Añadir un margen superior para que no se pegue al contenido */
}

/* Añadir margen inferior en el contenedor para evitar que el pie de página lo cubra */
.container {
    margin-top: 50px;
    margin-bottom: 100px; /* Margen inferior para asegurar que el botón no sea cubierto */
}

    </style>
</head>
<body>

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg" style="background-color: #8c6239;">
    <div class="container-fluid">
        <a class="navbar-brand" href="Principal.php" style="color: white;">
            <img src="img/logo.jpg" alt="Verchaze Logo" width="50" height="50"> Verchaze
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Principal.php" style="color: white;">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_favoritos.php" style="color: white;">Favoritos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_carrito.php" style="color: white;">Carrito de compra</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_mis_pedidos.php" style="color: white;">Mis Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color: white;">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2>Carrito de Compras</h2>

    <?php if (empty($productos_carrito)): ?>
        <p>No tienes productos en tu carrito.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_carrito as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="img-thumbnail" alt="Imagen del producto"></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>
                            <a href="actualizar_carrito.php?id=<?php echo $producto['id']; ?>&accion=restar" class="btn btn-outline-secondary">-</a>
                            <?php echo htmlspecialchars($producto['cantidad']); ?>
                            <a href="actualizar_carrito.php?id=<?php echo $producto['id']; ?>&accion=sumar" class="btn btn-outline-secondary">+</a>
                        </td>
                        <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></td>
                        <td>
                            <a href="eliminar_carrito.php?id=<?php echo $producto['id']; ?>" class="btn-delete">
                                <img src="img/eliminar.png" alt="Eliminar" style="width: 25px;">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Mostrar el total y el botón para realizar la compra -->
        <div class="total-carrito">
            <p>Total: $<?php echo number_format($total_compra, 2); ?></p>
            <a href="realizar_compra.php" class="btn btn-compra">Realizar Compra</a>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>Verchaze &copy; 2024</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
