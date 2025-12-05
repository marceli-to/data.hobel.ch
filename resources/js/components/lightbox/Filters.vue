<template>
  <div
    v-if="show"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-2xl max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">Filters</h2>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div class="overflow-y-auto flex-1 p-4 space-y-6">
        <!-- State Filter -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">State</label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="state in states"
              :key="state.value"
              @click="toggleState(state.value)"
              :class="[
                'px-2 py-2 text-xs transition-colors cursor-pointer rounded-sm',
                selectedState === state.value
                  ? 'bg-black text-white'
                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
              ]"
            >
              {{ state.label }}
            </button>
          </div>
        </div>

        <!-- Category Filter -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category</label>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="category in categories"
              :key="category"
              @click="toggleCategory(category)"
              :class="[
                'px-2 py-2 text-xs transition-colors cursor-pointer rounded-sm',
                selectedCategories.includes(category)
                  ? 'bg-black text-white'
                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
              ]"
            >
              {{ category }}
            </button>
          </div>
        </div>
      </div>

      <div class="p-4 border-t border-gray-200 flex gap-3 justify-between">
        <button
          v-if="selectedCategories.length > 0 || selectedState"
          @click="clearFilters"
          class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
        >
          Clear all
        </button>
        <div class="flex-1"></div>
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
        >
          Done
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { PhX } from '@phosphor-icons/vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  categories: {
    type: Array,
    default: () => []
  },
  selectedCategories: {
    type: Array,
    default: () => []
  },
  selectedState: {
    type: String,
    default: null
  }
});

const emit = defineEmits(['close', 'toggle-category', 'toggle-state', 'clear-filters']);

const states = [
  { value: 'pending', label: 'Pending' },
  { value: 'done', label: 'Done' }
];

const toggleCategory = (category) => {
  emit('toggle-category', category);
};

const toggleState = (state) => {
  emit('toggle-state', state);
};

const clearFilters = () => {
  emit('clear-filters');
};
</script>
