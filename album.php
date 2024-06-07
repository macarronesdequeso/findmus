<?php

// Incluir el script albumManager.php para obtener los detalles del compositor y los archivos asociados
require_once "scripts/albumManager.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Findmus - " . $albums['name']; ?></title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/song.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <script src="scripts/logoHome.js"></script>
    <script src="scripts/songPlaylistManager.js"></script>
    <div class="imageContainer">
        <!-- Contenedor de imagen -->
        <div class="imageDisplay">
            <?php

            // Verificar si se encontró el compositor
            if($albums) {
                // Mostrar la imagen del compositor
                echo "<img src='/albums/" . $albums['id'] . ".jpg' alt='Imagen del Album'>";
            } else {
                echo "<p>No se encontró ningún compositor con ese ID.</p>";
            }
            ?>
        </div>
        
        <!-- Contenedor de detalles de compositor y canciones -->
        <div class="songDetails">
            <div class="logo mensaje">
                <img id="logoHome" class="icon" data-icon="logoText">
            </div>
            <?php
            // Verificar si se encontró el compositor
            if($albums) {
                // Mostrar los detalles del compositor
                echo "<h1>" . $albums['name'] . "</h1>";
                if ($albums['composer'] != null) {
                    echo "<p>Compositor: " . $albums['composer'] . "</p>";
                }
                if ($albums['date'] != null) {
                    echo "<p>Fecha de lanzamiento: " . $albums['date'] . "</p>";
                }

                if ($albums['id'] == -2) {
                    require_once "scripts/userManager.php";

                    // Verificar si la sesión no está iniciada y, si no, redirigir a login.php
                    if (!isset($_SESSION['username'])) {
                        header("Location: /login?error=Por favor, inicia sesión para acceder a esta página");
                        exit();
                    }

                    // Incluir el script para obtener las canciones favoritas del usuario
                    require_once "scripts/loadLikes.php";

                    // Obtener las canciones favoritas del usuario
                    $likedSongs = getLikedSongs($user_id);

                    if ($likedSongs) {
                        echo "<h2>Canciones favoritas</h2>";
                        echo "<table>";
                        foreach ($likedSongs as $song) {
                            echo "<tr>";
                            echo "<td><a href='song?id=" . $song['id'] . "'>" . $song['name'] . "</a></td>";
                            echo "<td><audio controls><source src='/songs/" . $song['id'] . "/song.mp3' type='audio/mpeg'>Tu navegador no soporta la reproducción de audio.</audio></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No se encontraron canciones favoritas.</p>";
                    }
                } else {
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
                        echo "<td><a href='song?id=" . $song['id'] . "'>" . $song['name'] . "</a></td>";
                        echo "<td><audio controls><source src='/songs/" . $song['id'] . "/song.mp3' type='audio/mpeg'>Tu navegador no soporta la reproducción de audio.</audio></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            } else {
                echo "<p>No se encontró ningún compositor con ese ID.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
