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
    // Verifica si los parámetros de búsqueda están definidos en la URL
    if (isset($_GET['q']) && isset($_GET['type'])) {
        // Obtiene los parámetros de búsqueda de la URL
        $search_query = htmlspecialchars($_GET['q']);
        $type = htmlspecialchars($_GET['type']);
        $order = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : 'asc';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

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

        // Construye la consulta SQL basada en el tipo
        $sql = "";
        $search_param = "%" . $search_query . "%";

        if ($type == 'composer') {
            $sql = "SELECT * FROM composer WHERE name LIKE ? ORDER BY name $order LIMIT ? OFFSET ?";
        } elseif ($type == 'albums') {
            $sql = "SELECT * FROM albums WHERE name LIKE ? ORDER BY name $order LIMIT ? OFFSET ?";
        } elseif ($type == 'songs') {
            $sql = "SELECT * FROM songs WHERE name LIKE ? ORDER BY name $order LIMIT ? OFFSET ?";
        } else {
            echo "<h1>Tipo de búsqueda no válido<h1>";
            exit();
        }

        // Prepara la declaración
        $stmt = $conn->prepare($sql);

        // Enlaza los parámetros
        $stmt->bind_param("sii", $search_param, $limit, $offset);

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el conjunto de resultados
        $result = $stmt->get_result();

        // Verifica si hay resultados
        if ($result->num_rows > 0) {
            // Muestra el encabezado de la tabla
            echo "<div class='resultsTable'><table>";
            
            if ($type == 'composer') {
                echo "<tr><th>Imagen</th><th>Nombre</th><th>Fecha de Nacimiento</th><th>Fecha de Muerte</th><th>Biografía</th></tr>";
            } elseif ($type == 'albums') {
                echo "<tr><th>Imagen</th><th>Nombre</th><th>Compositor</th></tr>";
            } elseif ($type == 'songs') {
                echo "<tr><th>Imagen</th><th>Nombre</th><th>Compositor</th><th>Album</th><th>Fecha creación</th><th>Vistas</th></tr>";
            }

            // Muestra los datos de cada fila
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                if ($type == 'composer') {
                    echo "<td class='center'><img id='coverImg' src='/composers/" . htmlspecialchars($row["id"]) . ".jpg' alt='Imagen del compositor'></td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["dateBirth"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["dateDeath"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["bio"]) . "</td>";
                } elseif ($type == 'albums') {
                    echo "<td class='center'><img id='coverImg' src='/albums/" . htmlspecialchars($row["id"]) . ".jpg' alt='Imagen del álbum'></td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["composer"]) . "</td>";
                } elseif ($type == 'songs') {
                    echo "<td><img id='coverImg' src='/songs/" . htmlspecialchars($row["id"]) . "/cover.jpg' alt='Imagen de la canción'></td>";
                    echo "<td><a href='/song?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row["composer"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["album"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["dateCreation"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["views"]) . "</td>";
                }
                echo "</tr>";
            }

            // Cierra la tabla
            echo "</table></div>";

            // Botón de paginación
            $nextPage = $page + 1;
            echo "<div class='pagination'>";
            echo "<a href='?q=$search_query&type=$type&order=$order&page=$nextPage'><button>Siguiente</button></a>";
            echo "</div>";

            // Botón de paginación
            $nextPage = $page - 1;
            echo "<div class='pagination'>";
            echo "<a href='?q=$search_query&type=$type&order=$order&page=$nextPage'><button>Atras</button></a>";
            echo "</div>";
        } else {
            // No se encontraron resultados
            echo "<h1>No se encontraron resultados<h1>";
        }

        // Cierra la declaración
        $stmt->close();

        // Cierra la conexión a la base de datos
        $conn->close();
    } else {
        // Si los parámetros de búsqueda no están definidos, muestra un mensaje o manéjalo según sea necesario
        echo "<h1>No se encontraron parámetros de búsqueda<h1>";
    }
    ?>
    </div>    
</body>
</html>