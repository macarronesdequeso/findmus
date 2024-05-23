<?php
function getRandomSongByCriteria($conn, $criteria) {
    switch ($criteria) {
        case 'recent':
            $sql = "SELECT DISTINCT id, name FROM songs WHERE id != -1 ORDER BY id DESC LIMIT 10";
            break;
        case 'most_viewed':
            $sql = "SELECT DISTINCT id, name FROM songs WHERE id != -1 ORDER BY views DESC LIMIT 10";
            break;
        case 'by_composer':
            $sql = "SELECT DISTINCT composer.id, composer.name 
                    FROM composer, songs 
                    WHERE composer.name = songs.composer AND composer.id != -1
                    ORDER BY songs.views DESC LIMIT 10";
            break;
        case 'by_album':
            $sql = "SELECT DISTINCT albums.id, albums.name 
                    FROM albums, songs 
                    WHERE albums.name = songs.album 
                    ORDER BY songs.views DESC LIMIT 10";
            break;
        default:
            return null;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($songs)) {
        $randomIndex = array_rand($songs);
        return $songs[$randomIndex];
    }

    return null;
}

// Configuración de la conexión a la base de datos
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "music";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $recentSong = getRandomSongByCriteria($conn, 'recent');
    $mostViewedSong = getRandomSongByCriteria($conn, 'most_viewed');
    $composerSong = getRandomSongByCriteria($conn, 'by_composer');
    $albumSong = getRandomSongByCriteria($conn, 'by_album');
} catch (PDOException $e) {
    // Manejar el error de conexión o consulta
    echo "Error: " . $e->getMessage();
}
?>
