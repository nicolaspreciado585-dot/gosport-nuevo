import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separa dependencias grandes en archivos individuales
                    'vendor-laravel': ['laravel-vite-plugin'],
                    'vendor-alpine': ['alpinejs'],
                    'vendor-chart': ['chart.js'],
                    'vendor-datatables': [
                        'datatables.net',
                        'datatables.net-buttons',
                        'datatables.net-buttons/js/buttons.html5.js',
                        'datatables.net-buttons/js/buttons.print.js',
                        'jszip',
                        'pdfmake/build/pdfmake',
                        'pdfmake/build/vfs_fonts'
                    ],
                   
                },
            },
        },
        chunkSizeWarningLimit: 1000, // evita warning por chunks grandes
    },
});
