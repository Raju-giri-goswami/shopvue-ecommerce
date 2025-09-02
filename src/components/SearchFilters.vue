<template>
  <div class="card p-6 animate-fade-in">
    <div class="flex flex-col lg:flex-row lg:items-center gap-6">
      <!-- Search -->
      <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            v-model="localSearchQuery"
            @input="onSearchChange"
            type="text"
            placeholder="Search by name or description..."
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
          />
          <button 
            v-if="localSearchQuery"
            @click="clearSearch"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="lg:min-w-64">
        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
        <select
          v-model="localSelectedCategory"
          @change="onCategoryChange"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
      </div>

      <!-- Sort By -->
      <div class="lg:min-w-64">
        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
        <select
          v-model="localSortBy"
          @change="onSortChange"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
        >
          <option value="">Default</option>
          <option value="name_asc">Name (A-Z)</option>
          <option value="name_desc">Name (Z-A)</option>
          <option value="price_asc">Price (Low to High)</option>
          <option value="price_desc">Price (High to Low)</option>
          <option value="newest">Newest First</option>
        </select>
      </div>

      <!-- Results count -->
      <div class="lg:min-w-48">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
          <div class="text-2xl font-bold text-blue-600">{{ totalResults }}</div>
          <div class="text-sm text-blue-700">
            {{ totalResults === 1 ? 'Product Found' : 'Products Found' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useProductsStore } from '../stores/products'

const productsStore = useProductsStore()

const localSearchQuery = ref(productsStore.searchQuery)
const localSelectedCategory = ref(productsStore.selectedCategory)
const localSortBy = ref(productsStore.sortBy)

defineProps<{
  categories: string[]
  totalResults: number
}>()

let searchTimeout: NodeJS.Timeout

const onSearchChange = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    productsStore.setSearchQuery(localSearchQuery.value)
  }, 300)
}

const onCategoryChange = () => {
  productsStore.setCategory(localSelectedCategory.value)
}

const onSortChange = () => {
  productsStore.setSortBy(localSortBy.value)
}

const clearSearch = () => {
  localSearchQuery.value = ''
  productsStore.setSearchQuery('')
}

// Watch for external changes to sync local state
watch(() => productsStore.searchQuery, (newValue) => {
  localSearchQuery.value = newValue
})

watch(() => productsStore.selectedCategory, (newValue) => {
  localSelectedCategory.value = newValue
})

watch(() => productsStore.sortBy, (newValue) => {
  localSortBy.value = newValue
})
</script>