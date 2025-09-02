<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Loading State -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
      <p class="mt-4 text-gray-600">Loading product details...</p>
    </div>

    <div v-else-if="product" class="animate-fade-in">
      <!-- Breadcrumb with hover effects -->
      <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
          <li>
            <router-link 
              to="/" 
              class="text-gray-500 hover:text-blue-600 transition-colors flex items-center"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              Home
            </router-link>
          </li>
          <li><ChevronRightIcon class="h-4 w-4 text-gray-400" /></li>
          <li>
            <router-link 
              to="/products" 
              class="text-gray-500 hover:text-blue-600 transition-colors flex items-center"
            >
              Products
            </router-link>
          </li>
          <li><ChevronRightIcon class="h-4 w-4 text-gray-400" /></li>
          <li class="text-blue-600 font-medium">{{ product.name }}</li>
        </ol>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Image Gallery -->
        <div class="space-y-4">
          <div class="aspect-square overflow-hidden rounded-2xl bg-gray-100 border border-gray-200 shadow-lg">
            <img 
              :src="imageUrl" 
              :alt="product.name"
              class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-zoom-in"
              @click="openImageModal"
              @error="handleImageError"
            />
          </div>
          <!-- Image Gallery Thumbnails (if you have multiple images) -->
          <div class="grid grid-cols-4 gap-4">
            <div 
              v-for="i in 4" 
              :key="i"
              class="aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition-all cursor-pointer bg-gray-100"
            >
              <img 
                :src="imageUrl" 
                :alt="product.name"
                class="w-full h-full object-cover"
                @error="handleImageError"
              />
            </div>
          </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-8">
          <div>
            <div class="flex items-center space-x-4">
              <span class="px-3 py-1 rounded-full text-sm font-medium" 
                :class="product.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
              >
                {{ product.stock > 0 ? 'In Stock' : 'Out of Stock' }}
              </span>
              <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ product.category }}
              </span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mt-4">{{ product.name }}</h1>
            <div class="mt-4 flex items-baseline space-x-4">
              <p class="text-3xl font-bold text-blue-600">
                ${{ product.price.toFixed(2) }}
              </p>
              <span class="text-gray-500 line-through text-lg">
                ${{ (product.price * 1.2).toFixed(2) }}
              </span>
              <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">
                Save 20%
              </span>
            </div>
          </div>

          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-gray-900">Description</h3>
            <p class="text-gray-600 leading-relaxed text-lg">{{ product.description }}</p>
            <div class="border-t border-gray-200 pt-4">
              <div class="flex items-center space-x-2 text-sm text-gray-600">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Premium Quality</span>
              </div>
              <div class="flex items-center space-x-2 text-sm text-gray-600 mt-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>24-hour Temperature Control</span>
              </div>
            </div>
          </div>

          <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex items-center justify-between">
              <span class="text-gray-600">Stock Status:</span>
              <span class="font-medium" :class="product.stock > 20 ? 'text-green-600' : 'text-orange-600'">
                {{ product.stock }} units available
              </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
              <div 
                class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                :style="{ width: `${Math.min((product.stock / 150) * 100, 100)}%` }"
              ></div>
            </div>
          </div>

          <!-- Quantity and Add to Cart -->
          <div class="space-y-6">
            <div v-if="product.stock === 0" 
              class="bg-red-50 border border-red-200 rounded-xl p-6 flex items-center space-x-4"
            >
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
              <div>
                <p class="text-red-600 font-medium">This item is currently out of stock</p>
                <p class="text-red-500 text-sm mt-1">Please check back later or subscribe for notifications</p>
              </div>
            </div>

            <div v-else class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Select Quantity
                </label>
                <div class="flex items-center space-x-4">
                  <button
                    @click="quantity = Math.max(1, quantity - 1)"
                    class="w-12 h-12 rounded-xl border-2 border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-center group"
                  >
                    <MinusIcon class="h-5 w-5 text-gray-600 group-hover:text-blue-600" />
                  </button>
                  <div class="w-20 h-12 rounded-xl border-2 border-gray-300 flex items-center justify-center font-bold text-lg">
                    {{ quantity }}
                  </div>
                  <button
                    @click="quantity = Math.min(product.stock, quantity + 1)"
                    :disabled="quantity >= product.stock"
                    class="w-12 h-12 rounded-xl border-2 border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-center group disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <PlusIcon class="h-5 w-5 text-gray-600 group-hover:text-blue-600" />
                  </button>
                </div>
              </div>

              <div class="flex space-x-4">
                <button
                  @click="handleAddToCart"
                  :disabled="addingToCart"
                  class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center space-x-2 shadow-lg shadow-blue-200"
                >
                  <svg v-if="addingToCart" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                  </svg>
                  <span v-else class="flex items-center">
                    Add to Cart
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                  </span>
                </button>

                <button
                  class="p-4 rounded-xl border-2 border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all group"
                >
                  <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="border-t border-gray-200 pt-6 space-y-4">
            <div class="flex items-center space-x-3 text-sm text-gray-600">
              <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>30-day money-back guarantee</span>
            </div>
            <div class="flex items-center space-x-3 text-sm text-gray-600">
              <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
              </svg>
              <span>Free shipping on orders over $50</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <h2 class="text-2xl font-bold text-gray-900 mb-4">Product not found</h2>
      <router-link to="/products" class="btn-primary">
        Back to Products
      </router-link>
    </div>
    <!-- Image Modal -->
    <div v-if="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="showImageModal = false">
      <div class="max-w-4xl w-full mx-4">
        <img 
          :src="imageUrl" 
          :alt="product?.name"
          class="w-full h-auto rounded-lg shadow-2xl"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { ChevronRightIcon, PlusIcon, MinusIcon } from '@heroicons/vue/24/outline'
