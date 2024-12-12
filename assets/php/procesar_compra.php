<?php
session_start();

// Validar la compra, procesar el pago, guardar en la base de datos, etc.

// Después de procesar la compra
unset($_SESSION['carrito']); // Vaciar el carrito

echo "Compra procesada con éxito. ¡Gracias por tu compra!";
?>
