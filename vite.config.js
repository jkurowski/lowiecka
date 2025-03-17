import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/index.scss'], // Your SCSS file
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Add global SCSS variables or mixins if needed
                // additionalData: `@import "./resources/scss/variables.scss";`
            }
        }
    },
    build: {
        outDir: 'public', // Output the build to the 'public' folder
        assetsDir: 'css', // Place CSS files inside the 'css' folder
        rollupOptions: {
            output: {
                assetFileNames: 'css/styles.css', // Output the CSS to 'public/css/style.css'
            }
        }
    }
});