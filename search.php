<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Findmus</title>
    <!-- Theme CSS -->
    <link rel="stylesheet" id="theme-style">
    <!-- Specific CSS -->
    <link rel="stylesheet" href="/styles/search.css">
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
    <?php
    // Verifica si el parámetro de búsqueda está definido en la URL
    if (isset($_GET['q'])) {
        // Obtiene la consulta de búsqueda de la URL
        $search_query = htmlspecialchars($_GET['q']);

        // Tu código de conexión a la base de datos
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "music";

        // Conectar a la base de datos
        $conn = new mysqli($host, $username, $password, $database);

        // Verifica la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL para buscar canciones que coincidan con la consulta de búsqueda
        $sql = "SELECT * FROM songs WHERE composer LIKE ? OR name LIKE ?";
        
        // Prepara la declaración
        $stmt = $conn->prepare($sql);

        // Enlaza los parámetros
        $search_param = "%" . $search_query . "%";
        $stmt->bind_param("ss", $search_param, $search_param);

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el conjunto de resultados
        $result = $stmt->get_result();

        // Verifica si hay resultados
        if ($result->num_rows > 0) {
            // Muestra el encabezado de la tabla
            echo "<div class='resultsTable'><table>";
            echo "<tr><th></th><th>Nombre</th><th>Compositor</th><th>Album</th><th>Fecha creación</th><th>Vistas</th></tr>";

            // Muestra los datos de cada fila
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img id='coverImg' src='/songs/" . htmlspecialchars($row["id"]) . "/cover.jpg' alt='Imagen de la canción'></td>";
                echo "<td><a href='/song?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                echo "<td>" . htmlspecialchars($row["composer"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["album"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["dateCreation"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["views"]) . "</td>";
                echo "</tr>";
            }

            // Cierra la tabla
            echo "</table></div>";
        } else {
            // No se encontraron resultados
            echo "<h1>No se encontraron resultados<h1>";
        }

        // Cierra la declaración
        $stmt->close();

        // Cierra la conexión a la base de datos
        $conn->close();
    } else {
        // Si el parámetro de búsqueda no está definido, muestra un mensaje o manéjalo según sea necesario
        echo "<h1>No se encontró el parámetro de búsqueda<h1>";
    }
    ?>
    </div>    
</body>
</html>