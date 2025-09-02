<template>
  <div>
    <!-- Hero Section -->
    <section class="relative overflow-hidden min-h-[85vh] flex items-center bg-[#1e3a8a]">
      <!-- Animated Background Patterns -->
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full blur-3xl animate-blob"></div>
          <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
          <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/2 h-1/2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
        </div>
        <div class="absolute inset-0 bg-grid-white/[0.03] bg-grid"></div>
      </div>

      <!-- Content -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <!-- Left Column -->
          <div class="animate-fade-in">
                      <div class="relative">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight text-white">
              Welcome to 
              <span class="relative">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500 block mt-2">
                  ShopVue
                </span>
                <span class="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 transform scale-x-0 transition-transform duration-500 hover:scale-x-100"></span>
              </span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 leading-relaxed max-w-2xl">
              Discover amazing products at unbeatable prices with fast shipping and excellent customer service
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
              <router-link 
                to="/products" 
                class="group relative inline-flex items-center justify-center px-8 py-4 font-semibold text-white transition-all duration-300 ease-in-out rounded-xl bg-gradient-to-r from-yellow-400 to-orange-500 hover:translate-y-[-2px] hover:shadow-lg overflow-hidden"
              >
                <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-yellow-300 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out"></span>
                <span class="relative flex items-center">
                  Shop Now
                  <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                  </svg>
                </span>
              </router-link>
              <router-link 
                to="/products" 
                class="group relative inline-flex items-center justify-center px-8 py-4 font-semibold text-white transition-all duration-300 ease-in-out rounded-xl border-2 border-white/80 hover:bg-white hover:text-blue-900 overflow-hidden"
              >
                <span class="relative flex items-center">
                  Browse Catalog
                  <svg class="w-5 h-5 ml-2 opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </span>
              </router-link>
            </div>
          </div>
          </div>

          <!-- Right Column - Featured Categories -->
          <div class="hidden md:grid grid-cols-2 gap-4 animate-slide-up">
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-white hover:bg-white/20 transition-all cursor-pointer transform hover:scale-105">
              <svg class="w-10 h-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
              </svg>
              <h3 class="text-lg font-semibold mb-2">Electronics</h3>
              <p class="text-gray-300">Latest gadgets and tech accessories</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-white hover:bg-white/20 transition-all cursor-pointer transform hover:scale-105">
              <svg class="w-10 h-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
              </svg>
              <h3 class="text-lg font-semibold mb-2">Fashion</h3>
              <p class="text-gray-300">Trending styles and accessories</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-white hover:bg-white/20 transition-all cursor-pointer transform hover:scale-105">
              <svg class="w-10 h-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <h3 class="text-lg font-semibold mb-2">Home & Living</h3>
              <p class="text-gray-300">Beautiful home decorations</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl text-white hover:bg-white/20 transition-all cursor-pointer transform hover:scale-105">
              <svg class="w-10 h-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="text-lg font-semibold mb-2">Lifestyle</h3>
              <p class="text-gray-300">Health and wellness products</p>
            </div>
          </div>
        </div>

        <!-- Wave Decoration -->
        <div class="absolute bottom-0 left-0 right-0">
          <svg class="w-full h-24 fill-white" viewBox="0 0 1440 74" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,32L80,42.7C160,53,320,75,480,74.7C640,75,800,53,960,42.7C1120,32,1280,32,1360,32L1440,32L1440,74L1360,74C1280,74,1120,74,960,74C800,74,640,74,480,74C320,74,160,74,80,74L0,74Z"></path>
          </svg>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center mb-8">
        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Featured Products</h2>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">Check out our most popular items, carefully selected for quality and value</p>
      </div>

      <div v-if="error" class="text-center py-12">
        <p class="text-red-500">{{ error }}</p>
        <button 
          @click="store.fetchProducts()" 
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Try Again
        </button>
      </div>

      <div v-else-if="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-4 text-gray-600">Loading products...</p>
      </div>
      
      <div v-else-if="featuredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8 items-stretch">
        <div 
          v-for="(product, index) in featuredProducts" 
          :key="product.id"
          class="animate-slide-up"
          :style="{ animationDelay: `${index * 0.05}s` }"
        >
          <ProductCard :product="product" />
        </div>
      </div>
      
      <div v-else class="text-center py-12">
        <div class="bg-gray-100 rounded-full p-8 w-24 h-24 mx-auto mb-6 flex items-center justify-center">
          <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
        </div>
        <h3 class="text-2xl font-semibold text-gray-900 mb-4">No Products Available</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
          We're currently loading products. If this persists, please check your internet connection or try refreshing the page.
        </p>
        <button 
          @click="retryLoadProducts"
          class="btn-primary text-lg py-4 px-8"
        >
          Retry Loading
        </button>
      </div>

      <div class="text-center mt-12">
        <router-link 
          to="/products" 
          class="btn-primary text-lg py-4 px-8"
        >
          View All Products
        </router-link>
      </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-16 lg:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Why Choose ShopVue?</h2>
          <p class="text-gray-600 text-lg">We're committed to providing the best shopping experience</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
          <div class="text-center group">
            <div class="bg-blue-100 rounded-full p-6 w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
              <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-gray-900">Free Shipping</h3>
            <p class="text-gray-600 leading-relaxed">Free shipping on all orders over $50. Fast and reliable delivery to your doorstep.</p>
          </div>
          
          <div class="text-center group">
            <div class="bg-green-100 rounded-full p-6 w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:bg-green-200 transition-colors duration-300">
              <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-gray-900">Secure Payment</h3>
            <p class="text-gray-600 leading-relaxed">Your payment information is protected with bank-level security and encryption.</p>
          </div>
          
          <div class="text-center group">
            <div class="bg-orange-100 rounded-full p-6 w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:bg-orange-200 transition-colors duration-300">
              <svg class="h-10 w-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-gray-900">Easy Returns</h3>
            <p class="text-gray-600 leading-relaxed">30-day hassle-free return policy. Not satisfied? We'll make it right.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-900 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Stay Updated</h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
          Subscribe to our newsletter for exclusive deals, new product announcements, and special offers.
        </p>
        <div class="max-w-md mx-auto flex gap-4">
          <input 
            type="email" 
            placeholder="Enter your email"
            class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-blue-500"
          />
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            Subscribe
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useProductsStore } from '../stores/products'
import ProductCard from '../components/ProductCard.vue'

const store = useProductsStore()
const { products, loading } = storeToRefs(store)
const error = ref<string | null>(null)

const featuredProducts = computed(() => {
  // Get first 8 products
  return products.value.slice(0, 8)
})

onMounted(async () => {
  // Always fetch products to ensure fresh data
  error.value = null
  try {
    await store.fetchProducts()
  } catch (e) {
    console.error('Error loading products:', e)
    error.value = 'Failed to load products. Please try again later.'
  }
})

const retryLoadProducts = async () => {
  error.value = null
  try {
    await store.fetchProducts()
  } catch (e) {
    console.error('Error re-loading products:', e)
    error.value = 'Failed to load products. Please try again later.'
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out forwards;
}

.animate-slide-up {
  animation: slide-up 1s ease-out forwards;
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.bg-grid {
  background-size: 30px 30px;
  background-image: 
    linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
}

.hover\:scale-105:hover {
  transform: scale(1.05);
  transition: transform 0.3s ease-out;
}
</style>