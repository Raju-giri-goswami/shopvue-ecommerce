<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">All Products</h1>
      <p class="text-gray-600 text-lg">Discover our complete collection of premium products</p>
    </div>

    <!-- Search and Filters -->
    <div class="mb-8">
      <SearchFilters 
        :categories="productsStore.categories" 
        :totalResults="productsStore.filteredProducts.length"
      />
    </div>

    <!-- Loading State -->
    <div v-if="productsStore.loading" class="flex justify-center py-16">
      <div class="text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Loading products...</p>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-else-if="productsStore.paginatedProducts.length > 0">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8 items-stretch">
        <div 
          v-for="(product, index) in productsStore.paginatedProducts" 
          :key="product.id"
          class="animate-slide-up"
          :style="{ animationDelay: `${index * 0.05}s` }"
        >
          <ProductCard :product="product" />
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-12">
        <Pagination
          :currentPage="productsStore.currentPage"
          :totalPages="productsStore.totalPages"
          :totalItems="productsStore.filteredProducts.length"
          :itemsPerPage="12"
          @pageChange="productsStore.setPage"
        />
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 animate-fade-in">
      <div class="text-gray-400 mb-6">
        <svg class="h-16 w-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
      <p class="text-gray-600 mb-6">Try adjusting your search or filter criteria</p>
      <button 
        @click="clearFilters"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        Clear Filters
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useProductsStore } from '../stores/products'
import ProductCard from '../components/ProductCard.vue'
import SearchFilters from '../components/SearchFilters.vue'
import Pagination from '../components/Pagination.vue'

const productsStore = useProductsStore()

onMounted(async () => {
  if (!productsStore.products.length) {
    await productsStore.fetchProducts()
  }
})

const clearFilters = () => {
  productsStore.setSearchQuery('')
  productsStore.setCategory('')
  productsStore.setPage(1)
}
</script>