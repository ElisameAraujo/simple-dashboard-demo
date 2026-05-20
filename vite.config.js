import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/admin/admin.css', 'resources/js/admin/admin.js', 'resources/css/web/web.css', 'resources/js/web/web.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
