<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 animate-fade-in">
      <h1 class="text-4xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
      <p class="text-gray-600">Review your items before checkout</p>
    </div>

    <div v-if="cartStore.loading" class="flex justify-center py-16">
      <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Loading your cart...</p>
      </div>
    </div>

    <div v-else-if="cartStore.cartItems.length === 0" class="text-center py-16 animate-fade-in">
      <div class="bg-gray-100 rounded-full p-8 w-24 h-24 mx-auto mb-6 flex items-center justify-center">
        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"/>
        </svg>
      </div>
      <h3 class="text-2xl font-semibold text-gray-900 mb-4">Your cart is empty</h3>
      <p class="text-gray-600 mb-8 max-w-md mx-auto">Start shopping to add items to your cart and enjoy our amazing products</p>
      <router-link to="/products" class="btn-primary text-lg py-4 px-8">
        Start Shopping
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2 space-y-4">
        <div 
          v-for="(item, index) in cartStore.cartItems" 
          :key="item.id"
          class="card p-6 animate-slide-up"
          :style="{ animationDelay: `${index * 0.1}s` }"
        >
          <div class="flex items-center space-x-6">
            <!-- Product Image -->
            <div class="flex-shrink-0">
              <img 
                :src="getImageUrl(item.product?.image_url)" 
                :alt="item.product?.name"
                class="w-20 h-20 object-cover rounded-lg shadow-sm"
              />
            </div>
            
            <!-- Product Details -->
            <div class="flex-1 min-w-0">
              <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ item.product?.name }}</h3>
              <p class="text-gray-600 text-sm mb-2">{{ item.product?.category }}</p>
              <p class="text-xl font-bold text-blue-600">${{ item.product?.price.toFixed(2) }}</p>
            </div>
            
            <!-- Quantity Controls -->
            <div class="flex items-center space-x-3">
              <button
                @click="updateQuantity(item.id, item.quantity - 1)"
                class="p-2 rounded-full hover:bg-gray-100 transition-colors border border-gray-300"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
              </button>
              <span class="w-12 text-center font-semibold text-lg">{{ item.quantity }}</span>
              <button
                @click="updateQuantity(item.id, item.quantity + 1)"
                class="p-2 rounded-full hover:bg-gray-100 transition-colors border border-gray-300"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
              </button>
            </div>
            
            <!-- Item Total -->
            <div class="text-right min-w-0">
              <p class="text-lg font-bold text-gray-900">
                ${{ (item.quantity * (item.product?.price || 0)).toFixed(2) }}
              </p>
            </div>
            
            <!-- Remove Button -->
            <button
              @click="removeItem(item.id)"
              class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Cart Summary -->
      <div class="lg:col-span-1">
        <div class="card p-6 sticky top-24">
          <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>
          
          <div class="space-y-4 mb-6">
            <div class="flex justify-between">
              <span class="text-gray-600">Subtotal ({{ cartStore.totalItems }} items)</span>
              <span class="font-semibold">${{ cartStore.totalPrice.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Shipping</span>
              <span class="font-semibold text-green-600">Free</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Tax</span>
              <span class="font-semibold">${{ (cartStore.totalPrice * 0.08).toFixed(2) }}</span>
            </div>
            <hr class="border-gray-200">
            <div class="flex justify-between text-lg font-bold">
              <span>Total</span>
              <span class="text-blue-600">${{ (cartStore.totalPrice * 1.08).toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="space-y-3">
            <router-link 
              to="/checkout" 
              class="w-full btn-primary text-center block py-4 text-lg"
              :class="{ 'opacity-50 cursor-not-allowed': !authStore.isAuthenticated }"
            >
              <template v-if="authStore.isAuthenticated">
                Proceed to Checkout
              </template>
              <template v-else>
                Login to Checkout
              </template>
            </router-link>
            
            <router-link to="/products" class="w-full btn-secondary text-center block py-3">
              Continue Shopping
            </router-link>
          </div>
          
          <!-- Security badges -->
          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Secure
              </div>
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Encrypted
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '../stores/cart'
import { useAuthStore } from '../stores/auth'
import { onMounted } from 'vue'

const cartStore = useCartStore()
const authStore = useAuthStore()

// Refresh cart items when the component mounts
onMounted(async () => {
  console.log('Cart view mounted, refreshing cart items')
  await cartStore.fetchCartItems()
})

// Helper function to get proper image URL
const getImageUrl = (imageUrl?: string) => {
  if (!imageUrl) return '/placeholder.jpg'
  
  // If it's already a full URL, return as is
  if (imageUrl.startsWith('http')) {
    return imageUrl
  }
  
  // If it starts with /, construct the full URL
  if (imageUrl.startsWith('/')) {
    // Handle the mismatch between database URLs and actual file names
    let imagePath = imageUrl
    
    // Map database image URLs to actual file names
    const imageMappings: { [key: string]: string } = {
      '/images/products/airpods-new.jpg': '/images/products/airpods-new.jpg',
      '/images/products/ipad-new.jpg': '/images/products/ipad-new.jpg', 
      '/images/products/iphone14-new.jpg': '/images/products/iphone14-new.jpg',
      '/images/products/macbook-new.jpg': '/images/products/macbook-new.jpg'
    }
    
    // Apply mapping if it exists
    if (imageMappings[imagePath]) {
      imagePath = imageMappings[imagePath]
    }
    
    return `http://localhost/ShopVue/public${imagePath}`
  }
  
  // Otherwise, assume it's a relative path
  return `http://localhost/ShopVue/public/images/products/${imageUrl}`
}

const updateQuantity = async (cartItemId: string, quantity: number) => {
  console.log('Updating quantity for item:', cartItemId, 'to:', quantity)
  if (quantity <= 0) {
    // If quantity is 0 or less, remove the item
    await removeItem(cartItemId)
    return
  }
  
  try {
    const result = await cartStore.updateCartItem(cartItemId, quantity)
    if (!result.success) {
      console.error('Failed to update quantity:', result.error)
      alert('Failed to update quantity: ' + result.error)
    } else {
      console.log('Quantity updated successfully')
    }
  } catch (error) {
    console.error('Error updating quantity:', error)
    alert('Error updating item quantity')
  }
}

const removeItem = async (cartItemId: string) => {
  console.log('Removing item:', cartItemId)
  try {
    const result = await cartStore.removeFromCart(cartItemId)
    if (!result.success) {
      console.error('Failed to remove item:', result.error)
      alert('Failed to remove item: ' + result.error)
    } else {
      console.log('Item removed successfully')
    }
  } catch (error) {
    console.error('Error removing item:', error)
    alert('Error removing item from cart')
  }
}
</script>