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
    header("Location: ../login?error=Por favor, espera unos minutos antes de intentar de nuevo");
    exit(); // Detener la ejecución adicional
}

// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "users";

// Intentar la conexión a la base de datos utilizando PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica si se ha enviado el formulario
    if ($_POST) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Consulta SQL para obtener el usuario con el mismo nombre
        $sql = "SELECT user, pass FROM users_cred WHERE user = :username";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);

        // Ejecutar la consulta
        $stmt->execute();

        // Comprueba si se encontraron resultados
        if ($stmt->rowCount() > 0) {
            // Obtener la contraseña cifrada de la base de datos
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['pass'];

            // Verificar si la contraseña proporcionada coincide con la contraseña cifrada almacenada
            if (password_verify($password, $stored_password)) {
                // Si el inicio de sesión es exitoso, restablece el contador de intentos
                $_SESSION['login_attempts'] = 0;
                // Guarda el nombre de usuario en la sesión
                $_SESSION['username'] = $username;
                // Redirige al usuario a la página de inicio
                sleep(1);
                header("Location: /");
                exit(); // Detener la ejecución adicional
            } else {
                // Incrementa el contador de intentos
                $_SESSION['login_attempts']++;

                // Verifica si se han excedido los intentos permitidos
                if ($_SESSION['login_attempts'] >= $max_attempts) {
                    // Establece la marca de tiempo para el cooldown
                    $_SESSION['cooldown_timestamp'] = time();
                    // Redirige con un mensaje de error
                    header("Location: ../login?error=Has excedido el número máximo de intentos. Por favor, espera unos minutos antes de intentar de nuevo");
                    exit(); // Detener la ejecución adicional
                } else {
                    // Redirige con un mensaje de error de usuario o contraseña incorrectos
                    header("Location: ../login?error=Usuario o contraseña incorrectos");
                    exit(); // Detener la ejecución adicional
                }
            }
        } else {
            // Incrementa el contador de intentos
            $_SESSION['login_attempts']++;
            // Verifica si se han excedido los intentos permitidos
            if ($_SESSION['login_attempts'] >= $max_attempts) {
                // Establece la marca de tiempo para el cooldown
                $_SESSION['cooldown_timestamp'] = time();
                // Redirige con un mensaje de error
                header("Location: ../login?error=Has excedido el número máximo de intentos. Por favor, espera unos minutos antes de intentar de nuevo");
                exit(); // Detener la ejecución adicional
            } else {
                // Si no se encontró el nombre de usuario en la base de datos, redirige con un mensaje de error
                header("Location: ../login?error=Usuario o contraseña incorrectos");
                exit(); // Detener la ejecución adicional
            }
        }
        
    } else {
        // Si no se envió el formulario, redirige con un mensaje de error
        header("Location: ../login?error=ERR: Algo salió mal");
        exit(); // Detener la ejecución adicional
    }
} catch(PDOException $e) {
    // Si ocurre un error en la conexión a la base de datos, redirige con un mensaje de error
    header("Location: ../login?error=Error de conexión a la base de datos");
    exit();
}
?>