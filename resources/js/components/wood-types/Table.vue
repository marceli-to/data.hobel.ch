<template>
  <ToastNotification
    :show="toast.show"
    :message="toast.message"
    :type="toast.type"
  />

  <div class="flex-1 container mx-auto py-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
      <div>
        <h1 class="text-2xl font-semibold text-black mb-1">Wood Types</h1>
        <p class="text-sm text-gray-500">{{ woodTypes.length }} items</p>
      </div>

      <router-link
        :to="{ name: 'wood-types.create' }"
        class="bg-black text-white text-sm px-3 py-2 hover:bg-gray-800 transition-colors cursor-pointer rounded-sm flex items-center gap-1"
      >
        <PhPlus class="w-4 h-4" />
        Add
      </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Loading...
    </div>

    <!-- Table -->
    <div v-else class="overflow-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200">
            <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <button @click="sortBy('name')" class="uppercase cursor-pointer flex items-center gap-1 hover:text-black transition-colors">
                Name
                <span v-if="sortColumn === 'name'" class="text-black">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="py-3 px-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              <button @click="sortBy('price')" class="uppercase cursor-pointer flex items-center gap-1 ml-auto hover:text-black transition-colors">
                Price (Rate)
                <span v-if="sortColumn === 'price'" class="text-black">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="woodType in sortedWoodTypes"
            :key="woodType.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="py-4 pr-2">
              <span class="text-sm font-medium text-black">{{ woodType.name }}</span>
            </td>
            <td class="py-4 px-2 text-sm text-right text-gray-900 tabular-nums">
              {{ formatPrice(woodType.price) }}
            </td>
            <td class="py-4 pl-2 text-right flex items-center justify-end gap-2">
              <router-link
                :to="{ name: 'wood-types.edit', params: { id: woodType.id } }"
                class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                title="Edit Wood Type"
              >
                <PhPencilSimple class="w-5 h-5" />
              </router-link>
              <button
                @click="confirmDelete(woodType)"
                class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer"
                title="Delete Wood Type"
              >
                <PhTrash class="w-5 h-5" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Delete Confirmation Lightbox -->
  <div
    v-if="deleteConfirmation"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="deleteConfirmation = null"
  >
    <div class="bg-white p-6 rounded-sm shadow-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold text-black mb-4">Delete Wood Type</h3>
      <p class="text-sm text-gray-600 mb-6">
        Are you sure you want to delete "{{ deleteConfirmation.name }}"? This action cannot be undone.
      </p>
      <div class="flex justify-end gap-3">
        <button
          @click="deleteConfirmation = null"
          class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer"
        >
          Cancel
        </button>
        <button
          @click="deleteWoodType"
          class="px-4 py-2 text-sm bg-red-500 text-white hover:bg-red-600 transition-colors cursor-pointer rounded-sm"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ToastNotification from '../ToastNotification.vue';
import { PhPencilSimple, PhTrash, PhPlus } from '@phosphor-icons/vue';

const woodTypes = ref([]);
const loading = ref(true);
const sortColumn = ref('name');
const sortDirection = ref('asc');
const deleteConfirmation = ref(null);

const toast = ref({
  show: false,
  message: '',
  type: 'success',
});

let toastTimeout = null;

const showToast = (message, type = 'success') => {
  if (toastTimeout) {
    clearTimeout(toastTimeout);
  }
  toast.value = { show: true, message, type };
  toastTimeout = setTimeout(() => {
    toast.value.show = false;
  }, 3000);
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('de-CH', {
    style: 'currency',
    currency: 'CHF',
  }).format(price);
};

const sortedWoodTypes = computed(() => {
  const sorted = [...woodTypes.value].sort((a, b) => {
    let aVal, bVal;

    if (sortColumn.value === 'name') {
      aVal = a.name || '';
      bVal = b.name || '';
    } else if (sortColumn.value === 'price') {
      aVal = parseFloat(a.price) || 0;
      bVal = parseFloat(b.price) || 0;
    }

    if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1;
    if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });

  return sorted;
});

const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

const confirmDelete = (woodType) => {
  deleteConfirmation.value = woodType;
};

const deleteWoodType = async () => {
  if (!deleteConfirmation.value) return;

  try {
    await axios.delete(`/api/wood-types/${deleteConfirmation.value.id}`);
    woodTypes.value = woodTypes.value.filter(wt => wt.id !== deleteConfirmation.value.id);
    showToast('Wood type deleted successfully!', 'success');
  } catch (error) {
    console.error('Error deleting wood type:', error);
    showToast('Error deleting wood type. Please try again.', 'error');
  } finally {
    deleteConfirmation.value = null;
  }
};

onMounted(async () => {
  try {
    const response = await axios.get('/api/wood-types');
    woodTypes.value = response.data;
  } catch (error) {
    console.error('Error fetching wood types:', error);
  } finally {
    loading.value = false;
  }
});
</script>
