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
    <link rel="stylesheet" href="/styles/search.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/styles/styleDefault.css">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="/styles/animations.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body data-user-id="<?php echo isset($user_id) ? $user_id : '-1'; ?>">
    <!-- Scripts -->
    <script src="/scripts/themeManager.js"></script>
    <script src="/scripts/indexManager.js"></script>
    <script src="/scripts/userInfoManager.js"></script>
    <script src="/scripts/orderManager.js"></script>
    <!-- Logo Home lleva al Index -->
    <script src="scripts/logoHome.js"></script>

    <div class="homeContainer">
        <div class="homeBar">
            <img id="logoHome" class="icon" data-icon="logo">
            <img id="searchIco" class="icon" data-icon="search">
            <input id="searchInput" placeholder="Buscar..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
        </div>
        <div class="subhomeBar">
            <img id="profileIco" id="pro" class="icon" data-icon="profile">
        </div>
    </div>

    <div class="menuDiv">
    <select id="orderSelect">
        <option value="asc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'selected' : ''; ?>>Ascendente</option>
        <option value="desc" <?php echo (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'selected' : ''; ?>>Descendente</option>
    </select>
    <select id="typeSelect">
        <option value="songs" <?php echo (isset($_GET['type']) && $_GET['type'] == 'songs') ? 'selected' : ''; ?>>Canciones</option>
        <option value="composer" <?php echo (isset($_GET['type']) && $_GET['type'] == 'composer') ? 'selected' : ''; ?>>Compositores</option>
        <option value="albums" <?php echo (isset($_GET['type']) && $_GET['type'] == 'albums') ? 'selected' : ''; ?>>Álbumes</option>
    </select>
    <?php require_once 'scripts/searchManager.php' ?>
    </div>    
</body>
</html>