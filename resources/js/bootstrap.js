import tippy from 'tippy.js';
import toggleDarkMode from "./toggleDarkMode";
import navbarCollapser from "./navbarCollapser";
import setDarkModeTogglerIcon from "./setDarkModeTogglerIcon";
import onDomReady from "./onDomReady";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

onDomReady(() => {
    setDarkModeTogglerIcon();

    document.querySelectorAll('[data-toggle="dark-mode"]').forEach((element) => {
        element.addEventListener('click', (event) => {
            toggleDarkMode();
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', function () {
            let confirmationText = element.getAttribute('data-confirm');

            if (!confirmationText) {
                confirmationText = 'Sind Sie sicher?';
            }

            if (!confirm(confirmationText)) {
                return false;
            }
        });
    });

    tippy('[data-provide-dropdown]', {
        theme: 'dropdown',
        allowHTML: true,
        interactive: true,
        arrow: 'false',
        trigger: 'click',
        placement: 'bottom-start',
        offset: [0, -5],
        animation: 'shift-away-subtle',
        content(reference) {
            let dropdown = document.querySelector(reference.getAttribute('data-dropdown-target'));
            dropdown.classList.remove('hidden');

            return dropdown;
        },
    });

    navbarCollapser();
});
