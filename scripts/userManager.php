<?php
// Verificar si la sesión no está iniciada y, si no, iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "users";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Buscar el id y isAdmin del usuario actual
        $sql = "SELECT id, isAdmin FROM users_cred WHERE user = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $isAdmin = $row['isAdmin']; // Guardar el estado de isAdmin en una variable

            // Buscar los datos del usuario en users_data
            $sql = "SELECT firstName, lastName, dateBirth, country FROM users_data WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $user_data = null; // No hay datos para este usuario
            }
        } else {
            // Si el usuario no se encuentra, establecer isAdmin en 0
            $isAdmin = 0;
            $user_data = null;
        }
    } else {
        // Si la sesión no está iniciada, establecer isAdmin en 0
        $isAdmin = 0;
        $user_data = null;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
