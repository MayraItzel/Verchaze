<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $stmt = $pdo->prepare("SELECT productos.id, productos.nombre, productos.descripcion, productos.precio, productos.imagen 
                           FROM favoritos 
                           JOIN productos ON favoritos.producto_id = productos.id 
                           WHERE favoritos.cliente_id = :cliente_id");
    $stmt->execute(['cliente_id' => $cliente_id]);
    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Favoritos - Verchaze</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Helvetica', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #8e582c;
            padding: 15px;
            color: white;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .navbar .nav-link {
            color: white;
            margin-right: 15px;
        }

        .navbar .nav-link:hover {
            color: #e6aa77;
        }

        .container {
            flex-grow: 1; /* Esto asegura que el contenido crezca para llenar el espacio */
        }

        .favorites-container {
            margin-top: 50px;
        }

        .favorite-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .favorite-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .favorite-item h5 {
            margin-bottom: 0;
            color: #704214;
        }

        .favorite-item p {
            margin-bottom: 0;
            color: #aa7444;
        }

        .icon-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 5px;
        }

        .icon-btn img {
            width: 30px;
            height: 30px;
        }

        footer {
            background-color: #8e582c;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto; /* Hace que el footer se empuje al final de la página */
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

    <!-- Favoritos -->
    <div class="container favorites-container">
        <h2 class="text-center">Tus Productos Favoritos</h2>
        <?php if (isset($favoritos) && count($favoritos) > 0): ?>
            <?php foreach ($favoritos as $producto): ?>
                <div class="favorite-item">
                    <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del Producto">
                    <div>
                        <h5><?php echo $producto['nombre']; ?></h5>
                        <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                    </div>
                    <div>
                        <!-- Botón para agregar al carrito con ícono de carrito -->
                        <a href="agregar_carrito.php?producto_id=<?php echo $producto['id']; ?>" class="icon-btn">
                            <img src="img/carrito.png" alt="Agregar al carrito">
                        </a>
                        <!-- Botón para eliminar de favoritos con ícono de eliminar -->
                        <a href="eliminar_favorito.php?producto_id=<?php echo $producto['id']; ?>" class="icon-btn">
                            <img src="img/eliminar.png" alt="Eliminar de favoritos">
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No tienes productos en favoritos.</p>
        <?php endif; ?>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>Verchaze © 2024</p>
    </footer>

</body>
</html>
