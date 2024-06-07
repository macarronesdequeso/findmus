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
    
    // Obtener datos del formulario
    $name = $_POST['name'];
    $dateBirth = !empty($_POST['dateBirth']) ? $_POST['dateBirth'] : null;
    $dateDeath = !empty($_POST['dateDeath']) ? $_POST['dateDeath'] : null;
    $bio = $_POST['bio'];

    // Insertar el nuevo compositor en la base de datos
    $sql = "INSERT INTO composers (name, dateBirth, dateDeath, bio) VALUES (:name, :dateBirth, :dateDeath, :bio)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':dateBirth', $dateBirth, PDO::PARAM_NULL);
    $stmt->bindParam(':dateDeath', $dateDeath, PDO::PARAM_NULL);
    $stmt->bindParam(':bio', $bio);
    $stmt->execute();

    // Obtener el ID generado automáticamente
    $id = $conn->lastInsertId();

    // Directorio donde se guardarán las imágenes de los compositores
    $targetDir = "../composers/";

    // Manejar la carga de la imagen del compositor
    if (isset($_FILES['image'])) {
        $imageFilePath = $targetDir . $id . ".jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $imageFilePath);
    }

    header("Location: /admin/uploadComposer?success=Compositor subido correctamente");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir compositor</title>
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
            <h2>Subir compositor</h2>
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

    <label>Formulario subir compositor</label>
    <div class="menuDiv">
    <form action="" method="post" enctype="multipart/form-data">
            <div class="grid-container">
                <div class="grid-item">
                    <h3>Nombre del compositor</h3>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="grid-item">
                    <h3>Fecha de nacimiento</h3>
                    <input type="date" name="dateBirth" id="dateBirth" required>
                </div>
                <div class="grid-item">
                    <h3>Fecha de defunción</h3>
                    <input type="date" name="dateDeath" id="dateDeath">
                </div>
                <div class="grid-item">
                    <h3>Biografía</h3>
                    <textarea name="bio" id="bio" rows="4" required></textarea>
                </div>
                <div class="grid-item">
                    <h3>Imagen del compositor</h3>
                    <input type="file" name="image" id="image" accept="image/*" required>
                </div>
            </div>
    </div>
    <div class="menuDiv">
            <input type="submit" value="Subir compositor">
            <input type="reset" value="Limpiar campos">
    </div>
    </form>
</body>
</html>