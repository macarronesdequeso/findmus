<?php
// Iniciar sesión
session_start();

// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "users";

// Intentar la conexión a la base de datos utilizando MySQLi Object-Oriented
$conn = mysqli_connect($host, $username_db, $password_db, $database);

// Verificar la conexión
if ($conn->connect_error) {
    // Si hay un error en la conexión, redirige con un mensaje de error
    header("Location: ../song.php?error=Error de conexión a la base de datos");
    exit();
}

// Verifica si se ha enviado el formulario
if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT user, pass FROM users_cred WHERE user = ? AND pass = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Comprueba si se encontraron resultados
        if ($result->num_rows > 0) {
            // Si el inicio de sesión es exitoso, redirige al usuario a la página principal
            header("Location: ../song.php");
            exit(); // Detener la ejecución adicional
        } else {
            // Si las credenciales son incorrectas, redirige con un mensaje de error
            header("Location: ../song.php?error=Usuario o contraseña incorrectos");
            exit(); // Detener la ejecución adicional
        }s
    } else {
        // Si hay un error al ejecutar la consulta, redirige con un mensaje de error
        header("Location: ../song.php?error=Error al intentar iniciar sesión");
        exit(); // Detener la ejecución adicional
    }
} else {
    // Si no se envió el formulario, redirige con un mensaje de error
    header("Location: ../song.php?error=ERR: Something Went Wrong");
    exit(); // Detener la ejecución adicional
}
?>
