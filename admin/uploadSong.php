<?php
session_start();

require_once '../scripts/userManager.php';

// Verificar si la sesión no está iniciada y, si no, redirigir a login.php
if (!isset($_SESSION['username'])) {
    header("Location: /login?error=Por favor, inicia sesión para acceder a esta página");
    exit();
}

// Verificar si el usuario no es un administrador
if ($isAdmin != '1') {
    header("Location: unauthorized_access.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = "localhost";
    $username_db = "root";
    $password_db = "";
    $database = "music";
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del formulario
    $name = $_POST['name'];
    $date = $_POST['date'];
    $composer = $_POST['composer'];
    $album = $_POST['album'];
    $genre = $_POST['genre'];

    // Insertar la nueva canción en la base de datos
    $sql = "INSERT INTO songs (name, dateCreation, composer, album, genre) VALUES (:name, :date, :composer, :album, :genre)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':composer', $composer);
    $stmt->bindParam(':album', $album);
    $stmt->bindParam(':genre', $genre);
    $stmt->execute();

    // Obtener el ID generado automáticamente
    $id = $conn->lastInsertId();

    // Directorio donde se guardarán las canciones
    $targetDir = "../songs/$id/";

    // Si el directorio no existe, crea el directorio con permisos 0777 (lectura, escritura y ejecución para propietario, grupo y otros usuarios)
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Manejar la carga de la imagen de portada
    if (isset($_FILES['cover'])) {
        $coverFilePath = $targetDir . "cover.jpg";
        move_uploaded_file($_FILES['cover']['tmp_name'], $coverFilePath);
    }

    // Manejar la carga del archivo de canción
    if (isset($_FILES['song'])) {
        $songFilePath = $targetDir . "song.mp3";
        move_uploaded_file($_FILES['song']['tmp_name'], $songFilePath);
    }

    header("Location: /admin/uploadSong?success=Canción subida correctamente");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir canción</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/index.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Shadows CSS -->
    <link rel="stylesheet" href="/styles/shadows.css">
</head>
<body data-user-id="<?php echo isset($user_id) ? $user_id : '-1'; ?>">
    <script src="/scripts/themeManager.js"></script>
    <script src="/scripts/userInfoManager.js"></script>
    <!-- Logo Home lleva al Index -->
    <script src="/scripts/logoHome.js"></script>

    <div class="homeContainer">
        <div class="homeBar">
            <img id="logoHome" class="icon" data-icon="logo">
        </div>
        <div class="subhomeBar">
            <h2>Subir canción</h2>
            <img id="profileIco" id="pro" class="icon" data-icon="profile">
        </div>
    </div>

    <?php 
    if (isset($_GET["error"]) || isset($_GET["success"])) { 
        echo '<div class="mensaje">';
        // Verifica si se pasa un mensaje de error en los parámetros de la URL
        if (isset($_GET["error"])) { 
            echo $error_message = $_GET["error"];
        }
        // Verifica si se pasa un mensaje de éxito en los parámetros de la URL
        if (isset($_GET["success"])) { 
            echo $info_message = $_GET["success"];
        }
        echo '</div>';
    }
    ?>

    <label>Formulario subir canción</label>
    <div class="menuDiv">
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <h3>Selecciona una canción</h3>
            <input type="file" name="song" id="song" accept="audio/*" required>
        </div>
        <div>
            <h3>Selecciona una imagen de portada</h3>
            <input type="file" name="cover" id="cover" accept="image/*" required>
        </div>
    </div>
    <div class="mensaje">
        <div class="grid-container">
        <div class="grid-item">
            <h3>Nombre de la canción</h3>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="grid-item">
            <h3>Fecha de creación</h3>
            <input type="date" name="date" id="date" required>
        </div>
        <div class="grid-item">
            <h3>Compositor</h3>
            <select name="composer" id="composer" required>
                <?php
                // Conexión a la base de datos
                $host = "localhost";
                $username_db = "root";
                $password_db = "";
                $database = "music";
                $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Consulta para obtener los compositores
                $sql_composers = "SELECT name FROM composers";
                $stmt_composers = $conn->prepare($sql_composers);
                $stmt_composers->execute();
                $composers = $stmt_composers->fetchAll(PDO::FETCH_COLUMN);

                // Mostrar los compositores como opciones en el dropdown
                foreach ($composers as $composer) {
                    echo "<option value='$composer'>$composer</option>";
                }
                ?>
            </select>
        </div>
        <div class="grid-item">
            <h3>Álbum</h3>
            <select name="album" id="album" required>
                <?php
                // Consulta para obtener los álbumes
                $sql_albums = "SELECT name FROM albums";
                $stmt_albums = $conn->prepare($sql_albums);
                $stmt_albums->execute();
                $albums = $stmt_albums->fetchAll(PDO::FETCH_COLUMN);

                // Mostrar los álbumes como opciones en el dropdown
                foreach ($albums as $album) {
                    echo "<option value='$album'>$album</option>";
                }
                ?>
            </select>
        </div>
        <div class="grid-item">
            <h3>Género</h3>
            <select name="genre" id="genre" required>
                <?php
                // Consulta para obtener los géneros
                $sql_genres = "SELECT name FROM genre";
                $stmt_genres = $conn->prepare($sql_genres);
                $stmt_genres->execute();
                $genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN);

                // Mostrar los géneros como opciones en el dropdown
                foreach ($genres as $genre) {
                    echo "<option value='$genre'>$genre</option>";
                }
                ?>
            </select>
        </div>
        </div>
    </div>
    <div class="mensaje">
        <input type="submit" value="Subir canción">
        <input type="reset" value="Limpiar campos">
    </div>
    </form>
</body>
</html>