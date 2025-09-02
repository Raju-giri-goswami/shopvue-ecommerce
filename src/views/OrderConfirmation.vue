<template>
  <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>

    <div v-else-if="order" class="text-center animate-bounce-in">
      <!-- Success Icon -->
      <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-6 flex items-center justify-center">
        <CheckCircleIcon class="h-8 w-8 text-green-600" />
      </div>

      <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Confirmed!</h1>
      <p class="text-lg text-gray-600 mb-8">
        Thank you for your purchase. Your order has been successfully placed.
      </p>

      <!-- Order Details -->
      <div class="card p-6 text-left mb-8">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Order #{{ order.id.slice(0, 8) }}</h3>
          <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
            {{ order.status }}
          </span>
        </div>
        
        <div class="space-y-3 mb-4">
          <div 
            v-for="item in order.order_items" 
            :key="item.id"
            class="flex items-center space-x-3"
          >
            <img 
              :src="getImageUrl(item.product?.image_url)" 
              :alt="item.product?.name"
              class="w-12 h-12 object-cover rounded-lg"
            />
            <div class="flex-1">
              <p class="font-medium">{{ item.product?.name }}</p>
              <p class="text-sm text-gray-600">Qty: {{ item.quantity }} × ${{ item.price.toFixed(2) }}</p>
            </div>
            <p class="font-semibold">
              ${{ (item.quantity * item.price).toFixed(2) }}
            </p>
          </div>
        </div>
        
        <div class="border-t pt-4">
          <div class="flex justify-between items-center text-lg font-bold">
            <span>Total</span>
            <span>${{ order.total_price.toFixed(2) }}</span>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <router-link to="/products" class="btn-secondary">
          Continue Shopping
        </router-link>
        <router-link to="/dashboard" class="btn-primary">
          View My Orders
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { CheckCircleIcon } from '@heroicons/vue/24/outline'
import { useOrdersStore } from '../stores/orders'
import type { Order } from '../stores/orders'

const route = useRoute()
const ordersStore = useOrdersStore()

const order = ref<Order | null>(null)
const loading = ref(true)

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

onMounted(async () => {
  try {
    const orderId = route.params.id as string
    order.value = await ordersStore.fetchOrderDetails(orderId)
  } catch (error) {
    console.error('Error fetching order:', error)
  } finally {
    loading.value = false
  }
})
</script>