<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Findmus</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/login.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Shadows CSS -->
    <link rel="stylesheet" href="/styles/shadows.css">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animationsLogin.css">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <div class="mainPage">
        <div class="mainMenu">
            <img class="icon" data-icon="logoText">
            <form action="scripts/login.php" method="post">
                <?php 
                // Check if an error message is passed in the URL parameters
                if (isset($_GET["error"])) { 
                    echo $error_message = $_GET["error"];
                }
                if (isset($_GET["success"])) { 
                    echo $info_message = $_GET["success"];
                ?>
                <?php } ?>
                <h3>Nombre de usuario</h3>
                <input type="text" name="username" id="username" placeholder="Nombre de usuario" required>
                <h3>Contraseña</h3>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
                <button type="submit">Iniciar sesión</button>
            </form>
            <br>
            <a href="recover.html">Se me ha olvidado la contraseña</a>
        </div>
    </div>
    <footer>
        <h4>¿No tienes cuenta? <a href="register.php">Regístrate</a></h4>
        <h5>Findmus © 2024</h5>
    </footer>
</body>
</html>