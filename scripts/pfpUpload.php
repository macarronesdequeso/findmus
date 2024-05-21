<?php
session_start();

// Verificar si la sesión no está iniciada y, si no, redirigir a login.php
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

// Obtener la ID de usuario del atributo data-user-id del body
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '-1';

// Directorio donde se guardarán las imágenes de perfil
$targetDir = "/users/";

// Verificar si se envió un archivo
if (isset($_FILES["profilePicture"])) {
    $file = $_FILES["profilePicture"];
    
    // Obtener la extensión del archivo
    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    
    // Nombre de archivo único basado en la ID de usuario
    $fileName = $user_id . "." . "png";

    // Ruta completa de destino para guardar el archivo
    $targetFilePath = $targetDir . $fileName;

    // Intentar mover el archivo al directorio de destino
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        echo "La imagen de perfil se ha guardado correctamente.";
    } else {
        echo "Error al guardar la imagen de perfil.";
    }
} else {
    echo "No se ha seleccionado ninguna imagen.";
}
?>
