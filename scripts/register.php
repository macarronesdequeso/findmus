<?php
// Iniciar sesión
session_start();

// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "users";

// Verifica si se ha enviado el formulario de registro
if ($_POST) {
    // Recibe los datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Intentar la conexión a la base de datos utilizando MySQLi Object-Oriented
    $conn = new mysqli($host, $username_db, $password_db, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        // Si hay un error en la conexión, redirige con un mensaje de error
        header("Location: ../register.php?error=Error de conexión a la base de datos");
        exit();
    }

    // Comprobar si el nombre de usuario ya existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM users_cred WHERE user = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_username = $stmt->get_result();

    // Comprobar si el correo electrónico ya existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM users_cred WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_email = $stmt->get_result();

    // Si el nombre de usuario o el correo electrónico ya existen, redirige con un mensaje de error
    if ($result_username->num_rows > 0) {
        header("Location: ../register.php?error=El nombre de usuario ya está en uso");
        exit();
    } elseif ($result_email->num_rows > 0) {
        header("Location: ../register.php?error=El correo electrónico ya está en uso");
        exit();
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO users_cred (user, pass, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        // Redirige al usuario a la página de inicio de sesión después del registro exitoso
        header("Location: ../login.php?success=Registro exitoso. Por favor, inicia sesión");
        exit();
    } else {
        // Si hay un error al ejecutar la consulta, redirige con un mensaje de error
        header("Location: ../register.php?error=Error al intentar registrar el usuario");
        exit();
    }
} else {
    // Si no se envió el formulario, redirige con un mensaje de error
    header("Location: ../register.php?error=ERR: Something Went Wrong");
    exit();
}
?>
