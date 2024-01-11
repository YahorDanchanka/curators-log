import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'

export default defineConfig({
  plugins: [
    vue({
      template: { transformAssetUrls },
      script: {
        defineModel: true,
      },
    }),
    quasar({
      sassVariables: 'resources/css/quasar-variables.sass',
    }),
    laravel({
      input: ['resources/css/app.sass', 'resources/js/app.ts'],
      refresh: true,
    }),
  ],
  server: {
    host: '0.0.0.0',
    hmr: {
      host: 'localhost',
    },
    watch: {
      usePolling: true,
    },
  },
})
