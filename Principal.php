<?php
include 'db_connection.php';  

// Variables para la paginación
$productos_por_pagina = 8;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $productos_por_pagina;

// Si se ha enviado una búsqueda
$nombre_busqueda = isset($_GET['query']) ? $_GET['query'] : '';

// Consulta para obtener los productos con o sin búsqueda
if (!empty($nombre_busqueda)) {
    $sql = "SELECT id, nombre, descripcion, precio, stock, color, imagen FROM productos WHERE nombre LIKE :nombre OR color LIKE :nombre LIMIT :inicio, :productos_por_pagina";
    $stmt = $pdo->prepare($sql);
    $nombre_busqueda = '%' . $nombre_busqueda . '%'; // Añadir los comodines para la búsqueda
    $stmt->bindParam(':nombre', $nombre_busqueda, PDO::PARAM_STR);
} else {
    $sql = "SELECT id, nombre, descripcion, precio, stock, color, imagen FROM productos LIMIT :inicio, :productos_por_pagina";
    $stmt = $pdo->prepare($sql);
}

$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':productos_por_pagina', $productos_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de productos para paginación (considerando o no la búsqueda)
if (!empty($nombre_busqueda)) {
    $total_productos_sql = "SELECT COUNT(*) FROM productos WHERE nombre LIKE :nombre OR color LIKE :nombre";
    $total_stmt = $pdo->prepare($total_productos_sql);
    $total_stmt->bindParam(':nombre', $nombre_busqueda, PDO::PARAM_STR);
    $total_stmt->execute();
    $total_productos = $total_stmt->fetchColumn();
} else {
    $total_productos_sql = "SELECT COUNT(*) FROM productos";
    $total_productos = $pdo->query($total_productos_sql)->fetchColumn();
}
$total_paginas = ceil($total_productos / $productos_por_pagina);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verchaze</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Colores */
        :root {
            --color-primary: #8e582c;
            --color-secondary: #704214;
            --color-tertiary: #aa7444;
            --color-quaternary: #e6aa77;
            --color-light-primary: #b88b5c;
        }

        /* Estilos generales */
        body {
            background-color: #F5F5DC;
            color: var(--color-primary);
            margin: 0;
        }

        /* Barra de navegación */
        .navbar {
            background-color: var(--color-primary);
            color: #FFFFFF;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            padding: 20px;
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .navbar-brand,
        .navbar .nav-link {
            color: #FFFFFF;
        }

        .navbar .nav-link:hover {
            color: var(--color-light-primary);
        }

        .navbar .navbar-brand {
            font-size: 1.8em;
            font-weight: bold;
        }

        .navbar-brand img {
            width: 60px;
            height: 60px;
        }

        /* Carrusel */
        .custom-carousel-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .carousel-caption h5 {
            font-size: 2rem;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        }

        .carousel-caption p {
            font-size: 1.3rem;
            color: #ffffff;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        }

        /* Zoom en las imágenes al pasar el ratón */
        .card {
            border-radius: 15px;
            border: 1px solid #DCDCDC;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-body {
            flex-grow: 1;
        }

        .card-img-top {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
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

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
        }

        /* Botón Ver detalles */
        .btn-info {
            background-color: var(--color-secondary);
            border-color: var(--color-secondary);
            color: white;
        }

        .btn-info:hover {
            background-color: var(--color-tertiary);
            border-color: var(--color-tertiary);
        }

     /* Paginación */
.pagination {
    justify-content: center;
}

/* Paginación */
.pagination .page-link {
    color: #8c6239; /* Color café */
}

.pagination .page-link:hover {
    background-color: #8c6239; 
    color: white; 
}

.pagination .active .page-link {
    background-color: #8c6239; 
    border-color: #8c6239; 
    color: white; 
}



        /* Pie de página */
        footer {
            background-color: var(--color-secondary);
            color: #FFF;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: var(--color-quaternary);
            text-decoration: none;
        }

        footer a:hover {
            color: var(--color-light-primary);
        }

        .carousel-space {
            margin-bottom: 40px;
        }

        .space-top {
            margin-top: 150px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="Principal.php">
            <img src="img/logo.jpg" alt="Verchaze Logo"> Verchaze
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Formulario de búsqueda -->
        <form class="d-flex ms-auto" action="Principal.php" method="GET" role="search">
            <input class="form-control me-2" type="text" name="query" placeholder="Buscar por nombre o color" aria-label="Search">
            <button class="btn btn-outline-light" type="submit">Buscar</button>
        </form>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="ver_favoritos.php">Favoritos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ver_carrito.php">Carrito de compra</a>
            </li>
            <li class="nav-item">
    <a class="nav-link" href="ver_mis_pedidos.php">Mis Pedidos</a>
</li>

            <li class="nav-item">
                <a class="nav-link" href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>


    <!-- Carrusel -->
    <div id="carouselExampleDark" class="carousel carousel-dark slide space-top" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3"
                aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
                <img src="img/carrucel2 (1).webp" class="d-block custom-carousel-img" alt="Imagen 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Elegancia Redefinida</h5>
                    <p>Descubre nuestras bolsas más exclusivas.</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img/carrucel2 (2).webp" class="d-block custom-carousel-img" alt="Imagen 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Estilo Moderno</h5>
                    <p>Hasta un 50% de descuento en nuestra nueva colección.</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img/carrucel2 (3).webp" class="d-block custom-carousel-img" alt="Imagen 3">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Calidad Premium</h5>
                    <p>Elige lo mejor para ti y luce con distinción.</p>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="img/carrucel2 (4).webp" class="d-block custom-carousel-img" alt="Imagen 4">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Diseño Atemporal</h5>
                    <p>La bolsa perfecta para cualquier ocasión.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Espacio adicional entre el carrusel y las tarjetas -->
    <div class="carousel-space"></div>

    <!-- Sección de productos dinámicos -->
    <div class="container space-bottom">
        <div class="row">
            <?php if (isset($productos) && count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="col-3 mb-4">
                        <div class="card">
                            <img src="<?php echo $producto['imagen']; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                                <p class="card-text"><?php echo $producto['descripcion']; ?></p>
                                <p>Precio: $<?php echo $producto['precio']; ?></p>
                                <p>Stock: <?php echo $producto['stock']; ?> unidades</p>
                                <p>Color: <?php echo $producto['color']; ?></p>
                            </div>
                            <div class="card-footer">
                                <!-- Botón para ver detalles del producto -->
                                <a href="detalle_producto.php?producto_id=<?php echo $producto['id']; ?>" class="btn btn-info">Ver detalles</a>
                                <!-- Iconos de carrito y favoritos -->
                                <a href="agregar_carrito.php?producto_id=<?php echo $producto['id']; ?>" class="icon-btn">
                            <img src="img/carrito.png" alt="Agregar al carrito">
                        </a>
                                
                                <a href="agregar_favoritos.php?producto_id=<?php echo $producto['id']; ?>" class="icon-btn">
                                    <img src="img/corazon.png" alt="Agregar a favoritos">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Paginación -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($pagina_actual > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($pagina_actual < $total_paginas): ?>
                <li class="page-item">
                    <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Pie de página -->
    <footer>
        <div class="text-center p-3">
            © 2024 Copyright:
            <a href="#">Verchaze.com</a>
        </div>
    </footer>

</body>

</html>

<?php
$pdo = null;  // Cerrar la conexión
?>
