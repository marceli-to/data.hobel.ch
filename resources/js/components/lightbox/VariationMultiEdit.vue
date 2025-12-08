<template>
  <div
    v-if="show"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-lg max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">{{ selectedCount }} {{ selectedCount > 1 ? 'Varianten' : 'Variante' }} bearbeiten</h2>
          <p class="text-sm text-gray-500">Nur ausgefüllte Felder werden aktualisiert</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div class="overflow-y-auto flex-1 p-4">
        <form @submit.prevent="submit" class="space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Leer lassen um aktuellen Wert beizubehalten"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- Label -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bezeichnung</label>
            <input
              v-model="form.label"
              type="text"
              placeholder="Leer lassen um aktuellen Wert beizubehalten"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">SKU</label>
            <input
              v-model="form.sku"
              type="text"
              placeholder="Leer lassen um aktuellen Wert beizubehalten"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            />
          </div>

          <!-- Price -->
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Preis</label>
            <input
              v-model="form.price"
              type="text"
              placeholder="Leer lassen um aktuellen Wert beizubehalten"
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
          @click="submit"
          :disabled="saving || !hasChanges"
          class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
          {{ saving ? 'Speichert...' : 'Aktualisieren' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { PhX } from '@phosphor-icons/vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  productId: {
    type: [Number, String],
    required: true
  },
  selectedVariationIds: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'saved']);

const form = ref({
  name: '',
  label: '',
  sku: '',
  price: ''
});
const saving = ref(false);

const selectedCount = computed(() => props.selectedVariationIds.length);

const hasChanges = computed(() => {
  return form.value.name || form.value.label || form.value.sku || form.value.price;
});

const submit = async () => {
  if (!hasChanges.value) return;

  saving.value = true;
  try {
    const updates = {};
    if (form.value.name) updates.name = form.value.name;
    if (form.value.label) updates.label = form.value.label;
    if (form.value.sku) updates.sku = form.value.sku;
    if (form.value.price) updates.price = form.value.price;

    await axios.post(`/api/products/${props.productId}/variations/bulk-update`, {
      variation_ids: props.selectedVariationIds,
      updates
    });

    // Reset form
    form.value = { name: '', label: '', sku: '', price: '' };
    
    emit('saved');
    emit('close');
  } catch (error) {
    console.error('Error updating variations:', error);
  } finally {
    saving.value = false;
  }
};
</script>
