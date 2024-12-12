// Selecciona elementos de la página para manejar la apertura y cierre de los modales de registro e inicio de sesión
const registerModal = document.getElementById('register-modal');
const loginModal = document.getElementById('login-modal');
const registerBtn = document.getElementById('register-btn');
const loginBtn = document.getElementById('login-btn');
const closeRegister = document.getElementById('close-register');
const closeLogin = document.getElementById('close-login');
const switchToLogin = document.getElementById('login-switch');
const switchToRegister = document.getElementById('register-switch');

// Abre el modal de registro cuando se hace clic en el botón "Registrarse"
registerBtn.onclick = function () {
    registerModal.style.display = 'flex';
};

// Abre el modal de inicio de sesión cuando se hace clic en el botón "Iniciar Sesión"
loginBtn.onclick = function () {
    loginModal.style.display = 'flex';
};

// Cierra el modal de registro al hacer clic en la "X"
closeRegister.onclick = function () {
    registerModal.style.display = 'none';
};

// Cierra el modal de inicio de sesión al hacer clic en la "X"
closeLogin.onclick = function () {
    loginModal.style.display = 'none';
};

// Cambia del modal de registro al de inicio de sesión
switchToLogin.onclick = function () {
    registerModal.style.display = 'none';
    loginModal.style.display = 'flex';
};

// Cambia del modal de inicio de sesión al de registro
switchToRegister.onclick = function () {
    loginModal.style.display = 'none';
    registerModal.style.display = 'flex';
};

// Cierra los modales si el usuario hace clic fuera de ellos
window.onclick = function (event) {
    if (event.target === registerModal) {
        registerModal.style.display = 'none';
    }
    if (event.target === loginModal) {
        loginModal.style.display = 'none';
    }
};

// Función para enviar el formulario de registro usando JavaScript sin recargar la página
function registerUser(event) {
    event.preventDefault(); // Evita la recarga de la página al enviar el formulario

    const formData = new FormData(document.getElementById('register-form')); // Recoge los datos del formulario

    fetch('register.php', { // Envia los datos al servidor con fetch
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Convierte la respuesta en texto
    .then(data => {
        const messageDiv = document.getElementById('message'); // Div para mostrar el mensaje al usuario
        messageDiv.innerText = data;

        // Si el registro fue exitoso, muestra mensaje de agradecimiento y cierra el modal después de 3 segundos
        if (data.includes("Registro exitoso")) {
            messageDiv.innerText = "Gracias por registrarte con nosotros. Ahora puedes iniciar sesión.";
            setTimeout(() => {
                registerModal.style.display = 'none';
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('message').innerText = "Ocurrió un error al enviar el formulario.";
    });
}

// Función asincrónica para cargar productos destacados
async function loadProducts() {
    try {
        const response = await fetch('fetch_products.php'); // Hace una solicitud a un archivo PHP que devuelve los productos
        const productos = await response.json(); // Convierte la respuesta en JSON
        
        const productsContainer = document.getElementById('featured-products'); // Contenedor de los productos
        productsContainer.innerHTML = ''; // Limpia el contenido del contenedor antes de agregar nuevos productos

        // Si hay un error, muestra el mensaje de error en el contenedor
        if (productos.error) {
            productsContainer.innerHTML = `<p>${productos.error}</p>`;
        } else {
            // Crea y agrega cada producto al contenedor
            productos.forEach(product => {
                const productHTML = `
                    <div class="product">
                        <img src="${product.imagen}" alt="${product.nombre}">
                        <h5>${product.nombre}</h5>
                        <p class="price">$${product.precio}</p>
                        <p class="description">${product.descripcion || 'Descripción no disponible'}</p>
                    </div>
                `;
                productsContainer.innerHTML += productHTML;
            });
        }
    } catch (error) {
        document.getElementById('featured-products').innerHTML = '<p>Error al cargar productos.</p>';
    }
}

// Carga los productos automáticamente cuando la página termina de cargar
window.onload = loadProducts;
