import '../css/app.scss';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';

createInertiaApp({
    resolve: (name: string) => {
        const pages = import.meta.glob('./pages/**/*.vue', {
            eager: true,
        }) as Record<string, { default: any }>;

        const page = pages[`./pages/${name}.vue`];

        if (!page) {
            throw new Error(`Page not found: ./pages/${name}.vue`);
        }

        return page.default;
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .mount(el);
    },
});
