document.addEventListener("DOMContentLoaded", function() {
    // Obtener la imagen de búsqueda por su ID
    const searchIcon = document.getElementById("searchIco");

    // Obtener el campo de entrada de búsqueda por su ID
    const searchInput = document.getElementById("searchInput");

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