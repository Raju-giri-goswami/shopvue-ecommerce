<template>
  <transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="mb-4">
              <h3 class="text-lg font-medium text-gray-900">
                {{ product ? 'Edit Product' : 'Add New Product' }}
              </h3>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="input-field mt-1"
                />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  required
                  rows="3"
                  class="input-field mt-1"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                  <input
                    id="price"
                    v-model.number="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="input-field mt-1"
                  />
                </div>

                <div>
                  <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                  <input
                    id="stock"
                    v-model.number="form.stock"
                    type="number"
                    min="0"
                    required
                    class="input-field mt-1"
                  />
                </div>
              </div>

              <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <input
                  id="category"
                  v-model="form.category"
                  type="text"
                  required
                  class="input-field mt-1"
                />
              </div>

              <div>
                <label for="imageUrl" class="block text-sm font-medium text-gray-700">Image URL</label>
                <input
                  id="imageUrl"
                  v-model="form.image_url"
                  type="url"
                  required
                  class="input-field mt-1"
                />
              </div>

              <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
                <p class="text-sm text-red-600">{{ error }}</p>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="btn-secondary"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="btn-primary disabled:opacity-50"
                >
                  <span v-if="loading">{{ product ? 'Updating...' : 'Creating...' }}</span>
                  <span v-else>{{ product ? 'Update Product' : 'Create Product' }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { Product } from '../stores/products'

const props = defineProps<{
  show: boolean
  product?: Product | null
}>()

const emit = defineEmits<{
  close: []
  save: [productData: Omit<Product, 'id' | 'created_at' | 'updated_at'>]
}>()

const form = reactive({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  category: '',
  image_url: ''
})

const loading = ref(false)
const error = ref('')

const handleSubmit = async () => {
  loading.value = true
  error.value = ''

  try {
    emit('save', { ...form })
  } catch (err: any) {
    error.value = err.message || 'Failed to save product'
  } finally {
    loading.value = false
  }
}

// Reset form when modal opens/closes or product changes
watch([() => props.show, () => props.product], () => {
  if (props.show) {
    if (props.product) {
      Object.assign(form, {
        name: props.product.name,
        description: props.product.description,
        price: props.product.price,
        stock: props.product.stock,
        category: props.product.category,
        image_url: props.product.image_url
      })
    } else {
      Object.assign(form, {
        name: '',
        description: '',
        price: 0,
        stock: 0,
        category: '',
        image_url: ''
      })
    }
  }
  error.value = ''
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>