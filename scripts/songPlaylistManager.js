document.addEventListener("DOMContentLoaded", function() {
    // Obtener todas las etiquetas de audio en la página
    const audioElements = document.querySelectorAll('audio');

    // Función para detener todos los audios excepto el dado
    function stopAllExcept(currentAudio) {
        audioElements.forEach(audio => {
            if (audio !== currentAudio) {
                audio.pause();
            }
        });
    }

    // Reproducir la primera canción al cargar la página
    if (audioElements.length > 0) {
        const firstAudio = audioElements[0];
        firstAudio.setAttribute("autoplay", "");

        audioElements.forEach(audio => {
            audio.addEventListener('ended', function() {
                const currentIndex = Array.from(audioElements).indexOf(audio);
                const nextIndex = currentIndex + 1;
                if (nextIndex < audioElements.length) {
                    audioElements.forEach(audio => audio.currentTime = 0);
                    const nextAudio = audioElements[nextIndex];
                    stopAllExcept(nextAudio);
                    nextAudio.play();
                }
            });
        });
    }

    // Detener otros audios al hacer clic en uno nuevo
    audioElements.forEach(audio => {
        audio.addEventListener('play', function() {
            stopAllExcept(audio);
        });
    });
    
});
