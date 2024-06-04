// Enlazar la función de cambio de tema a los botones
document.addEventListener("DOMContentLoaded", function() {
    const themeToggleButtons = document.querySelectorAll('.theme-toggle');
    let availableThemes = ['dark', 'light', 'darkGreen', 'lightGreen']; // Define los temas disponibles - IR ACTUALIZANDO

    themeToggleButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado de redireccionamiento

            let newTheme = this.getAttribute('data'); // Obtener el tema del atributo 'data' del botón

            // Verificar si el tema obtenido es válido, si no, establecer "dark" como predeterminado
            if (!availableThemes.includes(newTheme)) {
                newTheme = 'dark';
            }

            localStorage.setItem('theme', newTheme); // Guardar el nuevo tema en localStorage
            location.reload(); // Recargar la página
        });
    });
});
