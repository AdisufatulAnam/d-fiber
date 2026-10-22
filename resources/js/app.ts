import { createInertiaApp } from '@inertiajs/vue3';
import { vReveal } from './directives/reveal';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    withApp: (app) => {
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
        app.directive('reveal', vReveal);
    },
    progress: {
        color: '#4B5563',
    },
});
