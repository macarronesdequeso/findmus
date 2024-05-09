<?php
// Iniciar sesión
session_start();

// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "users";

try {
    // Establecer conexión a la base de datos utilizando PDO
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se ha enviado el formulario de registro
    if ($_POST) {
        // Recibe los datos del formulario
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];

        // Comprobar si el nombre de usuario ya existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM users_cred WHERE user = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $result_username = $stmt->fetchAll();

        // Comprobar si el correo electrónico ya existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM users_cred WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result_email = $stmt->fetchAll();

        // Si el nombre de usuario o el correo electrónico ya existen, redirige con un mensaje de error
        if (count($result_username) > 0) {
            header("Location: ../register?error=El nombre de usuario ya está en uso");
            exit();
        } elseif (count($result_email) > 0) {
            header("Location: ../register?error=El correo electrónico ya está en uso");
            exit();
        }

        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO users_cred (user, pass, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            // Redirige al usuario a la página de inicio de sesión después del registro exitoso
            header("Location: ../login?success=Registro exitoso. Por favor, inicia sesión");
            exit();
        } else {
            // Si hay un error al ejecutar la consulta, redirige con un mensaje de error
            header("Location: ../register?error=Error al intentar registrar el usuario");
            exit();
        }
    } else {
        // Si no se envió el formulario, redirige con un mensaje de error
        header("Location: ../register?error=ERR: Algo salió mal");
        exit();
    }
} catch(PDOException $e) {
    // Si ocurre un error en la conexión a la base de datos, redirige con un mensaje de error
    header("Location: ../register?error=Error de conexión a la base de datos");
    exit();
}
?>