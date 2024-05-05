<?php
// Iniciar sesión
session_start();

// Define el número máximo de intentos permitidos
$max_attempts = 8;

// Define la duración del cooldown en segundos (3 minutos = 180 segundos)
$cooldown_duration = 180;

// Verifica si ya hay un contador de intentos en la sesión
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Verifica si se ha excedido el cooldown
if (isset($_SESSION['cooldown_timestamp']) && time() - $_SESSION['cooldown_timestamp'] < $cooldown_duration) {
    // Si aún está en el cooldown, redirige con un mensaje de error
    header("Location: ../login.php?error=Por favor, espera unos minutos antes de intentar de nuevo");
    exit(); // Detener la ejecución adicional
}

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
    header("Location: ../login.php?error=Error de conexión a la base de datos");
    exit();
}

// Verifica si se ha enviado el formulario
if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta SQL para obtener el usuario con el mismo nombre
    $sql = "SELECT user, pass FROM users_cred WHERE user = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Comprueba si se encontraron resultados
        if ($result->num_rows > 0) {
            // Obtener la contraseña cifrada de la base de datos
            $row = $result->fetch_assoc();
            $stored_password = $row['pass'];

            // Verificar si la contraseña proporcionada coincide con la contraseña cifrada almacenada
            if (password_verify($password, $stored_password)) {
                // Si el inicio de sesión es exitoso, restablece el contador de intentos
                $_SESSION['login_attempts'] = 0;
                // Redirige al usuario a la página principal
                header("Location: ../index.html");
                exit(); // Detener la ejecución adicional
            } else {
                // Incrementa el contador de intentos
                $_SESSION['login_attempts']++;
                
                // Verifica si se han excedido los intentos permitidos
                if ($_SESSION['login_attempts'] > $max_attempts) {
                    // Establece la marca de tiempo para el cooldown
                    $_SESSION['cooldown_timestamp'] = time();
                    // Redirige con un mensaje de error
                    header("Location: ../login.php?error=Has excedido el número máximo de intentos. Por favor, espera unos minutos antes de intentar de nuevo");
                    exit(); // Detener la ejecución adicional
                } else {
                    // Redirige con un mensaje de error de usuario o contraseña incorrectos
                    header("Location: ../login.php?error=Usuario o contraseña incorrectos");
                    exit(); // Detener la ejecución adicional
                }
            }
        } else {
            // Si no se encontró el nombre de usuario en la base de datos, redirige con un mensaje de error
            header("Location: ../login.php?error=Usuario o contraseña incorrectos");
            exit(); // Detener la ejecución adicional
        }
    } else {
        // Si hay un error al ejecutar la consulta, redirige con un mensaje de error
        header("Location: ../login.php?error=Error al intentar iniciar sesión");
        exit(); // Detener la ejecución adicional
    }
} else {
    // Si no se envió el formulario, redirige con un mensaje de error
    header("Location: ../login.php?error=ERR: Something Went Wrong");
    exit(); // Detener la ejecución adicional
}
?>
