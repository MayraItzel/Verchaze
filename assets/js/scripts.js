// Abrir el modal de registro
document.getElementById("register-btn").onclick = function() {
    document.getElementById("register-modal").style.display = "block";
}

// Abrir el modal de inicio de sesión
document.getElementById("login-btn").onclick = function() {
    document.getElementById("login-modal").style.display = "block";
}

// Cerrar el modal de registro al hacer clic en la 'X'
document.getElementById("close-register").onclick = function() {
    document.getElementById("register-modal").style.display = "none";
}

// Cerrar el modal de inicio de sesión al hacer clic en la 'X'
document.getElementById("close-login").onclick = function() {
    document.getElementById("login-modal").style.display = "none";
}

// Cerrar el modal al hacer clic fuera de él
window.onclick = function(event) {
    if (event.target === document.getElementById("register-modal")) {
        document.getElementById("register-modal").style.display = "none";
    }
    if (event.target === document.getElementById("login-modal")) {
        document.getElementById("login-modal").style.display = "none";
    }
}

// Cerrar el modal de registro al presionar la tecla 'Escape'
document.onkeydown = function(event) {
    if (event.key === "Escape") {
        document.getElementById("register-modal").style.display = "none";
        document.getElementById("login-modal").style.display = "none";
    }
}


function addToCart(productId) {
    // Lógica para agregar al carrito
    alert('Producto añadido al carrito: ' + productId);
}

function addToFavorites(productId) {
    // Lógica para agregar a favoritos
    alert('Producto añadido a favoritos: ' + productId);
}


