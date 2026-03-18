import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

async function resolvePwaPlugin() {
    try {
        const { VitePWA } = await import('vite-plugin-pwa');

        return VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'robots.txt', 'apple-touch-icon.png'],
            manifest: {
                name: 'Foodly',
                short_name: 'Foodly',
                description: 'Aplicacion de recetas PWA',
                theme_color: '#ffffff',
                background_color: '#ffffff',
                display: 'standalone',
                scope: '/',
                start_url: '/',
                icons: [
                    {
                        src: '/icons/icons-192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/icons/icons-512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            }
        });
    } catch (error) {
        console.warn('vite-plugin-pwa no se pudo cargar; Vite arrancara sin soporte PWA.', error.message);
        return null;
    }
}

export default defineConfig(async () => {
    const pwaPlugin = await resolvePwaPlugin();

    return {
        plugins: [
            laravel({
                input: [
                    'resources/sass/app.scss',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
            ...(pwaPlugin ? [pwaPlugin] : []),
        ],
    };
});
