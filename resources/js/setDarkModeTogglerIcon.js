function setDarkModeTogglerIcon() {
    const mode = localStorage.getItem('theme');
    const darkModeTogglerElement = document.querySelector('[data-toggle="dark-mode"]');

    console.log(darkModeTogglerElement);

    if (!mode) {
        darkModeTogglerElement.innerHTML = '<i class="fas fa-adjust"></i>';
    } else if (mode === 'dark') {
        darkModeTogglerElement.innerHTML = '<i class="fas fa-moon"></i>';
    } else if (mode === 'light') {
        darkModeTogglerElement.innerHTML = '<i class="fas fa-sun"></i>';
    }
}

export default setDarkModeTogglerIcon;
