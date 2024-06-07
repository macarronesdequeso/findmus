<?php
require_once 'cred.php';

// Conexión a la base de datos
$host = $DBhost;
$username_db = $DBusername;
$password_db = $DBpassword;
$database = "music";

// Obtener el ID de la canción desde la URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Si el ID es menor que -1, establecerlo como -1
    if ($id < -1) {
        $id = -1;
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
            // Obtener las rutas de los archivos asociados a la canción
            $base_dir = "songs/" . $id . "/";
            $song_path = $base_dir . "song.mp3";
            $image_path = $base_dir . "cover.jpg";

            // Incrementar el contador de vistas de la canción
            $views = $song['views'] + 1;

            // Consulta SQL para actualizar el número de vistas en la base de datos
            $update_sql = "UPDATE songs SET views = :views WHERE id = :id";

            // Preparar la consulta de actualización
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bindParam(':views', $views);
            $update_stmt->bindParam(':id', $id);

            // Ejecutar la consulta de actualización
            $update_stmt->execute();

            // Verificar si los archivos existen
            if (file_exists($song_path) && file_exists($image_path)) {
                // Devolver los valores de la canción y las rutas de los archivos
                return [
                    'song' => $song,
                    'song_path' => $song_path,
                    'image_path' => $image_path
                ];
            } else {
                // Los archivos no existen
                return null;
            }
        } else {
            // No se encontró la canción
            return null;
        }

    } catch(PDOException $e) {
        // Manejar el error de conexión o consulta
        return null;
    }
} else {
    // Si no se proporcionó un ID válido en la URL
    return null;
}
?>
