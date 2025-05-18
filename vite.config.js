import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

const disableHash = process.env.NO_HASH === 'true';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/style.css'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    build: {
        outDir: 'dist',
        rollupOptions: {
            manualChunks: undefined,
            output: {
                entryFileNames: disableHash
                    ? 'assets/app.js'
                    : 'assets/app.[hash].js',
                chunkFileNames: disableHash
                    ? 'assets/[name].js'
                    : 'assets/[name].[hash].js',
                assetFileNames: disableHash
                    ? 'assets/[name][extname]'
                    : 'assets/[name].[hash][extname]',
            },
        },
    },
});
