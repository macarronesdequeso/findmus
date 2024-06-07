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
    require_once '../scripts/cred.php';

    // Conexión a la base de datos
    $host = $DBhost;
    $username_db = $DBusername;
    $password_db = $DBpassword;
    $database = "music";
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener ID del álbum a borrar
    $albumId = $_POST['album_id'];

    // Actualizar canciones relacionadas
    $sql_update_songs = "UPDATE songs SET album = 'No Album' WHERE album = (SELECT name FROM albums WHERE id = :id)";
    $stmt_update_songs = $conn->prepare($sql_update_songs);
    $stmt_update_songs->bindParam(':id', $albumId);
    $stmt_update_songs->execute();

    // Borrar el álbum de la base de datos
    $sql_delete = "DELETE FROM albums WHERE id = :id";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $albumId);
    $stmt_delete->execute();

    // Eliminar la imagen del álbum
    $imageFilePath = "../albums/" . $albumId . ".jpg";
    if (file_exists($imageFilePath)) {
        unlink($imageFilePath);
    }

    header("Location: /admin/deleteAlbum?success=Álbum borrado correctamente");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar álbum</title>
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
            <h2>Borrar álbum</h2>
            <img id="profileIco" class="icon" data-icon="profile">
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

    <label>Formulario para borrar álbum</label>
    <div class="menuDiv">
        <form action="" method="post">
            <div class="grid-container">
                <div class="grid-item">
                    <h3>Selecciona un álbum</h3>
                    <select name="album_id" id="album_id" required>
                        <?php
                        // Conexión a la base de datos
                        require_once '../scripts/cred.php';

                        // Conexión a la base de datos
                        $host = $DBhost;
                        $username_db = $DBusername;
                        $password_db = $DBpassword;
                        $database = "music";
                        $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Consulta para obtener los álbumes
                        $sql_albums = "SELECT id, name FROM albums";
                        $stmt_albums = $conn->prepare($sql_albums);
                        $stmt_albums->execute();
                        $albums = $stmt_albums->fetchAll(PDO::FETCH_ASSOC);

                        // Mostrar los álbumes como opciones en el dropdown
                        foreach ($albums as $album) {
                            echo "<option value='{$album['id']}'>{$album['id']}: {$album['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
            <div class="mensaje">
                    <input type="submit" value="Borrar álbum">
            </div>
        </form>
    </div>
</body>
</html>
