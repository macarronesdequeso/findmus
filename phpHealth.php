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
    echo "<div class='mensaje'>";
    function checkTableAndColumns($conn, $database, $table, $columns) {
        try {
            // Consultar las columnas de la tabla
            $query = $conn->query("SHOW COLUMNS FROM $database.$table");
            $existingColumns = $query->fetchAll(PDO::FETCH_COLUMN);

            // Verificar cada columna
            foreach ($columns as $column) {
                if (in_array($column, $existingColumns)) {
                    echo "OK: $database.$table.$column<br>";
                } else {
                    echo "ERROR: $database.$table.$column no encontrado<br>";
                }
            }
        } catch (PDOException $e) {
            echo "ERROR: No se pudo verificar $database.$table - " . $e->getMessage() . "<br>";
        }
    }

    try {
        // Intentar la conexión a la base de datos utilizando PDO
        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
        // Establecer el modo de error de PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar las tablas y columnas en la base de datos 'users'
        checkTableAndColumns($conn, "users", "users_cred", ["id", "user", "pass", "isAdmin", "email", "key"]);
        checkTableAndColumns($conn, "users", "users_data", ["id", "firstName", "lastName", "dateBirth", "country", "liked_songs"]);

        // Cambiar a la base de datos 'music'
        $database = "music";
        $conn->exec("USE $database");

        // Verificar las tablas y columnas en la base de datos 'music'
        checkTableAndColumns($conn, "music", "albums", ["id", "name", "composer"]);
        checkTableAndColumns($conn, "music", "composers", ["id", "name", "dateBirth", "dateDeath", "bio"]);
        checkTableAndColumns($conn, "music", "genre", ["name"]);
        checkTableAndColumns($conn, "music", "songs", ["id", "name", "dateCreation", "composer", "album", "genre", "views"]);

    } catch (PDOException $e) {
        // En caso de error, mostrar el mensaje de error
        echo "ERROR: " . $e->getMessage();
    }
    echo "</div>";
    ?>
</body>
</html>