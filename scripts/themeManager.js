// localStorage: Usa el tema guardado en el navegador
function fetchTheme() {
    const theme = localStorage.getItem("theme");
    return theme ? theme : "dark"; // Return the theme from localStorage, defaulting to "dark" if not set
}

// Funcion: Aplica el tema
function applyTheme(theme) {
    const themePath = theme.toLowerCase() + 'Theme'; // darkTheme, lightTheme...
    const iconsPath = `/styles/themes/${themePath}`;

    applyIconsPath(iconsPath);

    const themeStyleLink = document.getElementById('theme-style');
    themeStyleLink.href = `/styles/themes/${themePath}/theme.css`;
}

// Funcion: Spinner de carga
function showSpinner() {
    const spinner = document.getElementById('loading');
    loading.setAttribute('src', `${path}/loading.gif`);
}

// Aplicar iconos
function applyIconsPath(path) {
    const icons = document.querySelectorAll('.icon');
    icons.forEach(icon => {
        const iconName = icon.getAttribute('data-icon');
        icon.setAttribute('src', `${path}/${iconName}.svg`);
    });
}


// Aplicar tema cuando carga la pagina
document.addEventListener("DOMContentLoaded", function() {
    const currentTheme = fetchTheme();
    applyTheme(currentTheme);
});