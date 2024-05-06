<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Check</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
</head>
<body>
    <!-- Scripts -->
    <script src="/scripts/themeManager.js"></script>
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <?php
    // Configuración de la conexión a la base de datos
    $host = "localhost";
    $username_db = "root";
    $password_db = "";
    $database = "users";

    try {
        // Intentar la conexión a la base de datos utilizando PDO
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si la conexión a la base de datos fue exitosa
        echo "OK";
    } catch (PDOException $e) {
        // En caso de error, mostrar el mensaje de error
        echo "ERROR: " . $e->getMessage();
    }
    ?>
</body>
</html>