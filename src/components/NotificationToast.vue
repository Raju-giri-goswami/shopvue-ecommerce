<template>
  <transition-group
    tag="div"
    class="fixed top-4 right-4 z-50 flex flex-col gap-2"
    name="notification"
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="transform translate-x-full opacity-0"
    enter-to-class="transform translate-x-0 opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="transform translate-x-0 opacity-100"
    leave-to-class="transform translate-x-full opacity-0"
  >
    <div
      v-for="notification in notificationStore.notifications"
      :key="notification.id"
      class="flex items-center p-4 rounded-lg shadow-lg min-w-[300px] max-w-md"
      :class="{
        'bg-green-100 border border-green-200 text-green-800': notification.type === 'success',
        'bg-red-100 border border-red-200 text-red-800': notification.type === 'error',
        'bg-blue-100 border border-blue-200 text-blue-800': notification.type === 'info'
      }"
    >
      <div class="flex-1">{{ notification.message }}</div>
      <button
        @click="notificationStore.remove(notification.id)"
        class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </transition-group>
</template>

<script setup lang="ts">
import { useNotificationStore } from '../stores/notification'

const notificationStore = useNotificationStore()
</script>

<style scoped>
.notification-move {
  transition: transform 0.3s ease-in-out;
}
</style>
