import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/normalize.css', 'resources/css/app.css', 'resources/js/app.js',
                'resources/css/test-vite.css', 'resources/js/test-vite.js','resources/js/vinyl.js'],
            refresh: true,
        }),
    tailwindcss(),
    ],
});