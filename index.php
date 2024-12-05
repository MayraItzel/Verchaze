<?php
include 'db_connection.php';

// Consultar los productos destacados
$sql = "SELECT nombre, precio, descripcion, imagen FROM productos_destacados";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$productos_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verchaze - Registro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
    <style>
        /* Estilos generales */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #F8F0E3;
            color: #6E4F3A;
        }

        /* Estilos de la barra de navegación */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            background-color: #A67544;
            color: #FFF;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .navbar-brand h1 {
            font-size: 2.5em;
            margin: 0;
            color: #FFF;
            font-family: 'Playfair Display', serif;
        }

        .navbar nav a {
            color: #FFF;
            text-decoration: none;
            margin-left: 15px;
            padding: 8px 16px;
            background-color: #A67544;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .navbar nav a:hover {
            background-color: #8C5C3E;
            transform: translateY(-2px);
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

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #A67544;
            border-radius: 50%;
        }

        /* Sección Lo más destacado */
        .section-title {
            font-size: 2.5rem;
            color: #A67544;
            margin: 50px 0 20px 0;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #6E4F3A;
            text-align: center;
            margin-bottom: 30px;
        }

        .featured-products {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .product {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin: 15px;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .product:hover img {
            transform: scale(1.1);
        }

        .product h5 {
            margin-top: 15px;
            color: #A67544;
            font-size: 1.4rem;
        }

        .product p {
            color: #6E4F3A;
            font-size: 1.1rem;
        }

        /* Sección de ofertas */
        .offers {
            background-color: #A67544;
            color: white;
            padding: 50px 0;
            text-align: center;
            margin: 40px 0;
        }

        .offers p {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .offers a {
            background-color: white;
            color: #A67544;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .offers a:hover {
            background-color: #8C5C3E;
            color: white;
        }

        /* Sección de testimonios */
        .testimonials {
            text-align: center;
            margin: 50px 0;
        }

        .testimonial {
            background-color: #FFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
        }

        .testimonial p {
            font-family: 'Poppins', sans-serif;
            color: #6E4F3A;
            font-size: 1.1rem;
        }

        .testimonial strong {
            font-size: 1.2rem;
            color: #A67544;
        }

        /* Redes sociales */
        .social-icons {
            text-align: center;
            margin-top: 20px;
        }

        .social-icons a {
            color: #A67544;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #8C5C3E;
        }

        /* Estilos del pie de página */
        footer {
            background-color: #A67544;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9em;
            color: #FFF;
            border-top: 1px solid #DDD;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <header>
        <div class="navbar">
            <a class="navbar-brand" href="#">
                <img src="img/logo.jpg" alt="Verchaze Logo">
                <h1>Verchaze</h1>
            </a>
            <nav>
                <a href="#" id="register-btn"><i class="fas fa-user-plus"></i> Registrarse</a>
                <a href="#" id="login-btn"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
            </nav>
        </div>
    </header>
    
    <main>
        <!-- Carrusel -->
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
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

         <!-- Lo más destacado -->
    <h2 class="section-title">Lo Más Destacado</h2>
    <p class="section-subtitle">Nuestras bolsas representan lo mejor en estilo y elegancia. ¡Elige una pieza única y luce sofisticada en cualquier ocasión!</p>
    <div class="featured-products">
    <?php foreach ($productos_destacados as $producto): ?>
    <div class="product">
        <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
        <h5><?php echo $producto['nombre']; ?></h5>
        <p class="price">$<?php echo $producto['precio']; ?></p>
        <?php if (!empty($producto['descripcion'])): ?>
            <p class="description"><?php echo $producto['descripcion']; ?></p>
        <?php else: ?>
            <p class="description">Descripción no disponible.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
    </div>

        <!-- Sección de ofertas -->
        <section class="offers">
            <h2 class="section-title">Ofertas Especiales</h2>
            <p><strong>Hasta un 50% de descuento</strong> en nuestra colección de verano. ¡No te lo pierdas!</p>
            <a href="#">Ver Ofertas</a>
        </section>

        <!-- Sección de testimonios -->
        <section class="testimonials">
            <h2 class="section-title">Lo que dicen nuestros clientes</h2>
            <div class="testimonial">
                <p>"La calidad de estas bolsas es increíble. ¡Totalmente recomendable!"</p>
                <p><strong>- Laura G.</strong></p>
            </div>
            <div class="testimonial">
                <p>"El diseño es moderno y elegante, perfecto para cualquier ocasión."</p>
                <p><strong>- Javier M.</strong></p>
            </div>
        </section>

        <!-- Redes sociales -->
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        </div>
    </main>
    
  <!-- Modal para el registro -->
<div class="modal" id="register-modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" id="close-register"><i class="fas fa-times"></i></span>
        <h2>Registrarse</h2>
        <form id="register-form" onsubmit="registerUser(event)">
            <label for="first_name">Nombre:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Apellido:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <div class="terms" style="display: flex; align-items: center;">
                <input type="checkbox" id="terms" name="terms" required style="margin-right: 8px;">
                <label for="terms">Acepto los <a href="terminos.html" target="_blank">términos y condiciones</a></label>
            </div>
            
            <button type="submit"><i class="fas fa-user-plus"></i> Registrar</button>
        </form>
        <div class="registration-options">
            <p>¿Ya tienes una cuenta? <a href="#" id="login-switch">Iniciar sesion</a></p>
        </div>
        <div id="message"></div> <!-- Div para mostrar mensajes -->
    </div>
</div>



<!-- Modal para iniciar sesión -->
<div class="modal" id="login-modal">
    <div class="modal-content">
        <span class="close-btn" id="close-login"><i class="fas fa-times"></i></span>
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
        </form>
        <div class="registration-options">
            <p>¿No tienes una cuenta? <a href="#" id="register-switch">Registrarse</a></p>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Tienda de Bolsas. Todos los derechos reservados.</p>
</footer>

<script>

   

    const registerModal = document.getElementById('register-modal');
    const loginModal = document.getElementById('login-modal');
    const registerBtn = document.getElementById('register-btn');
    const loginBtn = document.getElementById('login-btn');
    const closeRegister = document.getElementById('close-register');
    const closeLogin = document.getElementById('close-login');
    const switchToLogin = document.getElementById('login-switch');
    const switchToRegister = document.getElementById('register-switch');

    // Abrir modal de registro
    registerBtn.onclick = function () {
        registerModal.style.display = 'flex';
    };

    // Abrir modal de inicio de sesión
    loginBtn.onclick = function () {
        loginModal.style.display = 'flex';
    };

    // Cerrar modal de registro
    closeRegister.onclick = function () {
        registerModal.style.display = 'none';
    };

    // Cerrar modal de inicio de sesión
    closeLogin.onclick = function () {
        loginModal.style.display = 'none';
    };

    // Cambiar de registro a inicio de sesión
    switchToLogin.onclick = function () {
        registerModal.style.display = 'none';
        loginModal.style.display = 'flex';
    };

    // Cambiar de inicio de sesión a registro
    switchToRegister.onclick = function () {
        loginModal.style.display = 'none';
        registerModal.style.display = 'flex';
    };

    // Cerrar modales si se hace clic fuera de ellos
    window.onclick = function (event) {
        if (event.target === registerModal) {
            registerModal.style.display = 'none';
        }
        if (event.target === loginModal) {
            loginModal.style.display = 'none';
        }
    };
    

    
function registerUser(event) {
    event.preventDefault(); // Evita la recarga de la página

    const formData = new FormData(document.getElementById('register-form'));

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Mostrar el mensaje en el div "message"
        document.getElementById('message').innerText = data;

        // Cerrar modal si el registro es exitoso
        if (data.includes("Registro exitoso")) {
            document.getElementById('register-modal').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('message').innerText = "Ocurrió un error al enviar el formulario.";
    });
}

function registerUser(event) {
    event.preventDefault(); // Evita la recarga de la página

    const formData = new FormData(document.getElementById('register-form'));

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        const messageDiv = document.getElementById('message');

        // Mostrar el mensaje en el div "message"
        messageDiv.innerText = data;

        // Si el registro es exitoso, mostrar mensaje de agradecimiento
        if (data.includes("Registro exitoso")) {
            messageDiv.innerText = "Gracias por registrarte con nosotros. Ahora puedes iniciar sesión.";
            
            // Cerrar el modal después de 3 segundos
            setTimeout(() => {
                document.getElementById('register-modal').style.display = 'none';
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('message').innerText = "Ocurrió un error al enviar el formulario.";
    });
}
</script>
</body>

</html>
