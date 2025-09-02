<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

    <div v-if="ordersStore.loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>

    <div v-else-if="ordersStore.orders.length === 0" class="text-center py-12">
      <div class="text-gray-400 mb-4">
        <ClipboardDocumentListIcon class="h-12 w-12 mx-auto" />
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
      <p class="text-gray-600 mb-6">Start shopping to see your orders here</p>
      <router-link to="/products" class="btn-primary">
        Start Shopping
      </router-link>
    </div>

    <div v-else class="space-y-6">
      <div 
        v-for="order in ordersStore.orders" 
        :key="order.id"
        class="card p-6 animate-slide-up"
      >
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">
              Order #{{ order.id.slice(0, 8) }}
            </h3>
            <p class="text-sm text-gray-600">
              {{ new Date(order.created_at).toLocaleDateString() }}
            </p>
          </div>
          
          <div class="mt-2 sm:mt-0">
            <span 
              :class="getStatusClass(order.status)"
              class="px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ order.status }}
            </span>
          </div>
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
        
        <div class="border-t pt-4 flex justify-between items-center">
          <span class="text-lg font-bold">Total: ${{ order.total_price.toFixed(2) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { ClipboardDocumentListIcon } from '@heroicons/vue/24/outline'
import { useOrdersStore } from '../stores/orders'

const ordersStore = useOrdersStore()

const getStatusClass = (status: string) => {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'processing':
      return 'bg-blue-100 text-blue-800'
    case 'shipped':
      return 'bg-purple-100 text-purple-800'
    case 'delivered':
      return 'bg-green-100 text-green-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

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

onMounted(() => {
  ordersStore.fetchUserOrders()
})
</script>