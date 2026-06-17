import '../css/app.scss';
import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

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
        const pinia = createPinia();

        router.on('start', () => {
            NProgress.start();
        });

        router.on('finish', () => {
            NProgress.done();
        });

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
});
