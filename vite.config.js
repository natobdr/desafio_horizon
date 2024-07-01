import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import "jquery";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: [
            '@fortawesome/fontawesome-free/css/all.min.css',
        ],
    },
    build: {
        rollupOptions: {
            external: ['jquery'], // Indica que jQuery é um módulo externo
            output: {
                globals: {
                    jquery: 'jQuery', // Indica o nome global para jQuery
                },
            },
        },
    },
});
