import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import { PrimeVueResolver } from "@primevue/auto-import-resolver";
import Components from "unplugin-vue-components/vite";
import path from "path"; // 👈 Import path for alias resolution

export default defineConfig({
    plugins: [
        vue(),
        Components({
            resolvers: [PrimeVueResolver()],
        }),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
            "@public": path.resolve(__dirname, "public")
        }
    },
    // Docker/Sail compatibility
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
});
