import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
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
                host: env.VITE_HMR_HOST || 'localhost',
            },
        },
        build: {
            outDir: 'public/build',
        },
    };
});
