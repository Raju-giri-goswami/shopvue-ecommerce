<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">About ShopVue</h1>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Your trusted destination for quality products and exceptional shopping experience
      </p>
    </div>

    <!-- Our Story Section -->
    <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
      <div class="space-y-6">
        <h2 class="text-3xl font-bold text-gray-900">Our Story</h2>
        <p class="text-lg text-gray-600 leading-relaxed">
          Founded in 2023, ShopVue started with a simple mission: to make quality products accessible to everyone. 
          What began as a small online store has grown into a trusted marketplace, serving thousands of happy customers.
        </p>
        <p class="text-lg text-gray-600 leading-relaxed">
          We believe in quality, transparency, and customer satisfaction. Every product in our store is carefully 
          selected to ensure it meets our high standards of quality and value.
        </p>
      </div>
      <div class="relative">
        <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden shadow-xl">
          <img 
            src="https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
            alt="Our Story" 
            class="w-full h-full object-cover"
          >
        </div>
      </div>
    </div>

    <!-- Values Section -->
    <div class="py-16 bg-gray-50 rounded-3xl px-8 mb-20">
      <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Values</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center space-y-4">
          <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Quality</h3>
          <p class="text-gray-600">We never compromise on quality. Each product is verified to meet our standards.</p>
        </div>
        <div class="text-center space-y-4">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Reliability</h3>
          <p class="text-gray-600">Fast shipping, secure payments, and responsive customer support.</p>
        </div>
        <div class="text-center space-y-4">
          <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Customer First</h3>
          <p class="text-gray-600">Your satisfaction is our top priority. We're here to help you every step of the way.</p>
        </div>
      </div>
    </div>

    <!-- Team Section -->
    <div class="mb-20">
      <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Our Team</h2>
      <div class="grid md:grid-cols-4 gap-8">
        <div class="text-center space-y-4">
          <div class="w-32 h-32 rounded-full overflow-hidden mx-auto">
            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Team member" class="w-full h-full object-cover">
          </div>
          <h3 class="text-xl font-semibold text-gray-900">John Doe</h3>
          <p class="text-gray-600">CEO & Founder</p>
        </div>
        <div class="text-center space-y-4">
          <div class="w-32 h-32 rounded-full overflow-hidden mx-auto">
            <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Team member" class="w-full h-full object-cover">
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Jane Smith</h3>
          <p class="text-gray-600">Head of Operations</p>
        </div>
        <div class="text-center space-y-4">
          <div class="w-32 h-32 rounded-full overflow-hidden mx-auto">
            <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Team member" class="w-full h-full object-cover">
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Mike Johnson</h3>
          <p class="text-gray-600">Product Manager</p>
        </div>
        <div class="text-center space-y-4">
          <div class="w-32 h-32 rounded-full overflow-hidden mx-auto">
            <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Team member" class="w-full h-full object-cover">
          </div>
          <h3 class="text-xl font-semibold text-gray-900">Sarah Wilson</h3>
          <p class="text-gray-600">Customer Success</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Component is purely presentational, no script logic needed
</script>
