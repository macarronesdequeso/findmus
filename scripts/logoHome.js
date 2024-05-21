document.addEventListener("DOMContentLoaded", function() {
    // Obtener la imagen de búsqueda por su ID
    const searchIcon = document.getElementById("logoHome");

    // Agregar un evento de escucha para el clic en el logo
    searchIcon.addEventListener("click", function() {
        // Redirigir a la página de inicio
        window.location.href = "/";
    });

});