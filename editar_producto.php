<?php
include 'db_connection.php';  // Incluir la conexión a la base de datos

// Verificar si se ha proporcionado el ID del producto
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos actuales del producto
    $sql = "SELECT * FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        // Si no se encuentra el producto, redirigir a admin.php
        header("Location: admin.php");
        exit;
    }
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $color = $_POST['color'];  // Campo color del producto

    // Actualizar los datos del producto en la base de datos
    $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, color = :color WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion, ':precio' => $precio, ':stock' => $stock, ':color' => $color, ':id' => $id])) {
        echo "Producto actualizado correctamente.";
        header("Location: admin.php");  // Redirigir a admin.php después de actualizar
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
    <title>Editar Producto - Verchaze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .btn-primary {
            background-color: #5B3A29;
            border-color: #5B3A29;
        }

        .btn-primary:hover {
            background-color: #7B5B38;
            border-color: #7B5B38;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Editar Producto</h2>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo $producto['precio']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="color">Color del Producto:</label>
                <input type="text" class="form-control" id="color" name="color" value="<?php echo $producto['color']; ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <a href="admin.php" class="btn btn-secondary">Volver a Administración</a>
            </div>
        </form>
    </div>

</body>

</html>
