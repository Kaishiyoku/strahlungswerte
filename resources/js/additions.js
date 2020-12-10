import tippy from 'tippy.js';
import toggleDarkMode from "./toggleDarkMode";
import navbarCollapser from "./navbarCollapser";
import setDarkModeTogglerIcon from "./setDarkModeTogglerIcon";

$(document).ready(function () {
    setDarkModeTogglerIcon();

    $('[data-toggle="dark-mode"]').on('click', function (event) {
        toggleDarkMode();
    });

    $('[data-confirm]').on('click', function () {
        let confirmationText = $(this).attr('data-confirm');

        if (_.isEmpty(confirmationText) || confirmationText == 1) {
            confirmationText = 'Sind Sie sicher?';
        }

        if (!confirm(confirmationText)) {
            return false;
        }
    });

    $('[data-click]').each(function () {
        let $this = $(this);

        $this.on('click', function (event) {
            event.preventDefault();

            $($this.attr('data-click')).submit();
        });
    });

    $('[data-provide="tablesorter"]').each(function () {
        $(this).tablesorter({
            theme: 'bootstrap-custom',
            widgets: ['columns'],
            widgetOptions: {
                columns: ['primary', 'secondary', 'tertiary']
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
