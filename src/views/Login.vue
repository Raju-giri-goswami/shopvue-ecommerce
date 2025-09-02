<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-fade-in">
      <!-- Header -->
      <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl mx-auto mb-6 flex items-center justify-center">
          <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
          Welcome back
        </h2>
        <p class="text-gray-600">
          Sign in to your account to continue shopping
        </p>
      </div>
      
      <!-- Login Form -->
      <div class="card p-8">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email address
            </label>
            <input
              id="email"
              name="email"
              type="email"
              required
              v-model="form.email"
              class="input-field"
              placeholder="Enter your email"
            />
          </div>
          
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              required
              v-model="form.password"
              class="input-field"
              placeholder="Enter your password"
            />
          </div>

          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="text-sm text-red-600">{{ error }}</p>
            </div>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading"
            class="w-full btn-primary py-4 text-lg disabled:opacity-50"
          >
            <span v-if="authStore.loading">Signing in...</span>
            <span v-else>Sign In</span>
          </button>
        </form>
        
        <div class="mt-6 text-center">
          <p class="text-gray-600">
            Don't have an account?
            <router-link 
              to="/register" 
              class="font-medium text-blue-600 hover:text-blue-500 transition-colors"
            >
              Create one here
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const error = ref('')

const handleSubmit = async () => {
  error.value = ''
  
  const result = await authStore.signIn(form.email, form.password)
  
  if (result.success) {
    router.push('/')
  } else {
    error.value = result.error
  }
}
</script>