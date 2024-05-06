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
    <link rel="stylesheet" href="/styles/animationsLogin.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <!-- Scripts -->
    <script src="/scripts/themeManager.js"></script>
    <script src="/scripts/indexManager.js"></script>
    <div class="homeContainer">
        <div class="homeBar">
            <img id="logoHome" class="icon" data-icon="logo">
            <img id="searchIco" class="icon" data-icon="search">
            <input id="searchInput" placeholder="Buscar...">
        </div>
        <div class="subhomeBar">
            
            <img id="profileIco" id="pro" class="icon" data-icon="profile">
        </div>
    </div>
    <div class="menuDiv">
    <?php
    // Check if the search parameter is set in the URL
    if (isset($_GET['search'])) {
        // Get the search query from the URL and sanitize it
        $search_query = htmlspecialchars($_GET['search']);

        // Your database connection code
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "music";

        // Connect to the database
        $conn = new mysqli($host, $username, $password, $database);

        // Check the connection
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // SQL query to search for songs matching the search query
        $sql = "SELECT * FROM songs WHERE composer LIKE ? OR name LIKE ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $search_param = "%" . $search_query . "%";
        $stmt->bind_param("ss", $search_param, $search_param);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Output table header
            echo "<div class='resultsTable'><table>";
            echo "<tr><th></th><th>Nombre</th><th>Fecha creación</th><th>Compositor</th><th>Vistas</th></tr>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><img id='coverImg' src='/songs/" . htmlspecialchars($row["id"]) . "/cover.jpg' alt='Song Image'></td>";
                echo "<td><a href='/song.php?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                echo "<td>" . htmlspecialchars($row["dateCreation"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["composer"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["views"]) . "</td>";
                echo "</tr>";
            }

            // Close the table
            echo "</table></div>";
        } else {
            // No results found
            echo "<h1>No se encontraron resultados<h1>";
        }

        // Close the statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    } else {
        // If the search parameter is not set, display a message or handle it as needed
        echo "<h1>No se encontro el parametro de busqueda<h1>";
    }
    ?>
    </div>    
</body>
</html>