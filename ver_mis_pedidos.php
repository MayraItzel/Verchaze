<?php
session_start();
include 'db_connection.php';

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Obtener los pedidos del cliente
$sql = "SELECT id, nombre, direccion, total, tipo_pago, estado FROM pedidos WHERE cliente_id = :cliente_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cliente_id' => $cliente_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5e9da;
        }
        .navbar {
            background-color: #5B3A29;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            background-color: #7B5B38;
            color: white;
        }
        .container {
            margin-top: 80px;
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
    <a class="nav-link" href="ver_mis_pedidos.php">Mis Pedidos</a>
</li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color: white;">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Sección de pedidos del cliente -->
<div class="container">
    <h2>Mis Pedidos</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Total</th>
                <th>Tipo de Pago</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pedido['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($pedido['tipo_pago']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                    <td>
                        <?php if ($pedido['estado'] == 'En camino'): ?>
                            <a href="confirmar_recepcion_pedido.php?pedido_id=<?php echo $pedido['id']; ?>" class="btn btn-success">Confirmar Entrega</a>
                        <?php elseif ($pedido['estado'] == 'Entregado'): ?>
                            <span class="text-success">Pedido Entregado</span>
                        <?php else: ?>
                            <span class="text-warning"><?php echo htmlspecialchars($pedido['estado']); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
