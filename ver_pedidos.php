<?php
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

// Obtener los pedidos
$sql = "SELECT id, nombre, direccion, total, tipo_pago, estado FROM pedidos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Pedidos - Verchaze</title>
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

<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">
            <img src="img/logo.jpg" alt="Verchaze Logo" style="width: 60px; height: 60px;"> Verchaze
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="offcanvasNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_pedidos.php">Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                </li>
            </ul>
            <span class="navbar-text ms-3">
                Empleado: 
                <?php
                if (isset($_SESSION['empleado_nombre'])) {
                    echo htmlspecialchars($_SESSION['empleado_nombre']); 
                } else {
                    echo "Invitado";
                }
                ?>
            </span>
            
        </div>
    </div>
</nav>

<!-- Sección de administración de pedidos -->
<div class="container">
    <h2>Administrar Pedidos</h2>
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
                        <?php if ($pedido['estado'] == 'En proceso'): ?>
                            <a href="actualizar_pedido.php?id=<?php echo $pedido['id']; ?>&estado=En camino" class="btn btn-primary">Marcar como En camino</a>
                        <?php elseif ($pedido['estado'] == 'En camino'): ?>
                            <span class="badge bg-warning">En camino</span>
                        <?php elseif ($pedido['estado'] == 'Entregado'): ?>
                            <span class="badge bg-success">Pedido Entregado</span>
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
