<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once 'scripts/userManager.php'; ?>
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
    <!-- Scripts -->
    <script src="/scripts/themeManager.js"></script>
    <script src="/scripts/indexManager.js"></script>
    <script src="/scripts/userInfoManager.js"></script>
    <div class="fullScreen">

    </div>
    
    <div class="homeContainer">
        <div class="homeBar">
            <img id="logoHome" class="icon" data-icon="logo">
        </div>
        <div class="subhomeBar">
            <img id="searchIco" class="icon" data-icon="search">
            <input id="searchInput" placeholder="Buscar...">
            <img id="profileIco" id="pro" class="icon" data-icon="profile">
        </div>
    </div>

    <label>Bienvenido</label>
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

    <label>Musica</label> 
    <div class="menuDiv">
        <a href="song.php?id=0" class="song-link">
            <img class="song-image" src="/songs/0/cover.jpg" alt="Nombre Cancion">
            <p class="song-title">Nombre Cancion</p>
        </a>          
    </div>
</body>
</html>