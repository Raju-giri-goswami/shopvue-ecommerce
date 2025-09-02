import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

interface UserProfile {
  id: string
  user_id: string
  full_name: string
  is_admin: boolean
}

interface User {
  id: string
  email: string
  profile?: UserProfile
}

const API_BASE = '/backend'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const loading = ref(false)

  const isAuthenticated = computed(() => !!user.value)
  const isAdmin = computed(() => user.value?.profile?.is_admin || false)

  const signUp = async (email: string, password: string, fullName: string) => {
    loading.value = true
    try {
      console.log('Attempting registration with:', { email, fullName }); // Debug log
      const response = await axios.post(`${API_BASE}/auth/register.php`, {
        email,
        password,
        name: fullName
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      })

      if (response.data.error) {
        throw new Error(response.data.error)
      }

      // Generate new user ID for the new user
      const newUserId = 'auth_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
      localStorage.setItem('shopvue_user_id', newUserId)
      
      // Initialize cart for new user
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      await cartStore.onUserLogin(newUserId, true)

      return { success: true, message: response.data.message }
    } catch (error: any) {
      console.error('Registration error:', error.response || error); // Debug log
      return { 
        success: false, 
        error: error.response?.data?.error || error.message || 'Registration failed'
      }
    } finally {
      loading.value = false
    }
  }

  const signIn = async (email: string, password: string) => {
    loading.value = true
    try {
      const response = await axios.post(`${API_BASE}/auth/login.php`, {
        email,
        password
      })

      if (response.data.error) {
        throw new Error(response.data.error)
      }

      // Transform the user data to match the expected interface
      user.value = {
        id: response.data.user.id,
        email: response.data.user.email,
        profile: {
          id: response.data.user.id,
          user_id: response.data.user.id,
          full_name: response.data.user.name,
          is_admin: response.data.user.role === 'admin'
        }
      }
      
      // Generate new user ID for authenticated user
      const newUserId = 'auth_' + response.data.user.id + '_' + Date.now()
      localStorage.setItem('shopvue_user_id', newUserId)
      
      // Load user's cart data
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      await cartStore.onUserLogin(newUserId, true)
      
      return { success: true }
      
      return { success: true }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.error || error.message 
      }
    } finally {
      loading.value = false
    }
  }

  const signOut = async () => {
    try {
      await axios.post(`${API_BASE}/auth/logout.php`)
      user.value = null
      
      // Clear cart and user session
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      cartStore.onUserLogout()
      
      return { success: true }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.message || error.message 
      }
    }
  }

  const getSession = async () => {
    try {
      const response = await axios.get(`${API_BASE}/auth/user.php`)
      // Handle the demo backend response
      if (response.data.success === false) {
        user.value = null
        // Update cart store authentication status
        const { useCartStore } = await import('./cart')
        const cartStore = useCartStore()
        cartStore.setAuthenticated(false)
      } else {
        user.value = response.data.user
        // Update cart store authentication status
        const { useCartStore } = await import('./cart')
        const cartStore = useCartStore()
        cartStore.setAuthenticated(true)
      }
    } catch (error: any) {
      console.log('Session check failed (expected in demo):', error?.message || 'Unknown error')
      user.value = null
      // Update cart store authentication status
      const { useCartStore } = await import('./cart')
      const cartStore = useCartStore()
      cartStore.setAuthenticated(false)
    }
  }

  // Initialize session
  getSession()

  return {
    user,
    loading,
    isAuthenticated,
    isAdmin,
    signUp,
    signIn,
    signOut,
    getSession
  }
})