<?php
include 'db_connection.php';  // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $color = $_POST['color'];  // Campo color del producto

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen']['name'];
    $ruta_imagen = 'img/' . basename($imagen);

    // Mover la imagen subida a la carpeta "img/"
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
        // Guardar la información del producto en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, color, imagen) VALUES (:nombre, :descripcion, :precio, :stock, :color, :imagen)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion, ':precio' => $precio, ':stock' => $stock, ':color' => $color, ':imagen' => $ruta_imagen])) {
            echo "Producto registrado exitosamente.";
            header("Location: admin.php");  // Redirigir a la página de administración después de registrar
            exit;
        } else {
            echo "Error al registrar el producto.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto - Verchaze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f0e3;
            font-family: 'Poppins', sans-serif;
            color: #6e4f3a;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #5b3a29;
            font-weight: bold;
        }

        .form-group label {
            color: #5b3a29;
            font-weight: 500;
        }

        .form-control {
            border-color: #a67544;
            background-color: #f9f1e6;
            color: #5b3a29;
        }

        .form-control:focus {
            border-color: #8c5c3e;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #a67544;
            border-color: #a67544;
        }

        .btn-primary:hover {
            background-color: #8c5c3e;
            border-color: #8c5c3e;
        }

        .btn-secondary {
            background-color: #6e4f3a;
            border-color: #6e4f3a;
        }

        .btn-secondary:hover {
            background-color: #5b3a29;
            border-color: #5b3a29;
        }

        .form-group.mt-4 {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Registrar Nuevo Producto</h2>
        <form action="registrar_producto.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>

            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>

            <div class="form-group">
                <label for="color">Color del Producto:</label>
                <input type="text" class="form-control" id="color" name="color" required>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen del Producto:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
            </div>

            <!-- Botones de registrar y regresar -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Registrar Producto</button>
                <a href="admin.php" class="btn btn-secondary">Regresar a Administración</a>
            </div>
        </form>
    </div>

</body>

</html>
