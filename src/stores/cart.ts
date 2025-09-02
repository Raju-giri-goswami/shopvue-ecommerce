import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import type { Product } from './products'

const API_BASE = '/api'

// Configure axios to include credentials for session handling
axios.defaults.withCredentials = true

export interface CartItem {
  id: string
  user_id: string
  product_id: string
  quantity: number
  created_at: string
  updated_at: string
  product?: Product
}

export const useCartStore = defineStore('cart', () => {
  const cartItems = ref<CartItem[]>([])
  const loading = ref(false)
  const userId = ref<string>('')
  const isAuthenticated = ref(false)

  // Helper function to get user-specific localStorage key
  const getUserCartKey = (userId: string) => `shopvue_cart_${userId}`

  // Save cart to localStorage for current user
  const saveCartToStorage = () => {
    if (userId.value) {
      const cartKey = getUserCartKey(userId.value)
      localStorage.setItem(cartKey, JSON.stringify(cartItems.value))
    }
  }

  // Load cart from localStorage for current user
  const loadCartFromStorage = () => {
    if (userId.value) {
      const cartKey = getUserCartKey(userId.value)
      const storedCart = localStorage.getItem(cartKey)
      if (storedCart) {
        try {
          cartItems.value = JSON.parse(storedCart)
        } catch (error) {
          console.error('Error parsing stored cart:', error)
          cartItems.value = []
        }
      } else {
        cartItems.value = []
      }
    }
  }

  // Clear cart for current user
  const clearUserCart = () => {
    if (userId.value) {
      const cartKey = getUserCartKey(userId.value)
      localStorage.removeItem(cartKey)
    }
    cartItems.value = []
  }

  // Sync authentication status (called by auth store)
  const setAuthenticated = (auth: boolean) => {
    isAuthenticated.value = auth
    if (auth && userId.value && userId.value.startsWith('auth_')) {
      // If user is authenticated and has auth user ID, try to fetch from backend
      fetchCartItems()
    }
  }

  // Initialize user ID and determine if authenticated
  const initializeUserId = () => {
    if (!userId.value) {
      // Try to get from localStorage first
      const storedUserId = localStorage.getItem('shopvue_user_id')
      if (storedUserId) {
        // Check if user ID has changed (indicating new session)
        if (userId.value && userId.value !== storedUserId) {
          console.log('User ID changed, clearing cart for new session')
          clearUserCart() // Clear cart items for new user/session
        }
        userId.value = storedUserId
        isAuthenticated.value = storedUserId.startsWith('auth_')
        // Don't load from localStorage here - let fetchCartItems() handle it
      } else {
        // Generate new user ID for guest
        userId.value = 'guest_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
        localStorage.setItem('shopvue_user_id', userId.value)
        isAuthenticated.value = false
        cartItems.value = [] // Start with empty cart for new guest
      }
    }
  }

  const totalItems = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
  })

  const totalPrice = computed(() => {
    return cartItems.value.reduce((sum, item) => {
      return sum + (item.product?.price || 0) * item.quantity
    }, 0)
  })

  const fetchCartItems = async () => {
    loading.value = true
    try {
      initializeUserId()

      // Try to fetch from backend first for both authenticated and guest users
      console.log('Fetching cart items from backend for user:', userId.value)
      const response = await axios.get(`${API_BASE}/cart/list.php?t=${Date.now()}&user_id=${userId.value}`)

      if (response.data.success && response.data.data) {
        cartItems.value = response.data.data
        console.log('Loaded cart from backend:', cartItems.value)
        saveCartToStorage() // Save to localStorage as backup
      } else {
        // No cart in backend, clear localStorage and start empty
        console.log('No cart in backend, clearing cart')
        cartItems.value = []
        clearUserCart() // Clear from localStorage
      }
    } catch (error: any) {
      console.log('Cart fetch failed, clearing cart:', error?.message || 'Unknown error')
      cartItems.value = []
      clearUserCart() // Clear from localStorage on error
    } finally {
      loading.value = false
    }
  }

  interface CartResponse {
    success: boolean
    message?: string
    error?: string
  }

  const addToCart = async (productId: string, quantity: number = 1): Promise<CartResponse> => {
    loading.value = true
    try {
      initializeUserId()
      const requestData = {
        product_id: productId,
        quantity,
        user_id: userId.value
      }

      console.log('Sending cart add request:', requestData)
      const response = await axios.post(`${API_BASE}/cart/add.php`, requestData)
      console.log('Cart add response:', response.data)

      if (response.data.success) {
        // Update cart items from the response
        cartItems.value = response.data.data || []
        console.log('Updated cart items after add:', cartItems.value)

        // Save to localStorage for persistence
        saveCartToStorage()
        
        // Ensure the UI updates
        return { 
          success: true, 
          message: response.data.message || 'Added to cart successfully' 
        }
      } else {
        console.error('API returned error:', response.data.error)
        throw new Error(response.data.error || 'Failed to add item to cart')
      }
    } catch (error: any) {
      console.error('Error adding to cart:', error)
      return { 
        success: false, 
        error: error.response?.data?.error || error.message || 'Failed to add item to cart'
      }
    } finally {
      loading.value = false
    }
  }

  const updateCartItem = async (itemId: string, quantity: number) => {
    loading.value = true
    try {
      initializeUserId()
      const response = await axios.put(`${API_BASE}/cart/update.php`, {
        id: itemId,
        quantity,
        user_id: userId.value
      })

      if (!response.data.success) {
        throw new Error(response.data.message)
      }

      await fetchCartItems()
      saveCartToStorage() // Save updated cart to localStorage
      return { success: true }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.message || error.message 
      }
    } finally {
      loading.value = false
    }
  }

  const removeFromCart = async (itemId: string) => {
    loading.value = true
    try {
      initializeUserId()
      const response = await axios.delete(`${API_BASE}/cart/delete.php`, {
        data: { 
          id: itemId,
          user_id: userId.value
        }
      })

      if (!response.data.success) {
        throw new Error(response.data.message)
      }

      // Use the updated cart data from the response instead of fetching again
      cartItems.value = response.data.data || []
      console.log('Cart updated after delete:', cartItems.value)
      saveCartToStorage() // Save updated cart to localStorage
      return { success: true }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.message || error.message 
      }
    } finally {
      loading.value = false
    }
  }

  const clearCart = async () => {
    try {
      initializeUserId()
      const response = await axios.delete(`${API_BASE}/cart/clear.php`, {
        data: { user_id: userId.value }
      })
      if (response.data.success) {
        clearUserCart() // Clear from both memory and localStorage
      }
    } catch (error) {
      console.error('Error clearing cart:', error)
      // Still clear from localStorage even if API call fails
      clearUserCart()
    }
  }

  // Handle user authentication state changes
  const onUserLogin = async (newUserId: string, isAuth: boolean = true) => {
    console.log('User logged in, switching cart for:', newUserId)
    userId.value = newUserId
    isAuthenticated.value = isAuth
    localStorage.setItem('shopvue_user_id', newUserId)

    // Clear current cart and load user's cart
    cartItems.value = []
    await fetchCartItems()
  }

  const onUserLogout = () => {
    console.log('User logged out, clearing cart')
    clearUserCart()
    userId.value = ''
    isAuthenticated.value = false
    localStorage.removeItem('shopvue_user_id')
  }

  // Initialize cart when store is created
  if (typeof window !== 'undefined') {
    // Force refresh cart items on initialization
    fetchCartItems().catch(err => console.error('Error initializing cart:', err))
  }

  return {
    cartItems,
    loading,
    totalItems,
    totalPrice,
    fetchCartItems,
    addToCart,
    updateCartItem,
    removeFromCart,
    clearCart,
    onUserLogin,
    onUserLogout,
    saveCartToStorage,
    loadCartFromStorage,
    setAuthenticated
  }
})