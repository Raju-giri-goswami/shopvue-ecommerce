<script setup lang="ts">
import { ref, computed } from 'vue'
import { useCartStore } from '../stores/cart'
import type { Product } from '../stores/products'

const props = defineProps<{
  product: Product
}>()

const cartStore = useCartStore()
const loading = ref(false)

const addToCart = async () => {
  if (props.product.stock === 0) return
  
  loading.value = true
  try {
    console.log('Adding to cart from ProductCard:', props.product.id)
    const result = await cartStore.addToCart(props.product.id, 1)
    console.log('Result from ProductCard add:', result)
    
    if (result.success) {
      // Show success message
      console.log('Product added to cart successfully')
      // Force refresh cart items to update UI
      await cartStore.fetchCartItems()
    } else {
      console.error('Failed to add to cart:', result.error)
    }
  } catch (error: unknown) {
    const errorMessage = error instanceof Error ? error.message : 'Failed to add item to cart'
    console.error('Error adding to cart:', errorMessage)
  } finally {
    loading.value = false
  }
}

const imageUrl = computed(() => {
  if (!props.product.image_url) return '/placeholder.jpg'
  
  // If the image URL starts with http, use it as is
  if (props.product.image_url.startsWith('http')) {
    return props.product.image_url
  }
  
  // If it starts with /, construct the full URL
  if (props.product.image_url.startsWith('/')) {
    // Handle the mismatch between database URLs and actual file names
    let imagePath = props.product.image_url
    
    // Map database image URLs to actual file names
    const imageMappings: { [key: string]: string } = {
      // Use the new high-quality images instead of old ones
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
  return `http://localhost/ShopVue/public/images/products/${props.product.image_url}`
})

const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement
  if (img) {
    img.src = '/placeholder.jpg'
  }
}
</script>

<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group h-full flex flex-col min-h-[400px]">
    <div class="relative aspect-square overflow-hidden">
      <img 
        :src="imageUrl" 
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
        loading="lazy"
        @error="handleImageError"
      />
      <div 
        v-if="product.stock === 0"
        class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center"
      >
        <span class="bg-red-500 text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg">
          Out of Stock
        </span>
      </div>
      
      <!-- Quick view button -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
        <router-link 
          :to="`/product/${product.id}`"
          class="bg-white/95 backdrop-blur-sm text-gray-900 px-6 py-3 rounded-lg font-medium opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg hover:bg-white"
        >
          Quick View
        </router-link>
      </div>
    </div>
    
    <div class="p-6 flex-1 flex flex-col">
      <div class="space-y-2 flex-shrink-0">
        <div class="flex items-center space-x-2">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" 
                :class="{
                  'bg-blue-100 text-blue-800': product.category === 'smartphones' || product.category === 'laptops',
                  'bg-green-100 text-green-800': product.category === 'tablets',
                  'bg-purple-100 text-purple-800': product.category === 'accessories',
                  'bg-orange-100 text-orange-800': product.category === 'gaming',
                  'bg-gray-100 text-gray-800': !['smartphones', 'laptops', 'tablets', 'accessories', 'gaming'].includes(product.category)
                }">>
            <svg class="w-3 h-3 mr-1" :class="{
              'text-blue-500': product.category === 'smartphones' || product.category === 'laptops',
              'text-green-500': product.category === 'tablets',
              'text-purple-500': product.category === 'accessories',
              'text-orange-500': product.category === 'gaming',
              'text-gray-500': !['smartphones', 'laptops', 'tablets', 'accessories', 'gaming'].includes(product.category)
            }" fill="currentColor" viewBox="0 0 20 20">
              <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
              <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ product.category }}
          </span>
          <div v-if="product.rating" class="flex items-center">
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="text-xs text-gray-600 ml-1">{{ product.rating }}/5</span>
          </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
          {{ product.name }}
        </h3>
      </div>
      
      <p class="text-gray-600 text-sm mt-3 mb-4 line-clamp-2 leading-relaxed flex-1">
        {{ product.description }}
      </p>
      
      <div class="mt-auto flex-shrink-0 space-y-4">
        <div class="flex items-center justify-between">
          <div class="space-y-1">
            <span class="text-2xl font-bold text-blue-600">
              ${{ product.price.toFixed(2) }}
            </span>
            <div class="flex items-center space-x-1">
              <span class="text-sm" :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
              </span>
            </div>
          </div>
        </div>
        
        <button 
          @click="addToCart"
          :disabled="product.stock === 0 || loading"
          class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center"
        >
          <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          <span v-if="loading">Adding...</span>
          <span v-else-if="product.stock === 0">Out of Stock</span>
          <span v-else>Add to Cart</span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>