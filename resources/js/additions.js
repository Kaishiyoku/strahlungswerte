import tippy from 'tippy.js';
import toggleDarkMode from "./toggleDarkMode";
import navbarCollapser from "./navbarCollapser";
import setDarkModeTogglerIcon from "./setDarkModeTogglerIcon";
import onDomReady from "./onDomReady";
import {v4} from 'uuid';

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

            if (_.isEmpty(confirmationText) || confirmationText == 1) {
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
