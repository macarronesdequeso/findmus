<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Findmus</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/song.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Shadows CSS -->
    <link rel="stylesheet" href="/styles/shadows.css">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <script src="scripts/songPlaylistManager.js"></script>
    <div class="imageContainer">
        <!-- Contenedor de imagen -->
        <div class="imageDisplay">
            <?php
            // Incluir el script albumManager.php para obtener los detalles del compositor y los archivos asociados
            require_once "scripts/albumManager.php";

            // Verificar si se encontró el compositor
            if($albums) {
                // Mostrar la imagen del compositor
                echo "<img src='/albums/" . $albums['id'] . ".jpg' alt='Imagen del Compositor'>";
            } else {
                echo "<p>No se encontró ningún compositor con ese ID.</p>";
            }
            ?>
        </div>
        
        <!-- Contenedor de detalles de compositor y canciones -->
        <div class="songDetails">
            <?php
            // Verificar si se encontró el compositor
            if($albums) {
                // Mostrar los detalles del compositor
                echo "<h1>" . $albums['name'] . "</h1>";
                if ($albums['composer'] != null) {
                    echo "<p>Compositor: " . $albums['composer'] . "</p>";
                }

                // Consulta SQL para obtener las canciones compuestas por el artista
                $songs_sql = "SELECT * FROM songs WHERE album = :albums AND id != -1 ORDER BY dateCreation ASC";

                // Preparar la consulta
                $songs_stmt = $conn->prepare($songs_sql);
                $songs_stmt->bindParam(':albums', $albums['name']);
                // Ejecutar la consulta
                $songs_stmt->execute();

                // Obtener las canciones compuestas por el artista
                $songs = $songs_stmt->fetchAll(PDO::FETCH_ASSOC);

                // Mostrar las canciones en una tabla
                echo "<h2>Canciones en " . $albums['name'] . "</h2>";
                echo "<table>";
                foreach ($songs as $song) {
                    echo "<tr>";
                    echo "<td>" . $song['name'] . "</td>";
                    echo "<td><audio controls><source src='/songs/" . $song['id'] . "/song.mp3' type='audio/mpeg'>Tu navegador no soporta la reproducción de audio.</audio></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No se encontró ningún compositor con ese ID.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>