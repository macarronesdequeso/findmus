// localStorage: Usa el tema guardado en el navegador
function fetchTheme() {
    const theme = localStorage.getItem("theme");
    return theme ? theme : "dark"; // Return the theme from localStorage, defaulting to "dark" if not set
}

// Funcion: Aplica el tema
function applyTheme(theme) {
    const themePath = theme.toLowerCase() + 'Theme'; // darkTheme, lightTheme...
    
    // Variable para almacenar el nombre del tema modificado
    let modifiedThemePath;

    if (!themePath.includes("theme")) {
        if (themePath.includes("dark")) modifiedThemePath = "darkTheme";
        else if (themePath.includes("light")) modifiedThemePath = "lightTheme";
    }
    
    const iconsPath = `/styles/themes/${modifiedThemePath}`;
    applyIconsPath(iconsPath);

    const themeStyleLink = document.getElementById('theme-style');
    themeStyleLink.href = `/styles/themes/${themePath}/theme.css`;
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