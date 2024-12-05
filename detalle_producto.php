<?php
include 'db_connection.php';  // Incluir la conexión a la base de datos

$producto_id = $_GET['producto_id'];
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :producto_id");
$stmt->execute(['producto_id' => $producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado.";
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Detalles del Producto - <?php echo $producto['nombre']; ?></title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Helvetica', sans-serif;
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

        .product-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 50px;
        }

        .product-image {
            max-width: 50%;
            position: relative;
            cursor: zoom-in;
        }

        /* Estilo de zoom */
        .product-image img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease-in-out;
        }

        .product-image:hover img {
            transform: scale(2); /* Aumenta el zoom al pasar el ratón por encima */
        }

        .product-info {
            max-width: 40%;
        }

        .product-info h1 {
            color: #8e582c;
        }

        .product-info p {
            font-size: 1.2rem;
            line-height: 1.5;
        }

        .price {
            font-size: 2rem;
            color: #aa7444;
            font-weight: bold;
        }

        .stock {
            font-size: 1rem;
            color: #704214;
        }

        .color {
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .btn-icon {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .btn-icon img {
            width: 50px;
            height: 50px;
        }

        .details-header {
            font-size: 1.5rem;
            color: #704214;
            margin-bottom: 20px;
        }

        .details-body {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        footer {
            margin-top: 50px;
            padding: 20px;
            background-color: #8e582c;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="Principal.php">
                <img src="img/logo.jpg" alt="Verchaze Logo"> Verchaze
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="Principal.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ver_favoritos.php">Favoritos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vercarrito.php">Carrito de compra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row product-details">
            <div class="col-md-6 product-image">
                <img src="<?php echo $producto['imagen']; ?>" class="img-fluid" alt="<?php echo $producto['nombre']; ?>">
            </div>
            <div class="col-md-6 product-info">
                <h1><?php echo $producto['nombre']; ?></h1>
                <p><?php echo $producto['descripcion']; ?></p>
                <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
                <p class="stock">Stock: <?php echo $producto['stock']; ?> unidades</p>
                <p class="color">Color: <?php echo $producto['color']; ?></p>

                <!-- Botón de agregar al carrito -->
                <a href="agregar_carrito.php?producto_id=<?php echo $producto['id']; ?>" class="btn-icon">
                    <img src="img/carrito.png" alt="Agregar al carrito">
                </a>

                <!-- Botón de agregar a favoritos -->
                <a href="agregar_favoritos.php?producto_id=<?php echo $producto['id']; ?>" class="btn-icon">
                    <img src="img/corazon.png" alt="Agregar a Favoritos">
                </a>
            </div>
        </div>

        <div class="details-section mt-5">
            <h2 class="details-header">Detalles del Producto</h2>
            <div class="details-body">
                <p><?php echo $producto['descripcion_detallada'] ?? 'Este producto está hecho con materiales de alta calidad y un diseño único para garantizar durabilidad y estilo.'; ?></p>
            </div>
        </div>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>Verchaze © 2024</p>
    </footer>

</body>
</html>

<?php
$pdo = null;  // Cerrar la conexión
?>
