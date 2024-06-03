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
    $composer = $_POST['composer'];

    // Insertar el nuevo álbum en la base de datos
    $sql = "INSERT INTO albums (name, composer) VALUES (:name, :composer)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':composer', $composer);
    $stmt->execute();

    // Obtener el ID generado automáticamente
    $id = $conn->lastInsertId();

    // Directorio donde se guardarán las imágenes de los álbumes
    $targetDir = "../albums/";

    // Manejar la carga de la imagen del álbum
    if (isset($_FILES['cover'])) {
        $coverFilePath = $targetDir . $id . ".jpg";
        move_uploaded_file($_FILES['cover']['tmp_name'], $coverFilePath);
    }

    header("Location: /admin/uploadAlbum?success=Álbum subido correctamente");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir álbum</title>
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
            <h2>Subir álbum</h2>
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

    <label>Formulario subir álbum</label>
    <div class="menuDiv">
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <h3>Selecciona una imagen de portada</h3>
            <input type="file" name="cover" id="cover" accept="image/*" required>
        </div>
    </div>
    <div class="mensaje">
        <div class="grid-container">
            <div class="grid-item">
            <h3>Nombre del álbum</h3>
            <input type="text" name="name" id="name" required>
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
                    $sql_composers = "SELECT id, name FROM composers";
                    $stmt_composers = $conn->prepare($sql_composers);
                    $stmt_composers->execute();
                    $composers = $stmt_composers->fetchAll(PDO::FETCH_ASSOC);

                    // Mostrar los compositores como opciones en el dropdown
                    foreach ($composers as $composer) {
                        echo "<option value='{$composer['name']}'>{$composer['name']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="mensaje">
            <input type="submit" value="Subir álbum">
            <input type="reset" value="Limpiar campos">
    </div>
    </form>
</body>
</html>
