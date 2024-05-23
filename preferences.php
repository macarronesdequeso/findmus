<?php
session_start();

// Verificar si la sesión no está iniciada y, si no, redirigir a login.php
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

// Manejar el cierre de sesión
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index");
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
    <!-- Shadows CSS -->
    <link rel="stylesheet" href="/styles/shadows.css">
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

    <label>Información de usuario</label>
    <div class="menuDiv">

        <form action="scripts/pfpUpload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="profilePicture" accept="image/*">
            <input type="hidden" name="user_id" value="<?php echo isset($user_id) ? $user_id : '-1'; ?>">
            <button type="submit" name="submit">Guardar foto de perfil</button>
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

    <label>Debug</label>
    <div class="menuDiv">

        <a href="songTest.html"><button>Song Page Test</button></a>
        <form action="/song" method="get">
            <button type="submit">Go to Song...</button>
            <br>
            <input type="text" name="id" placeholder="Song ID">
        </form>
        <form action="/composer.php" method="get">
            <button type="submit">Go to Composer...</button>
            <br>
            <input type="text" name="id" placeholder="Composer ID">
        </form>
        <a href="phpHealth"><button>PHP Health Check</button></a>

    </div>
</body>
</html>
