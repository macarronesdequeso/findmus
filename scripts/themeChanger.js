// Enlazar la función de cambio de tema a los botones
document.addEventListener("DOMContentLoaded", function() {
    const themeToggleButtons = document.querySelectorAll('.theme-toggle');

    themeToggleButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado de redireccionamiento

            const newTheme = this.getAttribute('data'); // Obtener el tema del atributo 'data' del botón
            localStorage.setItem('theme', newTheme); // Guardar el nuevo tema en localStorage
            location.reload(); // Recargar la página
        });
    });
});
