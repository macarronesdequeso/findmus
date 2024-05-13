document.addEventListener("DOMContentLoaded", function() {
    // Obtener todas las etiquetas de audio
    var audioElements = document.querySelectorAll("audio");

    // Añadir un evento 'ended' a cada elemento de audio
    audioElements.forEach(function(audio) {
        audio.addEventListener("ended", function() {
            // Obtener el siguiente elemento de audio en la lista
            var nextAudio = audio.nextElementSibling;

            // Verificar si hay un siguiente elemento de audio
            if (nextAudio && nextAudio.tagName.toLowerCase() === "audio") {
                // Reproducir el siguiente audio
                nextAudio.play();
            }
        });
    });
});
