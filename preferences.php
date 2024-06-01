<?php
session_start();

// Verificar si la sesión no está iniciada y, si no, redirigir a login.php
if (!isset($_SESSION['username'])) {
    header("Location: login?error=Por favor, inicia sesión para acceder a esta página");
    exit();
}

// Manejar el cierre de sesión
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /");
    exit();
}

require_once 'scripts/userManager.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Findmus</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/index.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body data-user-id="<?php echo isset($user_id) ? $user_id : '-1'; ?>">
    <script src="scripts/themeManager.js"></script>
    <script src="scripts/themeChanger.js"></script>
    <script src="scripts/preferencesManager.js"></script>
    <script src="scripts/userInfoManager.js"></script>
    <!-- Logo Home lleva al Index -->
    <script src="scripts/logoHome.js"></script>

    <div class="homeContainer">
        <div class="homeBar">
            <img id="logoHome" class="icon" data-icon="logo">
        </div>
        <div class="subhomeBar">
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


    <label>Foto de perfil</label>
    <div class="menuDiv">

        <form action="scripts/pfpUpload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profilePicture" accept="image/*">
            <input type="hidden" name="user_id" value="<?php echo isset($user_id) ? $user_id : '-1'; ?>">
            <button type="submit" name="submit">Guardar foto de perfil</button>
        </form>

    </div>

    <label>Información del usuario</label>
        <div class="menuDiv">
            <form action="scripts/updateUser.php" method="post">
                <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                <div class="grid-container">
                <div class="grid-item">
                    <h3>Nombre de usuario:</h3>
                    <input type="text" id="firstName" placeholder="Nombre" name="firstName" value="<?php echo $user_data['firstName']; ?>">
                </div>
                <div class="grid-item">
                    <h3>Apellido:</h3>
                    <input type="text" id="lastName" placeholder="Apellido" name="lastName" value="<?php echo $user_data['lastName']; ?>">
                </div>
                <div class="grid-item">
                    <h3>Fecha de Nacimiento:</h3>
                    <input type="date" id="dateBirth" name="dateBirth" value="<?php echo $user_data['dateBirth']; ?>">
                </div>
                <div class="grid-item">
                    <h3>País:</h3>
                    <input type="text" id="country" placeholder="País" name="country" value="<?php echo $user_data['country']; ?>">
                    <button type="submit">Actualizar Información</button>
                </div>
                <div class="grid-item">
                </div>
            </div>
            </form>
        </div>

    <label>Tema</label>
    <div class="menuDiv">
        
        <button class="theme-toggle" data="dark">Change Theme (Dark)</button>
        <button class="theme-toggle" data="light">Change Theme (Light)</button>

    </div>

    <label>Cuenta</label>
    <div class="menuDiv">

        <form method="post" style="display: inline;">
            <button type="submit" name="logout" class="logout">Cerrar sesión</button>
        </form>

    </div>

    <?php if ($isAdmin == 1): ?>
        <label>Debug (solo administrador)</label>
        <div class="menuDiv">
            <a href="admin/uploadSong"><button>Añadir canción</button></a>
            <a href="songTest.html"><button>Test Página Canción</button></a>
            <form action="/song" method="get">
                <button type="submit">Ir a canción...</button>
                <br>
                <input type="text" name="id" placeholder="Canción ID">
            </form>
            <form action="/composer" method="get">
                <button type="submit">Ir al compositor...</button>
                <br>
                <input type="text" name="id" placeholder="Compositor ID">
            </form>
            <form action="/album" method="get">
                <button type="submit">Ir al álbum...</button>
                <br>
                <input type="text" name="id" placeholder="Álbum ID">
            </form>
            <a href="phpHealth"><button>PHP Health Check</button></a>
        </div>
    <?php endif; ?>

</body>
</html>
