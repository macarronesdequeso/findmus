<?php
require_once 'cred.php';

// Conexión a la base de datos
$host = $DBhost;
$username_db = $DBusername;
$password_db = $DBpassword;
$database = "users";

try {
    // Establecer conexión a la base de datos utilizando PDO
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los datos del formulario
    $email = $_POST["email"];
    $clave = $_POST["clave"];
    $new_password = $_POST["password"];

    // Usar la clave encriptada en la consulta SQL
    $stmt = $conn->prepare("SELECT * FROM users_cred WHERE email = :email AND `key` = :clave");
    $stmt->bindParam(":email", hash('sha256', $email));
    $stmt->bindParam(":clave", $clave);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // Si se encuentra una coincidencia, actualizar la contraseña
    if (count($result) > 0) {
        // Hash de la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Actualizar la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE users_cred SET pass = :password WHERE email = :email AND `key` = :clave");
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":email", hash('sha256', $email));
        $stmt->bindParam(":clave", $clave);
        $stmt->execute();

        // Redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
        header("Location: /login?success=Contraseña reestablecida con éxito. Por favor, inicia sesión.");
        exit();
    } else {
        // Si no se encuentra una coincidencia, redirigir con un mensaje de error
        header("Location: /recover?error=Correo electrónico o clave de seguridad incorrectos");
        exit();
    }
} catch(PDOException $e) {
    // Si ocurre un error en la conexión a la base de datos, redirigir con un mensaje de error
    header("Location: /recover?error=Error de conexión a la base de datos");
    exit();
}
?>