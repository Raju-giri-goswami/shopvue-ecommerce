<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <AppHeader />
    <main class="flex-grow">
      <router-view />
    </main>
    <AppFooter />
    <NotificationToast />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
import NotificationToast from './components/NotificationToast.vue'
import { useAuthStore } from './stores/auth'
import { useCartStore } from './stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()

onMounted(async () => {
  await authStore.getSession()
  if (authStore.isAuthenticated) {
    cartStore.fetchCartItems()
  }
})
</script>