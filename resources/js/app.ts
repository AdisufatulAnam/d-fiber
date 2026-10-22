import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import GlobalLoading from './components/GlobalLoading.vue';
import { vReveal } from './directives/reveal';
import { initGlobalLoading } from './lib/global-loading';

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

        // Overlay loading global: tampilkan saat buka web pertama kali,
        // login, dan setiap perpindahan menu/halaman.
        initGlobalLoading();
        const loadingHost = document.createElement('div');
        document.body.appendChild(loadingHost);
        createApp({ render: () => h(GlobalLoading) }).mount(loadingHost);
    },
    // Ganti progress bar bawaan Inertia dengan overlay spinner global.
    progress: false,
});
