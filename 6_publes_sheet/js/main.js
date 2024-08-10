const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;

let isDarkMode = false;

darkModeToggle.addEventListener('click', () => {
    isDarkMode = !isDarkMode;
    if (isDarkMode) {
        body.classList.add('dark-mode');
    } else {
        body.classList.remove('dark-mode');
    }
});

// ----------------------------------------------------------------------------------
