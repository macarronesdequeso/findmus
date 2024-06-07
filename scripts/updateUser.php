<?php
// Verificar si la sesión no está iniciada y, si no, iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'cred.php';

// Conexión a la base de datos
$host = $DBhost;
$username_db = $DBusername;
$password_db = $DBpassword;
$database = "users";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['id']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['dateBirth']) && isset($_POST['country'])) {
        $id = $_POST['id'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $dateBirth = $_POST['dateBirth'];
        $country = $_POST['country'];

        // Actualizar los datos del usuario
        $sql = "UPDATE users_data SET firstName = :firstName, lastName = :lastName, dateBirth = :dateBirth, country = :country WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':dateBirth', $dateBirth);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirigir de vuelta a la página principal después de la actualización
        header("Location: /preferences?success=Datos actualizados con éxito.");
        exit();
    }
} catch (PDOException $e) {
    header("Location: /preferences?error=Error de conexión a la base de datos");
}
?>
