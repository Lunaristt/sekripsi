import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    base: '/public/', // 👉 Tambahkan base path agar asset diakses dari /public/build/
    build: {
        outDir: 'public/build', // 👉 Pastikan hasil build tetap masuk ke folder public/build
        manifest: true,
        emptyOutDir: true, // Bersihkan sebelum build ulang
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
})
