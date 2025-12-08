<template>
  <div
    v-if="product"
    @click="$emit('close')"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white w-full max-w-lg max-h-[90vh] flex flex-col rounded-md"
    >
      <div class="flex justify-between items-center p-4 border-b border-gray-200 relative">
        <div>
          <h2 class="text-lg font-semibold text-black">{{ product.name }}</h2>
          <p class="text-sm text-gray-500">Anmerkungen</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div v-if="loading" class="px-6 py-12 text-center text-gray-400 text-sm">
        Lädt...
      </div>
      <div v-else-if="remarks && remarks.length > 0" class="overflow-y-auto flex-1">
        <div class="divide-y divide-gray-100">
          <div
            v-for="remark in remarks"
            :key="remark.id"
            class="p-4 flex justify-between items-start gap-4">
            <div class="flex-1">
              <div class="text-sm text-gray-700">{{ remark.description }}</div>
              <div class="text-xs text-gray-400 mt-2">{{ formatDate(remark.created_at) }}<span v-if="remark.user"> · {{ remark.user.name }}</span></div>
            </div>
            <button
              @click="deleteRemark(remark.id)"
              class="text-gray-300 hover:text-red-500 transition-colors cursor-pointer shrink-0"
              title="Anmerkung löschen"
            >
              <PhTrash class="w-5 h-5" />
            </button>
          </div>
        </div>
      </div>
      <div v-else class="px-6 py-12 text-center text-gray-400 text-sm">
        Keine Anmerkungen gefunden.
      </div>

      <!-- Add Remark Form -->
      <div class="p-4 border-t border-gray-200">
        <div class="flex gap-3">
          <input
            v-model="newRemarkText"
            type="text"
            class="flex-1 border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            placeholder="Anmerkung hinzufügen..."
            @keyup.enter="submitRemark"
          />
          <button
            @click="submitRemark"
            :disabled="!newRemarkText.trim() || submitting"
            class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            {{ submitting ? 'Speichert...' : 'Hinzufügen' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { PhX, PhTrash } from '@phosphor-icons/vue';

const props = defineProps({
  product: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'remark-added', 'remark-deleted']);

const remarks = ref([]);
const loading = ref(false);
const newRemarkText = ref('');
const submitting = ref(false);

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('de-CH', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchRemarks = async () => {
  if (!props.product) return;
  
  loading.value = true;
  remarks.value = [];
  
  try {
    const response = await axios.get(`/api/products/${props.product.id}/remarks`);
    remarks.value = response.data;
  } catch (error) {
    console.error('Error fetching remarks:', error);
  } finally {
    loading.value = false;
  }
};

const submitRemark = async () => {
  if (!newRemarkText.value.trim() || !props.product) return;
  
  submitting.value = true;
  try {
    await axios.post(`/api/products/${props.product.id}/remarks`, {
      description: newRemarkText.value.trim()
    });
    
    // Refresh remarks list
    const response = await axios.get(`/api/products/${props.product.id}/remarks`);
    remarks.value = response.data;
    
    newRemarkText.value = '';
    emit('remark-added', props.product.id);
  } catch (error) {
    console.error('Error adding remark:', error);
  } finally {
    submitting.value = false;
  }
};

const deleteRemark = async (remarkId) => {
  try {
    await axios.delete(`/api/products/${props.product.id}/remarks/${remarkId}`);
    
    // Remove from local list
    remarks.value = remarks.value.filter(r => r.id !== remarkId);
    
    emit('remark-deleted', props.product.id);
  } catch (error) {
    console.error('Error deleting remark:', error);
  }
};

watch(() => props.product, (newProduct) => {
  if (newProduct) {
    fetchRemarks();
  } else {
    remarks.value = [];
    newRemarkText.value = '';
  }
}, { immediate: true });
</script>
