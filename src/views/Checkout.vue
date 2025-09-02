<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Checkout Form -->
      <div class="card p-6">
        <h2 class="text-xl font-semibold mb-6">Shipping Information</h2>
        
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="firstName" class="block text-sm font-medium text-gray-700">
                First Name
              </label>
              <input
                id="firstName"
                v-model="form.firstName"
                type="text"
                required
                class="input-field mt-1"
              />
            </div>
            
            <div>
              <label for="lastName" class="block text-sm font-medium text-gray-700">
                Last Name
              </label>
              <input
                id="lastName"
                v-model="form.lastName"
                type="text"
                required
                class="input-field mt-1"
              />
            </div>
          </div>
          
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">
              Address
            </label>
            <input
              id="address"
              v-model="form.address"
              type="text"
              required
              class="input-field mt-1"
            />
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="city" class="block text-sm font-medium text-gray-700">
                City
              </label>
              <input
                id="city"
                v-model="form.city"
                type="text"
                required
                class="input-field mt-1"
              />
            </div>
            
            <div>
              <label for="zipCode" class="block text-sm font-medium text-gray-700">
                ZIP Code
              </label>
              <input
                id="zipCode"
                v-model="form.zipCode"
                type="text"
                required
                class="input-field mt-1"
              />
            </div>
          </div>

          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <button
            type="submit"
            :disabled="ordersStore.loading"
            class="w-full btn-primary py-3 text-lg disabled:opacity-50"
          >
            <span v-if="ordersStore.loading">Processing order...</span>
            <span v-else>Place Order</span>
          </button>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="card p-6">
        <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
        
        <div class="space-y-4 mb-6">
          <div 
            v-for="item in cartStore.cartItems" 
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
              <p class="text-sm text-gray-600">Qty: {{ item.quantity }}</p>
            </div>
            <p class="font-semibold">
              ${{ (item.quantity * (item.product?.price || 0)).toFixed(2) }}
            </p>
          </div>
        </div>
        
        <div class="border-t pt-4">
          <div class="flex justify-between items-center text-lg font-bold">
            <span>Total</span>
            <span>${{ cartStore.totalPrice.toFixed(2) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart'
import { useOrdersStore } from '../stores/orders'

const router = useRouter()
const cartStore = useCartStore()
const ordersStore = useOrdersStore()

const form = reactive({
  firstName: '',
  lastName: '',
  address: '',
  city: '',
  zipCode: ''
})

const error = ref('')

const handleSubmit = async () => {
  error.value = ''
  
  if (cartStore.cartItems.length === 0) {
    error.value = 'Your cart is empty'
    return
  }

  try {
    const shippingAddress = `${form.firstName} ${form.lastName}, ${form.address}, ${form.city}, ${form.zipCode}`
    const order = await ordersStore.createOrder({ shipping_address: shippingAddress })
    router.push(`/order-confirmation/${order.orderId}`)
  } catch (err: any) {
    error.value = err.message || 'Failed to place order'
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
  cartStore.fetchCartItems()
})
</script>