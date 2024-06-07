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

    // Obtener ID de la canción a borrar
    $songId = $_POST['song_id'];

    // Borrar la canción de la base de datos
    $sql = "DELETE FROM songs WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $songId);
    $stmt->execute();

    // Directorio donde se guardan las canciones
    $targetDir = "../songs/$songId/";

    // Borrar el directorio de la canción y su contenido
    if (file_exists($targetDir)) {
        // Borra los ficheros del directorio
        unlink($targetDir . "cover.jpg");
        unlink($targetDir . "song.mp3");
        rmdir($targetDir);
    } else {
        echo "Cojones de mono ruso";
    }

    header("Location: /admin/deleteSong?success=Canción borrada correctamente");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar canción</title>
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
            <h2>Borrar canción</h2>
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

    <label>Formulario para borrar canción</label>
    <div class="menuDiv">
    <form action="" method="post">
        <div class="grid-container">
            <div class="grid-item">
                <h3>Selecciona una canción</h3>
                <select name="song_id" id="song_id" required>
                    <?php
                    // Conexión a la base de datos
                    $host = "localhost";
                    $username_db = "root";
                    $password_db = "";
                    $database = "music";
                    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Consulta para obtener las canciones
                    $sql_songs = "SELECT id, name FROM songs";
                    $stmt_songs = $conn->prepare($sql_songs);
                    $stmt_songs->execute();
                    $songs = $stmt_songs->fetchAll(PDO::FETCH_ASSOC);

                    // Mostrar las canciones como opciones en el dropdown
                    foreach ($songs as $song) {
                        echo "<option value='{$song['id']}'>{$song['id']}: {$song['name']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="mensaje">
        <input type="submit" value="Borrar canción">
    </div>
</form>
</div>
        
</body>
</html>
