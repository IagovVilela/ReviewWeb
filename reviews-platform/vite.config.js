import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/marketing.css',
                'resources/js/app.js',
                'resources/js/home-entry.jsx',
                'resources/js/review-entry.jsx',
            ],
            refresh: true,
        }),
    ],
});
