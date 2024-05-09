document.addEventListener("DOMContentLoaded", function() {
    // Obtener la imagen de perfil por su ID
    const profileIcon = document.getElementById("profileIco");

    // Obtener la imagen de búsqueda por su ID
    const searchIcon = document.getElementById("searchIco");

    // Obtener el campo de entrada de búsqueda por su ID
    const searchInput = document.getElementById("searchInput");

    // Obtener el elemento del logo por su ID
    const logoHome = document.getElementById("logoHome");

    // Agregar un evento de escucha para el clic en el logo
    logoHome.addEventListener("click", function() {
        // Redirigir al index
        window.location.href = "/";
    });

    // Agregar un evento de escucha para el clic en la imagen de perfil
    profileIcon.addEventListener("click", function() {
        window.location.href = "login";
    });

    // Agregar un evento de escucha para el clic en la imagen de búsqueda
    searchIcon.addEventListener("click", function() {
        // Obtener el valor del campo de entrada de búsqueda
        const searchQuery = searchInput.value;

        // Redirigir a la página de búsqueda con el término de búsqueda como parámetro de consulta en la URL
        window.location.href = "search?search=" + encodeURIComponent(searchQuery);
    });

    // Agregar un evento de escucha para el evento "keypress" en el campo de entrada de búsqueda
    searchInput.addEventListener("keypress", function(event) {
        // Verificar si se presionó la tecla "Enter" (cuyo código es 13)
        if (event.key === "Enter") {
            // Obtener el valor del campo de entrada de búsqueda
            const searchQuery = searchInput.value;

            // Redirigir a la página de búsqueda con el término de búsqueda como parámetro de consulta en la URL
            window.location.href = "search?search=" + encodeURIComponent(searchQuery);
        }
    });
});