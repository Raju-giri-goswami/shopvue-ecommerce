import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

const API_BASE = '/api'

export interface Product {
  id: string
  name: string
  description: string
  price: number
  image_url: string
  stock: number
  category: string
  rating?: number
  created_at: string
  updated_at: string
}

interface ApiResponse<T> {
  data: T
  count?: number
}

interface ApiProduct extends Omit<Product, 'price' | 'stock'> {
  price: string
  stock: string
}

export const useProductsStore = defineStore('products', () => {
  // State
  const products = ref<Product[]>([])
  const loading = ref(false)
  const currentPage = ref(1)
  const totalCount = ref(0)
  const searchQuery = ref('')
  const selectedCategory = ref('')
  const sortBy = ref('')
  const itemsPerPage = 12

  // Getters
  const categories = computed(() => {
    const cats = [...new Set(products.value.map(p => p.category))]
    return cats.filter(Boolean)
  })

  const filteredProducts = computed(() => {
    let filtered = products.value

    if (searchQuery.value) {
      filtered = filtered.filter(p => 
        p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        p.description.toLowerCase().includes(searchQuery.value.toLowerCase())
      )
    }

    if (selectedCategory.value) {
      filtered = filtered.filter(p => p.category === selectedCategory.value)
    }

    // Apply sorting
    if (sortBy.value) {
      filtered = [...filtered].sort((a, b) => {
        switch (sortBy.value) {
          case 'name_asc':
            return a.name.localeCompare(b.name)
          case 'name_desc':
            return b.name.localeCompare(a.name)
          case 'price_asc':
            return a.price - b.price
          case 'price_desc':
            return b.price - a.price
          case 'newest':
            return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
          default:
            return 0
        }
      })
    }

    return filtered
  })

  const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return filteredProducts.value.slice(start, end)
  })

  const totalPages = computed(() => {
    return Math.ceil(filteredProducts.value.length / itemsPerPage)
  })

  // Actions
  const setPage = (page: number) => {
    currentPage.value = page
  }

  const setSearchQuery = (query: string) => {
    searchQuery.value = query
    currentPage.value = 1
  }

  const setCategory = (category: string) => {
    selectedCategory.value = category
    currentPage.value = 1
  }

  const setSortBy = (sort: string) => {
    sortBy.value = sort
    currentPage.value = 1
  }

  const fetchProducts = async () => {
    if (loading.value) return
    
    loading.value = true
    try {
      const response = await axios.get(`${API_BASE}/products/list.php`, {
        timeout: 10000,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })
      
      if (response.data && Array.isArray(response.data.data)) {
        const formattedProducts = response.data.data.map((p: ApiProduct) => ({
          ...p,
          price: Number(p.price),
          stock: Number(p.stock)
        }))
        products.value = formattedProducts
        totalCount.value = response.data.count || formattedProducts.length
      } else {
        console.error('Invalid response format:', response.data)
        throw new Error('Invalid response format')
      }
    } catch (error: any) {
      console.error('Error in fetchProducts:', error?.response?.status, error?.response?.data, error?.message)
      products.value = []
      totalCount.value = 0
    } finally {
      loading.value = false
    }
  }

  const getProductById = async (id: string): Promise<Product | null> => {
    try {
      // First try to find the product in the local state
      const product = products.value.find(p => p.id === id)
      if (product) {
        return product
      }

      // If not found locally, fetch from API
      const response = await axios.get<ApiResponse<ApiProduct>>(`${API_BASE}/products/get.php?id=${id}`)
      if (response.data && response.data.data) {
        return {
          ...response.data.data,
          price: Number(response.data.data.price),
          stock: Number(response.data.data.stock)
        }
      }
      return null
    } catch (error) {
      console.error('Error getting product:', error)
      return null
    }
  }

  return {
    // State
    products,
    loading,
    currentPage,
    totalCount,
    searchQuery,
    selectedCategory,
    sortBy,

    // Getters
    categories,
    filteredProducts,
    paginatedProducts,
    totalPages,

    // Actions
    fetchProducts,
    getProductById,
    setSearchQuery,
    setCategory,
    setSortBy,
    setPage
  }
})