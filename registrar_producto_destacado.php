<?php
session_start();
include 'db_connection.php';

// Verificar si el empleado ha iniciado sesión
if (!isset($_SESSION['empleado_id'])) {
    header("Location: index.html");
    exit;
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];
    $ruta_imagen = 'img/' . basename($imagen);

    try {
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
            $sql = "INSERT INTO productos_destacados (nombre, descripcion, precio, imagen) VALUES (:nombre, :descripcion, :precio, :imagen)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion, ':precio' => $precio, ':imagen' => $ruta_imagen])) {
                header('Location: admin.php');  // Redirigir al panel de administración
                exit;
            } else {
                echo "Error al registrar el producto destacado.";
            }
        } else {
            echo "Error al subir la imagen. Asegúrate de que la carpeta 'img/' tiene permisos de escritura.";
        }
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto Destacado</title>
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
        <h2 class="text-center">Registrar Producto Destacado</h2>

        <form action="registrar_producto_destacado.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" name="precio" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" name="imagen" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Producto</button>
            <a href="admin.php" class="btn btn-secondary">Regresar</a> <!-- Botón para regresar -->
        </form>
    </div>

</body>
</html>
