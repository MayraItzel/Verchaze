<?php
// confirmacion.php
session_start();
include 'db_connection.php';

if (!isset($_GET['pedido_id'])) {
    echo "Pedido no encontrado.";
    exit;
}

$pedido_id = $_GET['pedido_id'];

// Obtener información del pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = :pedido_id");
$stmt->execute(['pedido_id' => $pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5e9da;
            font-family: 'Arial', sans-serif;
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
            margin-top: 100px;
        }

        h2 {
            color: #8c6239;
            margin-bottom: 20px;
        }

        p {
            color: #704214;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
            color: #704214;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="Principal.php">
            <img src="img/logo.jpg" alt="Verchaze Logo" style="width: 60px; height: 60px;"> Verchaze
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="offcanvasNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ver_favoritos.php">Favoritos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vercarrito.php">Carrito</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_mis_pedidos.php">Mis Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido de Confirmación -->
<div class="container">
    <h2>Confirmación de Pedido</h2>
    <p>Gracias por tu compra, <?php echo htmlspecialchars($pedido['nombre']); ?>.</p>
    <p>Tu pedido está en proceso. A continuación los detalles:</p>
    <ul>
        <li><strong>ID del Pedido:</strong> <?php echo $pedido['id']; ?></li>
        <li><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></li>
        <li><strong>Dirección de envío:</strong> <?php echo htmlspecialchars($pedido['direccion']); ?></li>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
