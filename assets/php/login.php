<?php
session_start(); // Inicia la sesión para gestionar datos de usuario durante toda la sesión
include('db_connection.php'); // Incluye el archivo de conexión a la base de datos

// Verifica si el método de solicitud es POST, indicando un envío de formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correoElectronico = $_POST['email']; // Captura el correo electrónico ingresado
    $contrasena = $_POST['password'];     // Captura la contraseña ingresada

    try {
        // Busca el usuario en la tabla de empleados usando el correo electrónico
        $stmt = $pdo->prepare("SELECT * FROM empleados WHERE correoElectronico = :correoElectronico");
        $stmt->execute(['correoElectronico' => $correoElectronico]);
        $empleado = $stmt->fetch(); // Obtiene los datos del empleado si existe

        if ($empleado) {
            // Verifica que la contraseña ingresada coincida con la almacenada (hash)
            if (password_verify($contrasena, $empleado['contrasena'])) {
                // Almacena los datos del empleado en la sesión para futuras referencias
                $_SESSION['empleado_id'] = $empleado['id'];
                $_SESSION['empleado_nombre'] = $empleado['nombre']; // Guarda el nombre del empleado
                header("Location: admin.php"); // Redirige al panel de empleados
                exit; // Termina la ejecución para evitar el procesamiento adicional
            } else {
                echo "Credenciales inválidas para el empleado."; // Mensaje de error de credenciales
            }
        }

        // Si no se encontró en empleados, busca en la tabla de clientes
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE correoElectronico = :correoElectronico");
        $stmt->execute(['correoElectronico' => $correoElectronico]);
        $cliente = $stmt->fetch(); // Obtiene los datos del cliente si existe

        if ($cliente) {
            // Verifica que la contraseña ingresada coincida con la almacenada (hash) para el cliente
            if (password_verify($contrasena, $cliente['contrasena'])) {
                // Almacena los datos del cliente en la sesión para futuras referencias
                $_SESSION['cliente_id'] = $cliente['id'];
                $_SESSION['cliente_nombre'] = $cliente['nombre']; // Guarda el nombre del cliente
                header("Location: Principal.php"); // Redirige al panel de clientes
                exit; // Termina la ejecución para evitar el procesamiento adicional
            } else {
                echo "Credenciales inválidas para el cliente."; // Mensaje de error de credenciales
            }
        }

        // Si no se encontró ni en empleados ni en clientes, muestra un mensaje de error general
        echo "Credenciales inválidas. Por favor, verifica tu correo electrónico y contraseña.";
        
    } catch (PDOException $e) {
        // Muestra un mensaje de error en caso de fallo en la conexión o consulta a la base de datos
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
