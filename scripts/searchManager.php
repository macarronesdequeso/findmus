<?php
    // Verifica si los parámetros de búsqueda están definidos en la URL
    if (isset($_GET['q']) && isset($_GET['type'])) {
        // Obtiene los parámetros de búsqueda de la URL y los limpia para evitar inyecciones XSS
        $search_query = htmlspecialchars($_GET['q']);
        $type = htmlspecialchars($_GET['type']);
        $order = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : 'asc';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;  // Número de resultados por página
        $offset = ($page - 1) * $limit;  // Calcula el offset para la consulta SQL

        // Datos de conexión a la base de datos
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

        // Inicializa las consultas SQL
        $sql = "";
        $count_sql = "";
        $search_param = "%" . $search_query . "%";

        // Construye la consulta SQL basada en el tipo
        if ($type == 'composer') {
            $sql = "SELECT * FROM composer WHERE id > -1 AND (name LIKE ?) ORDER BY name $order LIMIT ? OFFSET ?";
            $count_sql = "SELECT COUNT(*) AS total FROM composer WHERE name LIKE ?";
        } elseif ($type == 'albums') {
            $sql = "SELECT * FROM albums WHERE id > -1  AND (name LIKE ?) ORDER BY name $order LIMIT ? OFFSET ?";
            $count_sql = "SELECT COUNT(*) AS total FROM albums WHERE name LIKE ?";
        } elseif ($type == 'songs') {
            $sql = "SELECT * FROM songs WHERE id > -1 AND (name LIKE ?) ORDER BY name $order LIMIT ? OFFSET ?";
            $count_sql = "SELECT COUNT(*) AS total FROM songs WHERE name LIKE ?";
        } else {
            echo "<h1>Tipo de búsqueda no válido<h1>";
            exit();
        }

        // Prepara y ejecuta la consulta para contar el total de resultados
        $count_stmt = $conn->prepare($count_sql);
        $count_stmt->bind_param("s", $search_param);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_rows = $count_result->fetch_assoc()['total'];
        $count_stmt->close();

        // Prepara la declaración para obtener los resultados
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $search_param, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica si hay resultados
        if ($result->num_rows > 0) {
            // Muestra el encabezado de la tabla
            echo "<div class='resultsTable'><table>";
            
            // Encabezados de la tabla según el tipo de búsqueda
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
                    echo "<td><a href='/composer?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                    echo "<td>" . (!empty($row["dateBirth"]) ? htmlspecialchars($row["dateBirth"]) : "No registrado") . "</td>";
                    echo "<td>" . (!empty($row["dateDeath"]) ? htmlspecialchars($row["dateDeath"]) : "No registrado") . "</td>";
                    echo "<td>" . (!empty($row["bio"]) ? htmlspecialchars($row["bio"]) : "No registrado") . "</td>";
                } elseif ($type == 'albums') {
                    echo "<td><img id='coverImg' src='/albums/" . htmlspecialchars($row["id"]) . ".jpg' alt='Imagen del álbum'></td>";
                    echo "<td><a href='/album?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                    echo "<td>" . (!empty($row["composer"]) ? htmlspecialchars($row["composer"]) : "No registrado") . "</td>";
                } elseif ($type == 'songs') {
                    echo "<td><img id='coverImg' src='/songs/" . htmlspecialchars($row["id"]) . "/cover.jpg' alt='Imagen de la canción'></td>";
                    echo "<td><a href='/song?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["name"]) . "</a></td>";
                    echo "<td>" . (!empty($row["composer"]) ? htmlspecialchars($row["composer"]) : "No registrado") . "</td>";
                    echo "<td>" . (!empty($row["album"]) ? htmlspecialchars($row["album"]) : "No registrado") . "</td>";
                    echo "<td>" . (!empty($row["dateCreation"]) ? htmlspecialchars($row["dateCreation"]) : "No registrado") . "</td>";
                    echo "<td>" . (!empty($row["views"]) ? htmlspecialchars($row["views"]) : "No registrado") . "</td>";
                }
                echo "</tr>";
            }            

            // Cierra la tabla
            echo "</table></div>";

            // Botones de paginación
            $nextPage = $page + 1;
            $lastPage = $page - 1;
            echo "<div class='pagination'>";
            // Si es la primera página, deshabilita el botón de Atrás
            echo "<a href='?q=$search_query&type=$type&order=$order&page=$lastPage'><button ".($page <= 1 ? 'disabled' : '').">Atrás</button></a>";
            // Si no hay más resultados, deshabilita el botón de Siguiente
            echo "<a href='?q=$search_query&type=$type&order=$order&page=$nextPage'><button ".($total_rows <= $page * $limit ? 'disabled' : '').">Siguiente</button></a>";
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