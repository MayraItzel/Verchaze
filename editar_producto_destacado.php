<?php
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

// Verificar si se recibe el ID del producto a editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto para mostrarlos en el formulario
    $sql = "SELECT * FROM productos_destacados WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Producto no encontrado";
        exit;
    }
} else {
    header('Location: admin.php');
    exit;
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $producto['imagen'];  // Mantener la imagen original por defecto

    // Si se sube una nueva imagen, reemplazar la imagen anterior
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        $ruta_imagen = 'img/' . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
        $imagen = $ruta_imagen;
    }

    // Actualizar los datos del producto en la base de datos
    $sql = "UPDATE productos_destacados SET nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion, ':precio' => $precio, ':imagen' => $imagen, ':id' => $id])) {
        header('Location: admin.php');  // Redirigir al panel de administración después de editar
        exit;
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto Destacado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5e9da;
            margin-top: 50px;
        }

        .container {
            margin-top: 50px;
        }

        .btn-primary {
            background-color: #8c6239;
            border-color: #8c6239;
        }

        .btn-primary:hover {
            background-color: #6d482c;
            border-color: #6d482c;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">Editar Producto Destacado</h2>

        <form action="editar_producto_destacado.php?id=<?php echo $producto['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $producto['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" required><?php echo $producto['descripcion']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" name="precio" class="form-control" step="0.01" value="<?php echo $producto['precio']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" name="imagen" class="form-control">
                <p>Imagen actual: <img src="<?php echo $producto['imagen']; ?>" width="50"></p>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="admin.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>

</body>
</html>
