<template>
  <div
    v-if="variation"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-lg max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">Variante bearbeiten</h2>
          <p class="text-sm text-gray-500">{{ variation.name }}</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div class="overflow-y-auto flex-1 p-4">
        <form @submit.prevent="save" class="space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- Label -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bezeichnung</label>
            <input
              v-model="form.label"
              type="text"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">SKU</label>
            <input
              v-model="form.sku"
              type="text"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- Price -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Preis</label>
            <input
              v-model="form.price"
              type="text"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- Stock -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Lagerbestand</label>
            <input
              v-model="form.stock"
              type="number"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>
        </form>
      </div>

      <div class="p-4 border-t border-gray-200 flex gap-3 justify-end">
        <button
          @click="$emit('close')"
          class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
        >
          Abbrechen
        </button>
        <button
          @click="save"
          :disabled="saving"
          class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
          {{ saving ? 'Speichert...' : 'Speichern' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { PhX } from '@phosphor-icons/vue';

const props = defineProps({
  variation: {
    type: Object,
    default: null
  },
  productId: {
    type: [Number, String],
    required: true
  }
});

const emit = defineEmits(['close', 'saved']);

const form = ref({
  name: '',
  label: '',
  sku: '',
  price: '',
  stock: null
});
const saving = ref(false);

const save = async () => {
  saving.value = true;
  try {
    await axios.put(`/api/products/${props.productId}/variations/${props.variation.id}`, form.value);
    emit('saved');
    emit('close');
  } catch (error) {
    console.error('Error saving variation:', error);
  } finally {
    saving.value = false;
  }
};

watch(() => props.variation, (newVariation) => {
  if (newVariation) {
    form.value = {
      name: newVariation.name || '',
      label: newVariation.label || '',
      sku: newVariation.sku || '',
      price: newVariation.price || '',
      stock: newVariation.stock ?? null
    };
  }
}, { immediate: true });
</script>
