<?php
session_start();
include 'db_connection.php';

// Verificar si el cliente ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];

// Obtener productos del carrito para mostrarlos en la página de confirmación de compra
$sql = "SELECT productos.nombre, productos.precio, carrito.cantidad
        FROM carrito
        JOIN productos ON carrito.producto_id = productos.id
        WHERE carrito.cliente_id = :cliente_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cliente_id' => $cliente_id]);
$productos_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($productos_carrito)) {
    echo "<p>No tienes productos en tu carrito.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F5F5DC;
        }

        .container {
            margin-top: 50px;
        }

        h2, h3 {
            color: #8c6239;
        }

        .list-group-item {
            font-size: 1.2rem;
        }

        .input-error {
            color: red;
            font-size: 0.9em;
            display: none;
        }

        .total-pago {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
            color: #8c6239;
        }

        .btn-success {
            background-color: #8c6239;
            border-color: #8c6239;
        }

        .btn-success:hover {
            background-color: #704214;
            border-color: #704214;
        }

        /* Estilo adicional para los campos de tarjeta y OXXO */
        #tarjeta_section, #oxxo_section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #8c6239;
            color: white;
            margin-top: 50px;
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
                    <a class="nav-link" href="ver_mis_pedidos.php" style="color: white;">Mis Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color: white;">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center">Confirmar Compra</h2>

    <!-- Lista de productos del carrito -->
    <h3>Tus productos</h3>
    <ul class="list-group mb-4">
        <?php
        $total = 0;
        foreach ($productos_carrito as $producto) {
            $subtotal = $producto['precio'] * $producto['cantidad'];
            $total += $subtotal;
            echo "<li class='list-group-item'>{$producto['nombre']} - {$producto['cantidad']} unidades - $".number_format($subtotal, 2)."</li>";
        }
        ?>
    </ul>

    <!-- Formulario para completar la compra -->
    <form id="compraForm" action="procesar_pago.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" pattern="[a-zA-Z\s]+" required>
            <span class="input-error" id="nombreError">El nombre solo debe contener letras.</span>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección de Envío</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono de Contacto</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" maxlength="10" pattern="\d{10}" required>
            <span class="input-error" id="telefonoError">El teléfono debe contener exactamente 10 dígitos.</span>
        </div>

        <div class="mb-3">
            <label for="metodo_pago" class="form-label">Método de Pago</label>
            <select class="form-select" id="metodo_pago" name="metodo_pago" required onchange="mostrarOpcionesPago()">
                <option value="">Seleccionar...</option>
                <option value="credito">Tarjeta de Crédito</option>
                <option value="debito">Tarjeta de Débito</option>
                <option value="oxxo">Pago en OXXO</option>
                <option value="deposito">Depósito Bancario</option>
            </select>
        </div>

        <!-- Campos adicionales para tarjeta de crédito/débito -->
        <div id="tarjeta_section" class="mb-3" style="display:none;">
            <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
            <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" maxlength="16" pattern="\d{16}">
            <span class="input-error" id="tarjetaError">El número de tarjeta debe tener 16 dígitos.</span>

            <label for="fecha_expiracion" class="form-label">Fecha de Expiración (MM/AA)</label>
            <input type="text" class="form-control" id="fecha_expiracion" name="fecha_expiracion" maxlength="5" pattern="\d{2}/\d{2}" placeholder="MM/AA">

            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" maxlength="3" pattern="\d{3}">
        </div>

        <!-- Información adicional para OXXO -->
        <div id="oxxo_section" class="mb-3" style="display:none;">
            <h4>Referencia de Pago OXXO</h4>
            <p>Utiliza esta referencia para realizar tu pago en cualquier tienda OXXO.</p>
            <input type="text" class="form-control" value="<?php echo mt_rand(1000000000, 9999999999); ?>" readonly>
        </div>

        <h3 class="total-pago">Total a Pagar: $<?php echo number_format($total, 2); ?></h3>

        <button type="submit" class="btn btn-success btn-lg">Realizar Pago</button>
    </form>
</div>

<footer>
    <p>Verchaze &copy; 2024</p>
</footer>

<script>
    // Mostrar/ocultar campos de tarjeta de crédito/débito según el método de pago seleccionado
    function mostrarOpcionesPago() {
        var metodoPago = document.getElementById('metodo_pago').value;
        var tarjetaSection = document.getElementById('tarjeta_section');
        var oxxoSection = document.getElementById('oxxo_section');

        tarjetaSection.style.display = (metodoPago === 'credito' || metodoPago === 'debito') ? 'block' : 'none';
        oxxoSection.style.display = (metodoPago === 'oxxo') ? 'block' : 'none';
    }

    // Validación del formulario
    document.getElementById('compraForm').addEventListener('submit', function(event) {
        var telefono = document.getElementById('telefono');
        var nombre = document.getElementById('nombre');
        var numeroTarjeta = document.getElementById('numero_tarjeta');

        if (!telefono.value.match(/^\d{10}$/)) {
            document.getElementById('telefonoError').style.display = 'block';
            event.preventDefault();
        }

        if (!nombre.value.match(/^[a-zA-Z\s]+$/)) {
            document.getElementById('nombreError').style.display = 'block';
            event.preventDefault();
        }

        if ((document.getElementById('metodo_pago').value === 'credito' || document.getElementById('metodo_pago').value === 'debito') && !numeroTarjeta.value.match(/^\d{16}$/)) {
            document.getElementById('tarjetaError').style.display = 'block';
            event.preventDefault();
        }
    });

    // Esconder campos de tarjeta por defecto
    mostrarOpcionesPago();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
