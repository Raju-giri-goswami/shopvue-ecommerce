import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  base: '/shopvue-ecommerce/', // 👈 This is crucial for GitHub Pages
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost/ShopVue/backend/api',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '')
      },
      '/backend': {
        target: 'http://localhost/ShopVue/backend',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/backend/, '')
      },
      '/images': {
        target: 'http://localhost/ShopVue',
        changeOrigin: true
      }
    }
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
  }
})
