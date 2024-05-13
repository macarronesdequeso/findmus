<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "music";

// Obtener el ID del compositor desde la URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Si el ID es menor que -1, establecerlo como 
    if ($id < -1) {
        $id = -1;
    }

    // Intentar la conexión a la base de datos utilizando PDO
    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para obtener los detalles del compositor con el ID proporcionado
        $sql = "SELECT * FROM composer WHERE id = :id";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);
        // Bind de parámetros
        $stmt->bindParam(':id', $id);
        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados de la consulta
        $composer = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el compositor
        if($composer) {
            return [
                'composer' => $composer
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
