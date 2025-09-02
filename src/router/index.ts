import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Home from '../views/Home.vue'
import Products from '../views/Products.vue'
import ProductDetail from '../views/ProductDetail.vue'
import Cart from '../views/Cart.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Checkout from '../views/Checkout.vue'
import OrderConfirmation from '../views/OrderConfirmation.vue'
import Dashboard from '../views/Dashboard.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import About from '../views/About.vue'
import Contact from '../views/Contact.vue'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/products',
      name: 'Products',
      component: Products
    },
    {
      path: '/product/:id',
      name: 'ProductDetail',
      component: ProductDetail
    },
    {
      path: '/cart',
      name: 'Cart',
      component: Cart
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/checkout',
      name: 'Checkout',
      component: Checkout,
      meta: { requiresAuth: true }
    },
    {
      path: '/order-confirmation/:id',
      name: 'OrderConfirmation',
      component: OrderConfirmation,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/admin',
      name: 'AdminDashboard',
      component: AdminDashboard,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/about',
      name: 'About',
      component: About
    },
    {
      path: '/contact',
      name: 'Contact',
      component: Contact
    }
  ]
})

router.beforeEach(async (to, _from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.user) {
    next('/login')
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next('/')
  } else {
    next()
  }
})

export default router