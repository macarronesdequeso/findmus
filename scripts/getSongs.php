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
            $sql = "SELECT DISTINCT composers.id, composers.name 
                    FROM composers, songs 
                    WHERE composers.name = songs.composer AND composers.id > -1
                    ORDER BY songs.views DESC LIMIT 10";
            break;
        case 'by_album':
            $sql = "SELECT DISTINCT albums.id, albums.name
                    FROM albums, songs 
                    WHERE albums.name = songs.album AND albums.id > -1
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

function getSongsByCategory($conn, $category) {
    $sql = "SELECT id, name FROM songs WHERE genre = :genre AND id != -1 ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':genre', $category);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRandomCategories($conn) {
    $sql = "SELECT DISTINCT genre FROM songs WHERE genre IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($genres) <= 4) {
        return $genres;
    }

    return array_rand(array_flip($genres), 4);
}

$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "music";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $selectedCategories = getRandomCategories($conn);
    $categorySongs = [];

    foreach ($selectedCategories as $category) {
        $categorySongs[$category] = getSongsByCategory($conn, $category);
    }

    $recentSong = getRandomSongByCriteria($conn, 'recent');
    $mostViewedSong = getRandomSongByCriteria($conn, 'most_viewed');
    $composerSong = getRandomSongByCriteria($conn, 'by_composer');
    $albumSong = getRandomSongByCriteria($conn, 'by_album');
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
