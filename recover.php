<!DOCTYPE html>
<html lang="en">
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
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <script src="scripts/themeManager.js"></script>
    <div class="mainMenu">
        <img id="logoHome" class="icon" data-icon="logoText">
        <form action="scripts/recover.php" method="post">
            <?php 
                // Verifica si se pasa un mensaje de error en los parámetros de la URL
                if (isset($_GET["error"])) { 
                    echo $error_message = $_GET["error"];
                    ?>
            <?php } ?>
            <h4>Introduzca su correo vinculado y su clave de seguridad</h4>
            <input type="text" name="email" id="email" placeholder="Correo electrónico" required>
            <h4></h4>
            <input type="text" name="clave" id="clave" placeholder="Clave de seguridad" required>
            <h4>Ponga la nueva contraseña aquí</h4>
            <input type="password" name="password" id="password" placeholder="Nueva contraseña" required minlength="8">
            <button type="submit">Reestablecer contraseña</button>
        </form>
    </div>
    <footer id="loginScreen">
        <h4>¿Te has acordado? <a href="login">Inicia sesión</a></h4>
        <h5>Findmus © 2024</h5>
    </footer>
</body>
</html>