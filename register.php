<?php
// Incluir el archivo de conexión a la base de datos
include 'db_connection.php';

try {
    // Comprobar si la solicitud es de tipo POST (cuando se envía el formulario)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Recoger y limpiar los datos enviados desde el formulario
        $nombre = trim($_POST['first_name']);
        $apellido = trim($_POST['last_name']);
        $correoElectronico = trim($_POST['email']);
        
        // Encriptar la contraseña para almacenarla de forma segura
        $contrasena = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

        // Validar que todos los campos obligatorios están llenos
        if (empty($nombre) || empty($apellido) || empty($correoElectronico) || empty($contrasena)) {
            echo "Por favor, completa todos los campos.";
            exit;
        }

        // Validar que el nombre no contenga números, solo letras y espacios
        if (!preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
            echo "El nombre no puede contener números.";
            exit;
        }

        // Validar que el apellido no contenga números
        if (!preg_match("/^[a-zA-Z\s]+$/", $apellido)) {
            echo "El apellido no puede contener números.";
            exit;
        }
        // Verificar si el correo electrónico ya está registrado en la base de datos
        $sql = "SELECT * FROM clientes WHERE correoElectronico = :correoElectronico";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':correoElectronico', $correoElectronico);
        $stmt->execute();  
        // Obtener los resultados de la consulta
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo "El correo electrónico ya está registrado. Por favor, utiliza otro.";
            exit;
        }
        // Insertar un nuevo cliente en la base de datos
        $sql = "INSERT INTO clientes (nombre, apellido, correoElectronico, contrasena) VALUES (:nombre, :apellido, 
        :correoElectronico, :contrasena)";
        $stmt = $pdo->prepare($sql);     
        // Asignar valores a los parámetros de la consulta
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':correoElectronico', $correoElectronico);
        $stmt->bindValue(':contrasena', $contrasena);
        // Ejecutar la consulta y confirmar si el registro fue exitoso
        if ($stmt->execute()) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            echo "Error al registrar el cliente. Verifica tu código y la base de datos.";
        }
    }
} catch (PDOException $e) {
    // Captura de errores en la conexión a la base de datos o ejecución de la consulta
    echo "Error en la conexión: " . $e->getMessage();
}
?>
