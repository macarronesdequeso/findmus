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
    <link rel="stylesheet" href="/styles/animationsLogin.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <div class="imageContainer">
        <!-- Contenedor de imagen -->
        <div class="imageDisplay">
            <?php
            // Incluir el script songManager.php para obtener los detalles de la canción y los archivos asociados
            require_once "scripts/songManager.php";

            // Verificar si se encontró la canción
            if($song) {
                // Mostrar la imagen de la portada del álbum
                echo "<img src='" . $image_path . "' alt='Portada del Álbum'>";
            } else {
                echo "<p>No se encontró ninguna canción con ese ID.</p>";
            }
            ?>
        </div>
        
        <!-- Contenedor de detalles de canción -->
        <div class="songDetails">
            <?php
            // Verificar si se encontró la canción
            if($song) {
                // Mostrar los detalles de la canción
                echo "<h1>" . $song['name'] . "</h1>";
                echo "<p>Fecha de Creación: " . $song['dateCreation'] . "</p>";
                echo "<p>Compositor: " . $song['composer'] . "</p>";
                echo "<p>Vistas: " . $song['views'] . "</p>";

                // Reproducir el audio de la canción
                echo "<audio controls autoplay>";
                echo "<source src='" . $song_path . "' type='audio/mpeg'>";
                echo "Tu navegador no soporta la reproducción de audio.";
                echo "</audio>";
            } else {
                echo "<p>No se encontró ninguna canción con ese ID.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