import { useProductsStore } from '../stores/products'
import { useCartStore } from '../stores/cart'
import { useNotificationStore } from '../stores/notification'
import type { Product } from '../stores/products'

const route = useRoute()
const productsStore = useProductsStore()
const cartStore = useCartStore()
const notificationStore = useNotificationStore()

const product = ref<Product | null>(null)
const loading = ref(true)
const quantity = ref(1)
const addingToCart = ref(false)
const showImageModal = ref(false)

// Computed property for proper image URL construction
const imageUrl = computed(() => {
  if (!product.value?.image_url) return '/placeholder.jpg'
  
  // If it's already a full URL, return as is
  if (product.value.image_url.startsWith('http')) {
    return product.value.image_url
  }
  
  // If it starts with /, construct the full URL
  if (product.value.image_url.startsWith('/')) {
    // Handle the mismatch between database URLs and actual file names
    let imagePath = product.value.image_url
    
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
  return `http://localhost/ShopVue/public/images/products/${product.value.image_url}`
})

const openImageModal = () => {
  showImageModal.value = true
}

const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement
  if (img) {
    img.src = '/placeholder.jpg'
  }
}

const handleAddToCart = async () => {
  if (!product.value || addingToCart.value) return

  addingToCart.value = true
  try {
    console.log('Adding to cart:', product.value.id, quantity.value)
    const result = await cartStore.addToCart(product.value.id, quantity.value)
    console.log('Result from cart add:', result)
    
    if (result.success) {
      notificationStore.show(result.message || 'Added to cart successfully', 'success')
      
      // Force a full refresh to update everything
      setTimeout(() => {
        cartStore.fetchCartItems().then(() => {
          console.log('Cart refreshed after timeout')
        })
      }, 500)
    } else {
      notificationStore.show(result.error || 'Failed to add item to cart', 'error')
    }
  } catch (error: unknown) {
    const errorMessage = error instanceof Error ? error.message : 'Failed to add item to cart'
    notificationStore.show(errorMessage, 'error')
    console.error('Error adding to cart:', error)
  } finally {
    addingToCart.value = false
  }
}

onMounted(async () => {
  try {
    const id = route.params.id as string
    if (!id) throw new Error('Product ID is required')
    
    product.value = await productsStore.getProductById(id)
    if (!product.value) throw new Error('Product not found')
    
  } catch (error: unknown) {
    const errorMessage = error instanceof Error ? error.message : 'Failed to load product details'
    notificationStore.show(errorMessage, 'error')
    console.error('Error fetching product:', error)
  } finally {
    loading.value = false
  }
})
</script>