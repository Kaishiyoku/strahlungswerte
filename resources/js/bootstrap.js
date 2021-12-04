import toggleDarkMode from "./toggleDarkMode";
import setDarkModeTogglerIcon from "./setDarkModeTogglerIcon";
import onDomReady from "./onDomReady";
import {Chart} from 'frappe-charts';

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

const withDefault = (defaultValue, value) => value || defaultValue;

window.withDefault = withDefault;

window.renderChart = (querySelector, title, data, height = 250, colors = ['#7cd6fd', '#743ee2']) => {
    new Chart(querySelector, {
        title,
        data,
        type: 'axis-mixed',
        height,
        colors,
        lineOptions: {
            regionFill: 1,
            dotSize: -1,
        },
        axisOptions: {
            xIsSeries: true,
        },
    });
};

onDomReady(() => {
    setDarkModeTogglerIcon();

    document.querySelectorAll('[data-toggle="dark-mode"]').forEach((element) => {
        element.addEventListener('click', (event) => {
            toggleDarkMode();
        });
    });
});
