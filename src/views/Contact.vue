<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Have questions? We're here to help. Send us a message and we'll respond as soon as possible.
      </p>
    </div>

    <div class="grid md:grid-cols-2 gap-12">
      <!-- Contact Form -->
      <div class="bg-white p-8 rounded-2xl shadow-lg">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              :class="{ 'border-red-500': errors.name }"
            >
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              :class="{ 'border-red-500': errors.email }"
            >
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input
              id="subject"
              v-model="form.subject"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              :class="{ 'border-red-500': errors.subject }"
            >
            <p v-if="errors.subject" class="mt-1 text-sm text-red-600">{{ errors.subject }}</p>
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea
              id="message"
              v-model="form.message"
              rows="5"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none"
              :class="{ 'border-red-500': errors.message }"
            ></textarea>
            <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message }}</p>
          </div>

          <button
            type="submit"
            :disabled="submitting"
            class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="submitting">Sending...</span>
            <span v-else>Send Message</span>
          </button>
        </form>
      </div>

      <!-- Contact Information -->
      <div class="space-y-8">
        <div class="bg-gray-50 p-8 rounded-2xl">
          <h3 class="text-xl font-semibold text-gray-900 mb-6">Other Ways to Connect</h3>
          <div class="space-y-6">
            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="text-lg font-medium text-gray-900">Phone</h4>
                <p class="mt-1 text-gray-600">+1 (555) 123-4567</p>
                <p class="mt-1 text-sm text-gray-500">Mon-Fri from 8am to 6pm</p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="text-lg font-medium text-gray-900">Email</h4>
                <p class="mt-1 text-gray-600">support@shopvue.com</p>
                <p class="mt-1 text-sm text-gray-500">We'll respond within 24 hours</p>
              </div>
            </div>

            <div class="flex items-start space-x-4">
              <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
              </div>
              <div>
                <h4 class="text-lg font-medium text-gray-900">Office</h4>
                <p class="mt-1 text-gray-600">123 Commerce Street</p>
                <p class="text-gray-600">New York, NY 10001</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 p-8 rounded-2xl">
          <h3 class="text-xl font-semibold text-gray-900 mb-6">Follow Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
              <span class="sr-only">Facebook</span>
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.77,7.46H14.5V5.9a.9.9,0,0,1,.94-.85h3.17V0H14.74C10.47,0,8.5,2.52,8.5,6.21V7.46h-3v5h3v11h6V12.46h4.27Z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
              <span class="sr-only">Twitter</span>
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953,4.57a10,10,0,0,1-2.825.775,4.958,4.958,0,0,0,2.163-2.723,10.163,10.163,0,0,1-3.127,1.184A4.92,4.92,0,0,0,11.78,8.288,13.938,13.938,0,0,1,1.64,3.162,4.822,4.822,0,0,0,.974,5.708,4.905,4.905,0,0,0,3.2,9.713,4.868,4.868,0,0,1,.975,9.1v.062A4.926,4.926,0,0,0,4.94,14a5.066,5.066,0,0,1-2.212.085,4.937,4.937,0,0,0,4.6,3.417,9.868,9.868,0,0,1-6.1,2.107A10.444,10.444,0,0,1,0,19.544,14,14,0,0,0,7.557,21.5c9.056,0,14-7.5,14-14.014,0-.214,0-.428-.015-.642A10.016,10.016,0,0,0,24,4.59Z"/>
              </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
              <span class="sr-only">Instagram</span>
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12,0C8.74,0,8.333.015,7.053.072,5.775.132,4.905.333,4.14.63c-.789.306-1.459.717-2.126,1.384S.935,3.35.63,4.14C.333,4.905.131,5.775.072,7.053.012,8.333,0,8.74,0,12s.015,3.667.072,4.947c.06,1.277.261,2.148.558,2.913.306.788.717,1.459,1.384,2.126.667.666,1.336,1.079,2.126,1.384.766.296,1.636.499,2.913.558C8.333,23.988,8.74,24,12,24s3.667-.015,4.947-.072c1.277-.06,2.148-.262,2.913-.558.788-.306,1.459-.718,2.126-1.384.666-.667,1.079-1.335,1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319,1.347,20.651.935,19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012,15.26,0,12,0Zm0,2.16c3.203,0,3.585.016,4.85.071,1.17.055,1.805.249,2.227.415.562.217.96.477,1.382.896.419.42.679.819.896,1.381.164.422.36,1.057.413,2.227.057,1.266.07,1.646.07,4.85s-.015,3.585-.074,4.85c-.061,1.17-.256,1.805-.421,2.227-.224.562-.479.96-.899,1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211,0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844,0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689,1.379-.898.42-.166,1.051-.361,2.221-.421,1.275-.045,1.65-.06,4.859-.06l.045.03Zm0,3.678A6.162,6.162,0,1,0,18.162,12,6.162,6.162,0,0,0,12,5.838Zm0,10.162a4,4,0,1,1,4-4A4,4,0,0,1,12,16Zm7.846-10.405a1.441,1.441,0,1,1-1.441-1.441A1.441,1.441,0,0,1,19.846,5.595Z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useNotificationStore } from '../stores/notification'

const notificationStore = useNotificationStore()

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const errors = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const submitting = ref(false)

const validateForm = () => {
  let isValid = true
  
  // Reset errors
  Object.keys(errors).forEach(key => {
    errors[key as keyof typeof errors] = ''
  })

  if (!form.name.trim()) {
    errors.name = 'Name is required'
    isValid = false
  }

  if (!form.email.trim()) {
    errors.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!form.subject.trim()) {
    errors.subject = 'Subject is required'
    isValid = false
  }

  if (!form.message.trim()) {
    errors.message = 'Message is required'
    isValid = false
  }

  return isValid
}

const handleSubmit = async () => {
  if (!validateForm()) return

  submitting.value = true
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    notificationStore.show('Message sent successfully! We\'ll get back to you soon.', 'success')
    
    // Reset form
    Object.keys(form).forEach(key => {
      form[key as keyof typeof form] = ''
    })
  } catch (error) {
    notificationStore.show('Failed to send message. Please try again.', 'error')
  } finally {
    submitting.value = false
  }
}
</script>
