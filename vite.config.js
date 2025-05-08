import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

const disableHash = process.env.NO_HASH === 'true';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
    build: {
        outDir: 'dist',
        rollupOptions: {
            output: {
                entryFileNames: disableHash ? 'assets/[name].js' : 'assets/[name].[hash].js',
                chunkFileNames: disableHash ? 'assets/[name].js' : 'assets/[name].[hash].js',
                assetFileNames: disableHash ? 'assets/[name][extname]' : 'assets/[name].[hash][extname]',
            },
        },
    },
});
