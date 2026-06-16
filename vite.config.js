import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

const aliases = {
    '$components': path.resolve('./resources/js/components'),
    '$layouts': path.resolve('./resources/js/layouts'),
    '$lib': path.resolve('./resources/js/lib'),
    '$stores': path.resolve('./resources/js/stores'),
    '$types': path.resolve('./resources/js/types'),
    '$pages': path.resolve('./resources/js/pages'),
    '$styles': path.resolve('./resources/css'),
};

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.ts'],
            refresh: true,
        }),
        vue(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                loadPaths: [path.resolve('./resources/css')],
            },
        },
    },
    resolve: {
        alias: aliases,
    },
});
