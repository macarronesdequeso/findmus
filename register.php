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
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <!-- Logo Home lleva al Index -->
    <script src="scripts/logoHome.js"></script>

    <div class="mainPage">
        <div class="mainMenu">
            <img id="logoHome" class="icon" data-icon="logoText">
            <form action="scripts/registerManager.php" method="post">
                <?php 
                // Verifica si se pasa un mensaje de error en los parámetros de la URL
                if (isset($_GET["error"])) { 
                    echo $error_message = $_GET["error"];
                    ?>
                <?php } ?>
                <h3>Ingresa un nombre de usuario</h3>
                <input type="text" name="username" id="username" placeholder="Nombre de usuario" required minlength="3" maxlength="20">
                <h3>Ingresa un correo válido</h3>
                <input type="email" name="email" id="email" placeholder="Correo" required>
                <h3>Ingresa una contraseña</h3>
                <input type="password" name="password" id="password" placeholder="Contraseña" required minlength="8" maxlength="25">
                <button id="register" type="submit">Registrarse</button>
            </form>
        </div>
    </div>
    
    <footer id="loginScreen">
        <h4>¿Ya tienes cuenta? <a href="login">Inicia sesión</a></h4>
        <h5>Findmus &copy 2024</h5>
    </footer>
</body>
</html>
