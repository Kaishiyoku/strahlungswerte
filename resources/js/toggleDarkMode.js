import addDarkModeClass from "./addDarkModeClass";
import removeDarkModeClass from "./removeDarkModeClass";
import setDarkModeTogglerIcon from "./setDarkModeTogglerIcon";

function toggleDarkMode() {
    const mode = localStorage.getItem('theme');

    if (!mode) {
        localStorage.setItem('theme', 'dark');

        addDarkModeClass();
    } else if (mode === 'dark') {
        localStorage.setItem('theme', 'light');

        removeDarkModeClass();
    } else if (mode === 'light') {
        localStorage.removeItem('theme');

        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            localStorage.setItem('theme', 'dark');
            addDarkModeClass();
        }
    }

    setDarkModeTogglerIcon();
}

export default toggleDarkMode;
