<?php
// Obtener el ID del compositor desde la URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Si el ID es menor que -1, establecerlo como 
    if ($id < -2) {
        $id = -1;
    }

    // Intentar la conexión a la base de datos utilizando PDO
    try {
        require_once 'cred.php';

        // Conexión a la base de datos
        $host = $DBhost;
        $username_db = $DBusername;
        $password_db = $DBpassword;
        $database = "music";
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para obtener los detalles del compositor con el ID proporcionado
        $sql = "SELECT * FROM albums WHERE id = :id";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        // Bind de parámetros
        $stmt->bindParam(':id', $id);
        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados de la consulta
        $albums = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el compositor
        if($albums) {
            return [
                'albums' => $albums
            ];
        } else {
            // No se encontró el compositor
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
