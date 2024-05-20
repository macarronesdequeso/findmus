document.addEventListener("DOMContentLoaded", function() {
    // Obtener la imagen de perfil por su ID
    const profileIcon = document.getElementById("profileIco");

    // Obtener la imagen de búsqueda por su ID
    const searchIcon = document.getElementById("searchIco");

    // Obtener el campo de entrada de búsqueda por su ID
    const searchInput = document.getElementById("searchInput");

    // Obtener los datos de sesión desde los atributos `data-*` del elemento `body`
    const bodyElement = document.body;
    const userId = bodyElement.dataset.userId;

    if (userId >= 0) {
        // Construir la ruta de la imagen de perfil
        const profilePicturePath = "/users/" + userId + ".png";

        // Verificar si el archivo de imagen existe
        fetch(profilePicturePath, { method: 'HEAD' })
            .then(response => {
                if (response.ok) {
                    // Establecer la ruta de la imagen de perfil como la fuente de la etiqueta <img>
                    profileIcon.src = profilePicturePath;
                    profileIcon.classList.add("profile"); // Agregar la clase "profile"

                    // Agregar un evento de escucha para el clic en la imagen de perfil
                    profileIcon.addEventListener("click", function() {
                        // Redirigir a la página de preferencias
                        window.location.href = "preferences";
                    });

                    console.log("OK - ID de usuario: " + userId + ", imagen de perfil encontrada.");
                } else {
                    console.log("ERROR - Imagen de perfil no encontrada para el ID de usuario: " + userId);
                }
            })
            .catch(error => {
                console.log("ERROR - Error al verificar la imagen de perfil: ", error);
            });
    } else {
        // Si el ID de usuario no está disponible, redirigir a la página de inicio de sesión
        profileIcon.addEventListener("click", function() {
            window.location.href = "login";
        });

        console.log("ERROR - ID de usuario no disponible");
    }

    // Agregar un evento de escucha para el clic en la imagen de búsqueda
    searchIcon.addEventListener("click", function() {
        // Obtener el valor del campo de entrada de búsqueda
        const searchQuery = searchInput.value;

        // Redirigir a la página de búsqueda con el término de búsqueda como parámetro de consulta en la URL
        window.location.href = "search?q=" + encodeURIComponent(searchQuery);
    });

    // Agregar un evento de escucha para el evento "keypress" en el campo de entrada de búsqueda
    searchInput.addEventListener("keypress", function(event) {
        // Verificar si se presionó la tecla "Enter" (cuyo código es 13)
        if (event.key === "Enter") {
            // Obtener el valor del campo de entrada de búsqueda
            const searchQuery = searchInput.value;

            // Redirigir a la página de búsqueda con el término de búsqueda como parámetro de consulta en la URL
            window.location.href = "search?q=" + encodeURIComponent(searchQuery);
        }
    });
});