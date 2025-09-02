<template>
  <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
              </svg>
            </div>
            <span class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
              ShopVue
            </span>
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-8">
          <router-link 
            to="/" 
            class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-blue-600"
            active-class="text-blue-600 border-blue-600"
          >
            Home
          </router-link>
          <router-link 
            to="/products" 
            class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-blue-600"
            active-class="text-blue-600 border-blue-600"
          >
            Products
          </router-link>
          <router-link 
            to="/about" 
            class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-blue-600"
            active-class="text-blue-600 border-blue-600"
          >
            About
          </router-link>
          <router-link 
            to="/contact" 
            class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-blue-600"
            active-class="text-blue-600 border-blue-600"
          >
            Contact
          </router-link>
          <router-link 
            v-if="authStore.isAuthenticated"
            to="/dashboard" 
            class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 py-2 px-1 border-b-2 border-transparent hover:border-blue-600"
            active-class="text-blue-600 border-blue-600"
          >
            Dashboard
          </router-link>
        </nav>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
          <!-- Cart -->
          <router-link 
            to="/cart" 
            class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 hover:bg-blue-50 rounded-lg"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"/>
            </svg>
            <span 
              v-if="cartStore.totalItems > 0"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium animate-bounce-in"
            >
              {{ cartStore.totalItems }}
            </span>
          </router-link>

          <!-- User menu -->
          <div v-if="authStore.isAuthenticated" class="relative">
            <button 
              @click="userMenuOpen = !userMenuOpen"
              class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-200 p-2 hover:bg-blue-50 rounded-lg"
            >
              <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                  {{ (authStore.user?.profile?.full_name || 'U').charAt(0).toUpperCase() }}
                </span>
              </div>
              <span class="hidden sm:block font-medium">{{ authStore.user?.profile?.full_name || 'User' }}</span>
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-if="userMenuOpen"
              class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-lg bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none animate-scale-in"
            >
              <router-link 
                to="/dashboard" 
                @click="userMenuOpen = false"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
              >
                My Orders
              </router-link>
              <router-link 
                v-if="authStore.isAdmin"
                to="/admin" 
                @click="userMenuOpen = false"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
              >
                Admin Dashboard
              </router-link>
              <hr class="my-1">
              <button 
                @click="handleSignOut"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
              >
                Sign Out
              </button>
            </div>
          </div>

          <!-- Login/Register -->
          <div v-else class="flex items-center space-x-3">
            <router-link 
              to="/login" 
              class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200 px-3 py-2 hover:bg-blue-50 rounded-lg"
            >
              Login
            </router-link>
            <router-link 
              to="/register" 
              class="btn-primary"
            >
              Register
            </router-link>
          </div>

          <!-- Mobile menu button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 text-gray-600 hover:text-blue-600 transition-colors hover:bg-blue-50 rounded-lg"
          >
            <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white animate-slide-up">
        <nav class="px-4 py-4 space-y-3">
          <router-link 
            to="/" 
            @click="mobileMenuOpen = false"
            class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
          >
            Home
          </router-link>
          <router-link 
            to="/products" 
            @click="mobileMenuOpen = false"
            class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
          >
            Products
          </router-link>
          <router-link 
            to="/about" 
            @click="mobileMenuOpen = false"
            class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
          >
            About
          </router-link>
          <router-link 
            to="/contact" 
            @click="mobileMenuOpen = false"
            class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
          >
            Contact
          </router-link>
          <div v-if="authStore.isAuthenticated" class="border-t pt-3 mt-3">
            <router-link 
              to="/dashboard" 
              @click="mobileMenuOpen = false"
              class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
            >
              My Orders
            </router-link>
            <router-link 
              v-if="authStore.isAdmin"
              to="/admin" 
              @click="mobileMenuOpen = false"
              class="block text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
            >
              Admin Dashboard
            </router-link>
            <button 
              @click="handleSignOut"
              class="block w-full text-left text-gray-600 hover:text-blue-600 font-medium transition-colors py-2"
            >
              Sign Out
            </button>
          </div>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)

const handleSignOut = async () => {
  await authStore.signOut()
  userMenuOpen.value = false
  mobileMenuOpen.value = false
}

// Close menus when clicking outside & fetch cart items
onMounted(() => {
  document.addEventListener('click', (e: MouseEvent) => {
    const target = e.target as HTMLElement
    if (!target?.closest('.relative')) {
      userMenuOpen.value = false
    }
  })
  
  // Refresh cart items when the header mounts
  console.log('Header mounted, refreshing cart items')
  cartStore.fetchCartItems()
})

onUnmounted(() => {
  document.removeEventListener('click', () => {
    userMenuOpen.value = false
  })
})
</script>