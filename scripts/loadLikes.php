<?php
function getLikedSongs($userId) {

    require 'cred.php';

    // Conexión a la base de datos
    $host = $DBhost;
    $username_db = $DBusername;
    $password_db = $DBpassword;
    $database_users = "users";

    $conn_users = new PDO("mysql:host=$host;dbname=$database_users", $username_db, $password_db);
    $conn_users->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener la lista de IDs de canciones que le gustan al usuario
    $user_sql = "SELECT liked_songs FROM users_data WHERE id = :user_id";
    $user_stmt = $conn_users->prepare($user_sql);
    $user_stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $user_stmt->execute();
    $liked_songs_row = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$liked_songs_row) {
        return false;
    }

    // Obtener la cadena de IDs de canciones
    $liked_songs_str = trim($liked_songs_row['liked_songs'], ",");

    // Asegurarse de que los IDs son válidos (números enteros) y eliminar valores vacíos pero mantener el 0
    $liked_songs_ids = array_filter(array_map('intval', explode(",", $liked_songs_str)), function($value) {
        return $value !== '';
    });

    if (empty($liked_songs_ids)) {
        return [];
    }

    // Conectar a la base de datos 'music' para obtener los detalles de las canciones
    $database_music = "music";
    $conn_music = new PDO("mysql:host=$host;dbname=$database_music", $username_db, $password_db);
    $conn_music->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar la tabla de canciones para obtener los detalles de las canciones que le gustan al usuario
    $liked_songs_sql = "SELECT * FROM songs WHERE id IN (" . implode(",", $liked_songs_ids) . ")";
    $liked_songs_stmt = $conn_music->prepare($liked_songs_sql);
    $liked_songs_stmt->execute();
    $liked_songs = $liked_songs_stmt->fetchAll(PDO::FETCH_ASSOC);

    return $liked_songs;
}
?>