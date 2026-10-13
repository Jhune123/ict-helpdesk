import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Exposes Vite on your local network
        cors: true,      // Prevents CORS errors on mobile browsers
        hmr: {
            host: '172.168.14.70', // Connects Vite HMR directly to your PC's IP
        },
    },
    build: {
        outDir: 'public/build',
    },
});