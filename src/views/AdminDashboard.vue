<template>
  <div class="admin-dashboard max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
      <button 
        @click="showAddProductModal = true"
        class="btn-primary flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        <span>Add Product</span>
      </button>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Products</h2>
          <div class="p-2 bg-blue-50 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ productsStore.products.length }}</p>
        <p class="text-sm text-gray-500 mt-2">Total Products</p>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Orders</h2>
          <div class="p-2 bg-green-50 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ mockData.orders.length }}</p>
        <p class="text-sm text-gray-500 mt-2">Total Orders</p>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Users</h2>
          <div class="p-2 bg-purple-50 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ mockData.users.length }}</p>
        <p class="text-sm text-gray-500 mt-2">Total Users</p>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Revenue</h2>
          <div class="p-2 bg-yellow-50 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">${{ totalRevenue.toLocaleString() }}</p>
        <p class="text-sm text-gray-500 mt-2">Total Revenue</p>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button 
            @click="activeTab = 'products'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'products'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Products
          </button>
          <button 
            @click="activeTab = 'orders'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'orders'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Orders
          </button>
          <button 
            @click="activeTab = 'users'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'users'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Users
          </button>
        </nav>
      </div>
    </div>

    <!-- Products Tab -->
    <div v-if="activeTab === 'products'" class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Product Management</h2>
      </div>
      
      <!-- Desktop Table View -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="product in productsStore.products" :key="product.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <img :src="getImageUrl(product.image_url)" :alt="product.name" class="h-10 w-10 rounded-lg object-cover mr-4">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500">{{ product.description.substring(0, 50) }}...</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.category }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ product.price }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.stock }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button 
                  @click="editProduct(product)"
                  class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors"
                >
                  Edit
                </button>
                <button 
                  @click="deleteProduct(product.id)"
                  class="text-red-600 hover:text-red-900 px-3 py-1 rounded-md bg-red-50 hover:bg-red-100 transition-colors"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Mobile Card View -->
      <div class="md:hidden p-4 space-y-4">
        <div v-for="product in productsStore.products" :key="product.id" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex items-start space-x-4">
            <img :src="getImageUrl(product.image_url)" :alt="product.name" class="h-16 w-16 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <h3 class="text-lg font-medium text-gray-900 truncate">{{ product.name }}</h3>
              <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ product.description }}</p>
              <div class="flex items-center justify-between mt-3">
                <div class="flex flex-col space-y-1">
                  <span class="text-lg font-bold text-blue-600">${{ product.price }}</span>
                  <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                          :class="{
                            'bg-blue-100 text-blue-800': product.category === 'smartphones' || product.category === 'laptops',
                            'bg-green-100 text-green-800': product.category === 'tablets',
                            'bg-purple-100 text-purple-800': product.category === 'accessories',
                            'bg-orange-100 text-orange-800': product.category === 'gaming',
                            'bg-gray-100 text-gray-800': !['smartphones', 'laptops', 'tablets', 'accessories', 'gaming'].includes(product.category)
                          }">
                      {{ product.category }}
                    </span>
                    <span class="text-sm text-gray-600">{{ product.stock }} in stock</span>
                  </div>
                </div>
                <div class="flex flex-col space-y-2">
                  <button 
                    @click="editProduct(product)"
                    class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded-md bg-blue-50 hover:bg-blue-100 transition-colors text-sm font-medium"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteProduct(product.id)"
                    class="text-red-600 hover:text-red-900 px-3 py-1 rounded-md bg-red-50 hover:bg-red-100 transition-colors text-sm font-medium"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Orders Tab -->
    <div v-if="activeTab === 'orders'" class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Order Management</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="order in mockData.orders" :key="order.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ order.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.customerName }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ order.total }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                  order.status === 'completed' ? 'bg-green-100 text-green-800' :
                  order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-red-100 text-red-800'
                ]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ order.date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Users Tab -->
    <div v-if="activeTab === 'users'" class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">User Management</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="user in mockData.users" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                  user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'
                ]">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ user.joinedDate }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <ProductModal
      :show="showAddProductModal || !!editingProduct"
      :product="editingProduct"
      @close="closeProductModal"
      @save="handleProductSave"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useProductsStore } from '../stores/products'
import ProductModal from '../components/ProductModal.vue'
import type { Product } from '../stores/products'

const router = useRouter()
const authStore = useAuthStore()
const productsStore = useProductsStore()

const activeTab = ref('products')
const showAddProductModal = ref(false)
const editingProduct = ref<Product | null>(null)

// Mock data for demonstration
const mockData = ref({
  orders: [
    { id: '1001', customerName: 'John Doe', total: '299.99', status: 'completed', date: '2024-01-15' },
    { id: '1002', customerName: 'Jane Smith', total: '149.99', status: 'pending', date: '2024-01-14' },
    { id: '1003', customerName: 'Bob Johnson', total: '799.99', status: 'completed', date: '2024-01-13' },
    { id: '1004', customerName: 'Alice Brown', total: '399.99', status: 'cancelled', date: '2024-01-12' },
  ],
  users: [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'user', joinedDate: '2023-12-01' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'user', joinedDate: '2023-12-05' },
    { id: 3, name: 'Admin User', email: 'admin@example.com', role: 'admin', joinedDate: '2023-11-15' },
    { id: 4, name: 'Bob Johnson', email: 'bob@example.com', role: 'user', joinedDate: '2023-12-10' },
  ]
})

const totalRevenue = computed(() => {
  return mockData.value.orders
    .filter(order => order.status === 'completed')
    .reduce((sum, order) => sum + parseFloat(order.total), 0)
})

// Helper function to get proper image URL
const getImageUrl = (imageUrl: string) => {
  if (!imageUrl) return '/placeholder.jpg'
  
  // If it's already a full URL, return as is
  if (imageUrl.startsWith('http')) {
    return imageUrl
  }
  
  // If it starts with /, construct the full URL
  if (imageUrl.startsWith('/')) {
    return `http://localhost/ShopVue/public${imageUrl}`
  }
  
  // Otherwise, assume it's a relative path
  return `http://localhost/ShopVue/public/images/products/${imageUrl}`
}

onMounted(async () => {
  // Check authentication
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }
  
  if (!authStore.isAdmin) {
    router.push('/')
    return
  }
  
  // Load products if not already loaded
  if (!productsStore.products.length) {
    await productsStore.fetchProducts()
  }
})

const editProduct = (product: Product) => {
  editingProduct.value = product
}

const deleteProduct = async (productId: string) => {
  if (confirm('Are you sure you want to delete this product?')) {
    try {
      const response = await fetch('http://localhost/ShopVue/backend/api/products/delete.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: productId })
      })
      
      const result = await response.json()
      
      if (result.success) {
        // Refresh the products list
        await productsStore.fetchProducts()
        alert('Product deleted successfully!')
      } else {
        alert('Failed to delete product: ' + result.error)
      }
    } catch (error) {
      console.error('Error deleting product:', error)
      alert('Error deleting product. Please try again.')
    }
  }
}

const closeProductModal = () => {
  showAddProductModal.value = false
  editingProduct.value = null
}

const handleProductSave = async (productData: Partial<Product>) => {
  try {
    if (editingProduct.value) {
      // Update existing product
      console.log('Update product:', editingProduct.value.id, productData)
      // In a real app, this would call an API to update the product
      alert('Product update would be implemented here')
    } else {
      // Create new product
      console.log('Create new product:', productData)
      // In a real app, this would call an API to create the product
      alert('Product creation would be implemented here')
    }
    closeProductModal()
  } catch (error) {
    console.error('Error saving product:', error)
    alert('Error saving product')
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
