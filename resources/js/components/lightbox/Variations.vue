<template>
  <div
    v-if="product"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">{{ product.name }}</h2>
          <p class="text-sm text-gray-500">{{ product.variations?.length || 0 }} variations</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div v-if="product.variations && product.variations.length > 0" class="overflow-y-auto flex-1">
        <div class="divide-y divide-gray-100">
          <div
            v-for="variation in product.variations"
            :key="variation.id"
            class="p-4"
          >
            <div class="flex items-start gap-4">
              <div class="w-14 h-14 bg-gray-100 shrink-0">
                <img
                  v-if="variation.image"
                  :src="variation.image"
                  :alt="variation.name"
                  class="w-full h-full object-cover"
                />
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-black">{{ variation.name }}</div>
                <div class="text-xs text-gray-500 mt-1">SKU: {{ variation.sku || '—' }}</div>
                <div v-if="variation.attribute_values" class="text-xs text-gray-500 mt-1">
                  <span v-for="(value, key) in variation.attribute_values" :key="key" class="mr-3">
                    {{ key }}: {{ value }}
                  </span>
                </div>
                <div class="flex items-center gap-4 mt-2">
                  <span v-if="variation.price" class="text-sm font-medium text-black">CHF {{ variation.price }}</span>
                  <span
                    class="text-xs"
                    :class="variation.in_stock ? 'text-gray-600' : 'text-gray-400'"
                  >
                    {{ variation.in_stock ? 'In Stock' : 'Out of Stock' }}
                  </span>
                  <span v-if="variation.stock" class="text-xs text-gray-500">{{ variation.stock }} units</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="px-6 py-12 text-center text-gray-400 text-sm">
        No variations found.
      </div>
    </div>
  </div>
</template>

<script setup>
import { PhX } from '@phosphor-icons/vue';

defineProps({
  product: {
    type: Object,
    default: null
  }
});

defineEmits(['close']);
</script>
