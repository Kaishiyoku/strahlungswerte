import {collapse, expand, toggle} from 'transition-height';
import {fromEvent} from 'rxjs';
import {throttleTime, debounceTime, map, pairwise, startWith} from 'rxjs/operators';
import getTailwindConfig from './getTailwindConfig';

function navbarCollapser() {
    const fullTailwindConfig = getTailwindConfig();

    const navbarControl = document.querySelector('[data-navbar-control]');
    const navbarContent = document.querySelector('[data-navbar-content]');
    const lgBreakpoint = parseInt(fullTailwindConfig.theme.screens.lg, 10);

    if (window.innerWidth < lgBreakpoint) {
        collapse(navbarContent);
        navbarContent.classList.remove('hidden');
    } else {
        navbarContent.classList.remove('hidden');
        expand(navbarContent);
    }

    fromEvent(navbarControl, 'click')
        .pipe(throttleTime(100))
        .subscribe(() => toggle(navbarContent));

    fromEvent(window, 'resize')
        .pipe(
            startWith({currentTarget: window}),
            map((event) => event.currentTarget.window.innerWidth),
            debounceTime(200),
            pairwise()
        )
        .subscribe(([previousData, currentData]) => {
            if (previousData >= lgBreakpoint && currentData < lgBreakpoint) {
                collapse(navbarContent);
            } else if (previousData < lgBreakpoint && currentData >= lgBreakpoint) {
                expand(navbarContent);
            }
        });
}

export default navbarCollapser;
