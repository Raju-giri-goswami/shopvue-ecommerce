import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref<Array<{
    id: number,
    message: string,
    type: 'success' | 'error' | 'info',
    timeout: number
  }>>([])

  let nextId = 0

  const show = (message: string, type: 'success' | 'error' | 'info' = 'info', timeout = 3000) => {
    const id = nextId++
    const notification = { id, message, type, timeout }
    notifications.value.push(notification)

    if (timeout > 0) {
      setTimeout(() => {
        remove(id)
      }, timeout)
    }

    return id
  }

  const remove = (id: number) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }

  return {
    notifications,
    show,
    remove
  }
})
