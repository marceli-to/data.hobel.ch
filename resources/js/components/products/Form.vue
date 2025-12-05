<template>
  <div class="flex-1 container mx-auto max-w- p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <h1 class="text-2xl font-semibold text-black mb-1">
          {{ isEditing ? product?.name : 'New Product' }}
        </h1>
        <router-link 
          :to="{ name: 'products.index' }" 
          class="text-sm text-gray-500 hover:text-black transition-colors flex items-center gap-1 mb-2"
        >
          <PhArrowLeft class="w-4 h-4" />
          Back to Products
        </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Loading...
    </div>

    <!-- Form -->
    <div v-else-if="product" class="max-w-4xl">
      <form @submit.prevent="saveProduct" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
          <input
            v-model="product.name"
            type="text"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>

        <!-- SKU -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">SKU</label>
          <input
            v-model="product.sku"
            type="text"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>

        <!-- Type -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Type</label>
          <select
            v-model="product.type"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          >
            <option value="simple">Simple</option>
            <option value="variable">Variable</option>
          </select>
        </div>

        <!-- Price -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Price</label>
          <input
            v-model="product.price"
            type="text"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
          <button
            type="submit"
            :disabled="saving"
            class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            {{ saving ? 'Saving...' : 'Save' }}
          </button>
          <router-link
            :to="{ name: 'products.index' }"
            class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
          >
            Cancel
          </router-link>
        </div>
      </form>

      <!-- Variations -->
      <div v-if="isEditing && variations.length > 0" class="mt-12">
        <h2 class="text-lg font-semibold text-black mb-4">Variations</h2>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr class="border-b border-gray-200">
                <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="py-3 px-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-10"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="variation in variations"
                :key="variation.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="py-3 pr-2 text-sm text-black">{{ variation.name }}</td>
                <td class="py-3 px-2 text-sm text-gray-500">{{ variation.sku || '—' }}</td>
                <td class="py-3 px-2 text-sm text-right text-gray-900 tabular-nums">
                  {{ variation.price ? variation.price : '—' }}
                </td>
                <td class="py-3 pl-2 text-right">
                  <button
                    @click="editVariation(variation)"
                    class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                    title="Edit Variation"
                  >
                    <PhPencilSimple class="w-5 h-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Variation Edit Lightbox -->
  <VariationEditLightbox
    :variation="editingVariation"
    :product-id="route.params.id"
    @close="editingVariation = null"
    @saved="fetchVariations"
  />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { PhArrowLeft, PhPencilSimple } from '@phosphor-icons/vue';
import VariationEditLightbox from '../lightbox/VariationEdit.vue';

const route = useRoute();
const router = useRouter();

const product = ref(null);
const variations = ref([]);
const loading = ref(true);
const saving = ref(false);
const editingVariation = ref(null);

const isEditing = computed(() => !!route.params.id);

const fetchProduct = async () => {
  if (!route.params.id) {
    product.value = {
      name: '',
      sku: '',
      type: 'simple',
      price: ''
    };
    loading.value = false;
    return;
  }

  try {
    const response = await axios.get(`/api/products/${route.params.id}`);
    product.value = response.data;
    await fetchVariations();
  } catch (error) {
    console.error('Error fetching product:', error);
  } finally {
    loading.value = false;
  }
};

const fetchVariations = async () => {
  if (!route.params.id) return;
  
  try {
    const response = await axios.get(`/api/products/${route.params.id}/variations`);
    variations.value = response.data;
  } catch (error) {
    console.error('Error fetching variations:', error);
  }
};

const editVariation = (variation) => {
  editingVariation.value = variation;
};

const saveProduct = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/products/${route.params.id}`, product.value);
    } else {
      await axios.post('/api/products', product.value);
    }
    router.push({ name: 'products.index' });
  } catch (error) {
    console.error('Error saving product:', error);
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  fetchProduct();
});
</script>
