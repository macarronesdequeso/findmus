<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once 'scripts/userManager.php'; ?>
    <?php require_once 'scripts/getSongs.php'; ?>
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
            <img id="searchIco" class="icon" data-icon="search">
            <input id="searchInput" placeholder="Buscar..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
        </div>
        <div class="subhomeBar">
            <img id="profileIco" id="pro" class="icon" data-icon="profile">
        </div>
    </div>

    <label>Bienvenido</label>
    <div class="menuDiv songList">
        <?php if ($recentSong !== null): ?>
            <a href="song?id=<?= $recentSong['id'] ?>" class="song-link">
                <img class="song-image" src="songs/<?= $recentSong['id'] ?>/cover.jpg" alt="<?= $recentSong['id'] ?>">
                <p class="song-title">De la más recientes</p>
            </a>
        <?php endif; ?>
        
        <?php if ($mostViewedSong !== null): ?>
            <a href="song?id=<?= $mostViewedSong['id'] ?>" class="song-link">
                <img class="song-image" src="songs/<?= $mostViewedSong['id'] ?>/cover.jpg" alt="<?= $mostViewedSong['id'] ?>">
                <p class="song-title">¡Novedades!</p>
            </a>
        <?php endif; ?>
        
        <?php if ($composerSong !== null): ?>
            <a href="composer?id=<?= $composerSong['id'] ?>" class="song-link">
                <img class="song-image" src="composers/<?= $composerSong['id'] ?>.jpg" alt="<?= $composerSong['id'] ?>">
                <p class="song-title">De nuestros mejores compositores</p>
            </a>
        <?php endif; ?>
        
        <?php if ($albumSong !== null): ?>
            <a href="album?id=<?= $albumSong['id'] ?>" class="song-link">
                <img class="song-image" src="albums/<?= $albumSong['id'] ?>.jpg" alt="<?= $albumSong['id'] ?>">
                <p class="song-title">Álbumes famosos</p>
            </a>
        <?php endif; ?>
    </div>

    <label>Explora</label>
    <div class="menuDiv">
    <div class="grid2x2">
        <?php if (!empty($categorySongs)): ?>
            <?php foreach ($categorySongs as $category => $songs): ?>
                <div class="category-section">
                    <h3><?php echo htmlspecialchars($category); ?></h3>
                    <div class="songs-container">
                        <?php foreach ($songs as $song): ?>
                            <a href="song?id=<?= $song['id'] ?>" class="song-link">
                                <img class="song-image" src="songs/<?= $song['id'] ?>/cover.jpg" alt="<?= htmlspecialchars($song['name']) ?>">
                                <p class="song-list-title"><?= htmlspecialchars($song['name']) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </div>


</body>
</html>