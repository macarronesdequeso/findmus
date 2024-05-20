<?php
session_start();

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

        // Buscar la ruta de la imagen de perfil
        $sql = "SELECT id FROM users_cred WHERE user = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];

            // Construir la ruta de la imagen de perfil
            $profile_picture_path = "users/" . $user_id . ".png";

            echo "Usuario: " . $username . "<br>";

            // Verificar si el archivo de imagen existe en la ruta proporcionada
            if (file_exists($profile_picture_path)) {
                // Imprimir la etiqueta <img> con la ruta de la imagen de perfil
                echo "Foto encontrada";
            } else {
                echo "No se encontró la imagen de perfil.";
            }
        } else {
            echo "Usuario no encontrado.";
        }
    } else {
        echo "Sesión no iniciada.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>