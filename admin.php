
<?php
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

// Obtener el término de búsqueda del usuario (si está presente)
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Consulta para obtener productos (con o sin búsqueda)
try {
    if (!empty($searchQuery)) {
        // Si hay una búsqueda, filtrar productos por nombre o descripción
        $sql = "SELECT id, nombre, descripcion, precio, stock, color, imagen 
                FROM productos 
                WHERE nombre LIKE :searchQuery OR descripcion LIKE :searchQuery";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':searchQuery' => '%' . $searchQuery . '%']);
    } else {
        // Si no hay búsqueda, obtener todos los productos
        $sql = "SELECT id, nombre, descripcion, precio, stock, color, imagen FROM productos";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error en la consulta de productos: " . $e->getMessage();
}

// Obtener productos destacados
try {
    $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos_destacados";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $productos_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error en la consulta de productos destacados: " . $e->getMessage();
}

// Eliminar un producto destacado
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM productos_destacados WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header('Location: admin.php');
    exit;
}

// Obtener empleado del mes
$empleado_mes = '';
$mensaje_empleado_mes = '';
if (file_exists('empleado_mes.txt')) {
    $empleado_mes = file_get_contents('empleado_mes.txt');
}
if (file_exists('mensaje_empleado_mes.txt')) {
    $mensaje_empleado_mes = file_get_contents('mensaje_empleado_mes.txt');
}

// Guardar empleado del mes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_empleado_mes'])) {
    $empleado_mes = $_FILES['foto_empleado']['name'];
    $ruta_empleado = 'img/' . basename($empleado_mes);
    $mensaje_empleado_mes = $_POST['mensaje_empleado'];

    if (move_uploaded_file($_FILES['foto_empleado']['tmp_name'], $ruta_empleado)) {
        file_put_contents('empleado_mes.txt', $ruta_empleado);
        file_put_contents('mensaje_empleado_mes.txt', $mensaje_empleado_mes);
        header('Location: admin.php');
        exit;
    } else {
        echo "Error al subir la imagen del empleado.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Verchaze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5e9da;
        }

        .container {
            margin-top: 80px; /* Ajustar para compensar la barra fija */
        }

        .navbar {
            background-color: #5B3A29;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: #7B5B38;
            color: white;
        }

        /* Estilos para los íconos */
        .icon-btn {
            width: 30px;
            height: 30px;
        }

        .empleado-mes {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #f0e4d7;
            border-radius: 10px;
            text-align: center;
            font-size: 0.9em;
        }

        .empleado-mes img {
            max-width: 80px;
            border-radius: 50%;
        }

        .btn-primary {
            background-color: #8c6239;
            border-color: #8c6239;
        }

        .btn-primary:hover {
            background-color: #6d482c;
            border-color: #6d482c;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .mt-5 {
            margin-top: 1.5rem;
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
                    <a class="nav-link" href="#productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#productos-destacados">Productos Destacados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#empleado-mes">Empleado del Mes</a>
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
            <form class="d-flex ms-3" method="GET" action="admin.php" role="search">
                <input class="form-control me-2" type="text" name="query" placeholder="Buscar producto" aria-label="Buscar">
                <button class="btn btn-outline-light" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>

    <div class="container">
        <!-- Gestión de Productos -->
        <section id="productos">
            <h2 class="text-center mt-5">Gestión de Productos</h2>
            <a href="registrar_producto.php" class="btn btn-primary mb-4">Agregar Producto</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Color</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td>$<?php echo $producto['precio']; ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><?php echo $producto['color']; ?></td>
                        <td><img src="<?php echo $producto['imagen']; ?>" width="50"></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $producto['id']; ?>"><img src="img/modificar.png" class="icon-btn" alt="Editar"></a>
                            <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')"><img src="img/eliminar.png" class="icon-btn" alt="Eliminar"></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Gestión de Productos Destacados -->
        <!-- En la sección de Productos Destacados -->
<section id="productos-destacados">
    <h2 class="text-center mt-5">Productos Destacados</h2>
    <a href="registrar_producto_destacado.php" class="btn btn-primary mb-4">Agregar Producto Destacado</a> <!-- Nuevo botón -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos_destacados as $producto): ?>
            <tr>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['descripcion']; ?></td>
                <td>$<?php echo $producto['precio']; ?></td>
                <td><img src="<?php echo $producto['imagen']; ?>" width="50"></td>
                <td>
                    <a href="editar_producto_destacado.php?id=<?php echo $producto['id']; ?>"><img src="img/modificar.png" class="icon-btn" alt="Editar"></a>
                    <a href="admin.php?delete=<?php echo $producto['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')"><img src="img/eliminar.png" class="icon-btn" alt="Eliminar"></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

        <!-- Empleado del Mes -->
        <section id="empleado-mes" class="empleado-mes">
            <h2 class="text-center mt-5">Empleado del Mes</h2>
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="foto_empleado" class="form-label">Foto:</label>
                    <input type="file" name="foto_empleado" id="foto_empleado" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje_empleado" class="form-label">Mensaje:</label>
                    <textarea name="mensaje_empleado" id="mensaje_empleado" rows="2" class="form-control" required></textarea>
                </div>
                <button type="submit" name="guardar_empleado_mes" class="btn btn-primary">Guardar Empleado del Mes</button>
            </form>

            <?php if (!empty($empleado_mes) && !empty($mensaje_empleado_mes)): ?>
                <h4 class="mt-4">Empleado del Mes Actual</h4>
                <img src="<?php echo $empleado_mes; ?>" alt="Empleado del Mes" class="img-thumbnail">
                <p class="mt-2"><?php echo htmlspecialchars($mensaje_empleado_mes); ?></p>
            <?php endif; ?>
        </section>
    </div>

    <script>
        function mostrarFormulario() {
            document.getElementById('formulario-destacados').style.display = 'block';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$pdo = null; // Cerrar la conexión
?>
