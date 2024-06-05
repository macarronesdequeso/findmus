<?php

// Verificar si la sesión está iniciada
if (isset($_SESSION['username'])) {
    // El usuario ha iniciado sesión, mostrar el botón
    echo '<form id="likeForm" method="post">';
    echo '<input type="hidden" name="id" value="<?php echo $user_id; ?>">';
    echo '<button id="likeContainer" type="submit">';
    echo '<img id="like" class="icon" data-icon="like" alt="Me gusta">';
    echo '</button>';
    echo '<input type="hidden" name="song_id" value="' . $_GET['id'] . '">';
    echo '</form>';
    
    // Obtener el ID del usuario y el ID de la canción actual
    $userId = isset($_POST['user_id']);
    $songId = intval($_GET['id']);

    // Conectar a la base de datos 'users' para obtener las canciones favoritas del usuario
    $host = "localhost";
    $username_db = "root";
    $password_db = "";
    $database_users = "users";
    try {
        $conn_users = new PDO("mysql:host=$host;dbname=$database_users", $username_db, $password_db);
        $conn_users->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consultar la lista de canciones favoritas del usuario
        $user_sql = "SELECT liked_songs FROM users_data WHERE id = :user_id";
        $user_stmt = $conn_users->prepare($user_sql);
        $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $user_stmt->execute();
        $liked_songs_row = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if ($liked_songs_row) {
            $liked_songs_ids = explode(",", $liked_songs_row['liked_songs']);
            $isLiked = in_array($songId, $liked_songs_ids);

            if ($isLiked) {
                echo '<script>';
                echo 'document.getElementById("likeContainer").disabled = true;';
                echo 'document.getElementById("like").setAttribute("data-icon", "likeSelected");';
                echo '</script>';
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['song_id'])) {
                // El usuario ha hecho clic en el botón de "me gusta"
                if (!$isLiked) {
                    // La canción no está en favoritos, actualizar la lista
                    $new_liked_songs = empty($liked_songs_row['liked_songs']) ? $songId : $liked_songs_row['liked_songs'] . ",$songId";
                    $update_sql = "UPDATE users_data SET liked_songs = :liked_songs WHERE id = :user_id";
                    $update_stmt = $conn_users->prepare($update_sql);
                    $update_stmt->bindParam(':liked_songs', $new_liked_songs, PDO::PARAM_STR);
                    $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $update_stmt->execute();

                    // Redirigir para evitar reenvío del formulario
                    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $songId);
                    exit();
                }
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // El usuario no ha iniciado sesión, ocultar el botón
    echo '<style>#likeForm { display: none; }</style>';
}
?>