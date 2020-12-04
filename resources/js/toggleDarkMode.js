import addDarkModeClass from "./addDarkModeClass";
import removeDarkModeClass from "./removeDarkModeClass";

function toggleDarkMode() {
    axios.put('/api/update_dark_mode_status').then(({data}) => {
        const {mode} = data;

        if (!mode) {
            localStorage.removeItem('theme');
            $('[data-toggle="dark-mode"]').html('<i class="fas fa-adjust"></i>');

            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                localStorage.setItem('theme', 'dark');
                addDarkModeClass();
            }
        } else if (mode === 'dark') {
            localStorage.setItem('theme', 'dark');
            $('[data-toggle="dark-mode"]').html('<i class="fas fa-moon"></i>');

            addDarkModeClass();
        } else if (mode === 'light') {
            localStorage.setItem('theme', 'light');
            $('[data-toggle="dark-mode"]').html('<i class="fas fa-sun"></i>');

            removeDarkModeClass();
        }
    });
}

export default toggleDarkMode;
