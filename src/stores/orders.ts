import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
import { useAuthStore } from './auth'
import { useCartStore } from './cart'

const API_BASE = '/api'

export interface Order {
  id: string
  user_id: string
  total_price: number
  status: string
  created_at: string
  updated_at: string
  order_items?: OrderItem[]
}

export interface OrderItem {
  id: string
  order_id: string
  product_id: string
  quantity: number
  price: number
  created_at: string
  product?: {
    name: string
    image_url: string
  }
}

export const useOrdersStore = defineStore('orders', () => {
  const orders = ref<Order[]>([])
  const loading = ref(false)

  const fetchUserOrders = async () => {
    const authStore = useAuthStore()
    if (!authStore.user) return

    loading.value = true
    try {
      const response = await axios.get(`${API_BASE}/orders/list.php`)
      if (response.data.success) {
        orders.value = response.data.data || []
      } else {
        throw new Error(response.data.message)
      }
    } catch (error) {
      console.error('Error fetching orders:', error)
      orders.value = []
    } finally {
      loading.value = false
    }
  }

  const createOrder = async (orderData: { shipping_address: string }) => {
    const authStore = useAuthStore()
    const cartStore = useCartStore()
    
    if (!authStore.user) {
      return { success: false, error: 'User not authenticated' }
    }

    loading.value = true
    try {
      const response = await axios.post(`${API_BASE}/orders/create.php`, {
        shipping_address: orderData.shipping_address,
        total_price: cartStore.totalPrice,
        items: cartStore.cartItems.map(item => ({
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.product?.price || 0
        }))
      })

      if (!response.data.success) {
        throw new Error(response.data.message)
      }

      await cartStore.clearCart()
      return { success: true, orderId: response.data.data.id }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.message || error.message 
      }
    } finally {
      loading.value = false
    }
  }

  const fetchOrderDetails = async (orderId: string) => {
    const authStore = useAuthStore()
    if (!authStore.user) return null

    loading.value = true
    try {
      const response = await axios.get(`${API_BASE}/orders/detail.php`, {
        params: { id: orderId }
      })
      if (!response.data.success) {
        throw new Error(response.data.message)
      }
      return response.data.data
    } catch (error) {
      console.error('Error fetching order details:', error)
      return null
    } finally {
      loading.value = false
    }
  }

  const updateOrderStatus = async (orderId: string, status: string) => {
    loading.value = true
    try {
      const response = await axios.put(`${API_BASE}/admin/orders.php`, {
        id: orderId,
        status
      })

      if (!response.data.success) {
        throw new Error(response.data.message)
      }

      await fetchUserOrders()
      return { success: true }
    } catch (error: any) {
      return { 
        success: false, 
        error: error.response?.data?.message || error.message 
      }
    } finally {
      loading.value = false
    }
  }

  const fetchAllOrders = async () => {
    loading.value = true
    try {
      const response = await axios.get(`${API_BASE}/admin/orders.php`)
      if (response.data.success) {
        orders.value = response.data.data || []
      } else {
        throw new Error(response.data.message)
      }
    } catch (error) {
      console.error('Error fetching all orders:', error)
      orders.value = []
    } finally {
      loading.value = false
    }
  }

  // Initialize orders when store is created
  fetchUserOrders()

  return {
    orders,
    loading,
    fetchUserOrders,
    fetchAllOrders,
    createOrder,
    updateOrderStatus,
    fetchOrderDetails
  }
})