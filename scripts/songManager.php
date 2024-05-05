<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "music";

// Obtener el ID de la canción desde la URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Si el ID es menor que 0, establecerlo como 0
    if ($id < 0) {
        $id = 0;
    }

    // Intentar la conexión a la base de datos utilizando PDO
    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para obtener los detalles de la canción con el ID proporcionado
        $sql = "SELECT * FROM songs WHERE id = :id";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        // Bind de parámetros
        $stmt->bindParam(':id', $id);
        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados de la consulta
        $song = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró la canción
        if($song) {
            // Mostrar los detalles de la canción
            echo "ID: " . $song['id'] . "<br>";
            echo "Nombre: " . $song['name'] . "<br>";
            echo "Fecha de Creación: " . $song['dateCreation'] . "<br>";
            echo "Compositor: " . $song['composer'] . "<br>";
            echo "Vistas: " . $song['views'] . "<br>";
            // Puedes mostrar más detalles si la tabla tiene más columnas
        } else {
            echo "No se encontró ninguna canción con ese ID.";
        }
    } catch(PDOException $e) {
        // Manejar el error de conexión o consulta
        echo "Error: " . $e->getMessage();
    }
} else {
    // Si no se proporcionó un ID válido en la URL
    echo "Por favor, proporciona un ID de canción válido en la URL.";
}

// Obtener el ID de la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id !== null) {
    // Directorio base donde se encuentran las canciones y las imágenes de los álbumes
    $base_dir = "../songs/" . $id . "/";

    // Ruta de la canción (asumiendo que se llama {id}.mp3)
    $song_path = $base_dir . $id . ".mp3";

    // Ruta de la imagen del álbum (asumiendo que se llama {id}.jpg)
    $image_path = $base_dir . $id . ".jpg";

    // Comprobar si los archivos existen
    if (file_exists($song_path) && file_exists($image_path)) {
        // Realizar acciones con los archivos (por ejemplo, descargarlos o mostrarlos en las página)
        // Aquí puedes implementar la lógica para enviar los archivos al usuario o utilizarlos de alguna otra manera
        // Por ejemplo:
        // header("Content-Type: audio/mpeg");
        // readfile($song_path);
        // header("Content-Type: image/jpeg");
        // readfile($image_path);
        echo "Song: $song_path, Image: $image_path";
    } else {
        echo "Los archivos no existen";
    }
} else {
    echo "No se proporcionó un ID en la URL";
}
?>