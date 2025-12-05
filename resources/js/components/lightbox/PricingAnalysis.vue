<template>
  <div
    v-if="product"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-4xl max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">{{ product.name }}</h2>
          <p class="text-sm text-gray-500">Price Analysis</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div class="overflow-y-auto flex-1 p-6">
        <PricingAnalysis :variations="product.variations" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { PhX } from '@phosphor-icons/vue';
import PricingAnalysis from '../PricingAnalysis.vue';

defineProps({
  product: {
    type: Object,
    default: null
  }
});

defineEmits(['close']);
</script>
