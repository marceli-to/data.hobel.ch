<template>
  <ToastNotification
    :show="toast.show"
    :message="toast.message"
    :type="toast.type"
  />

  <div class="flex-1 container mx-auto p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
      <div>
        <h1 class="text-2xl font-semibold text-black mb-1">Products</h1>
        <p class="text-sm text-gray-500">{{ products.length }} items</p>
      </div>

      <div class="flex items-center gap-2">
        <button
          @click="filtersLightbox = true"
          class="bg-gray-50 border border-gray-400 text-black text-sm px-3 py-2 hover:bg-gray-100 transition-colors cursor-pointer rounded-sm flex items-center gap-2"
        >
          <PhFunnelSimple class="w-4 h-4" />
          Filter
          <span v-if="selectedCategories.length > 0 || selectedState" class="bg-black text-white text-xs p-1 w-5 h-5 leading-none rounded-full">
            {{ selectedCategories.length + (selectedState ? 1 : 0) }}
          </span>
        </button>
        <button
          v-if="visibleSelectedProducts.length > 0"
          @click="multiEditLightbox = true"
          class="bg-black text-white text-sm px-3 py-2 hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
        >
          Edit {{ visibleSelectedProducts.length }} selected
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Loading...
    </div>

    <!-- Table -->
    <div v-else class="overflow-auto">
      <table class="w-full">
        <thead class="sticky top-0 bg-gray-50 z-10">
          <tr class="border-b border-gray-200">
            <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
            <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
            <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
            <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              <button @click="sortBy('categories')" class="uppercase cursor-pointer flex items-center gap-1 hover:text-black transition-colors">
                Categories
                <span v-if="sortColumn === 'categories'" class="text-black">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
            <th class="py-3 px-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="py-3 px-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              <button @click="sortBy('variations')" class="uppercase cursor-pointer flex items-center gap-1 ml-auto hover:text-black transition-colors">
                Variations
                <span v-if="sortColumn === 'variations'" class="text-black">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
            <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              <button @click="toggleSelectAll" class="text-gray-400 hover:text-black transition-colors cursor-pointer mt-1">
                <PhCheckSquare v-if="allVisibleSelected" class="w-5 h-5 text-black" />
                <PhSquare v-else class="w-5 h-5" />
              </button>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr
            v-for="product in products"
            :key="product.id"
            :class="{
              'bg-green-50': product.done_at,
              'bg-gray-50': !product.done_at && selectedProducts.includes(product.id)
            }"
          >
            <td class="py-2 pr-2">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-100 flex-shrink-0">
                  <img
                    v-if="product.images && product.images.length > 0"
                    :src="product.images[0].url"
                    :alt="product.name"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div>
                  <span class="text-sm font-medium text-black">{{ product.name }}</span>
                </div>
              </div>
            </td>
            <td class="py-4 px-2">
              <span
                class="inline-block px-1 py-1 rounded-sm text-xs leading-none"
                :class="product.type === 'simple' ? 'bg-gray-200 text-gray-600' : 'bg-black text-white'"
              >
                {{ product.type }}
              </span>
            </td>
            <td class="py-4 pl-2 pr-6 text-sm text-gray-500 max-w-[200px] truncate">
              {{ product.sku || '—' }}
            </td>
            <td class="py-4 px-2 text-sm text-gray-500">
              <span v-if="product.product_categories && product.product_categories.length > 0">
                {{ product.product_categories.map(c => c.name).join(', ') }}
              </span>
              <span v-else>—</span>
            </td>
            <td class="py-4 px-2 text-sm text-gray-500">
              <span v-if="product.product_tags && product.product_tags.length > 0">
                {{ product.product_tags.map(t => t.name).join(', ') }}
              </span>
              <span v-else>—</span>
            </td>
            <td class="py-4 px-2 text-sm text-right text-gray-900 tabular-nums">
              <span v-if="product.price">{{ product.price }}</span>
              <span v-else class="text-gray-400">—</span>
            </td>
            <td class="py-4 px-2 text-sm text-right">
              <button
                v-if="product.variations_count > 0"
                @click="openVariationsLightbox(product)"
                class="text-gray-500 hover:text-black hover:underline cursor-pointer transition-colors"
              >
                {{ product.variations_count }}
              </button>
              <span v-else class="text-gray-300">0</span>
            </td>
            <td class="py-4 pl-2 pr-1 text-right flex items-center justify-end gap-2">
              <router-link
                :to="{ name: 'products.edit', params: { id: product.id } }"
                class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                title="Edit Product"
              >
                <PhPencilSimple class="w-5 h-5" />
              </router-link>
              <button
                v-if="product.id === 146"
                @click="openAnalysisLightbox(product)"
                class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                title="Price Analysis"
              >
                <PhChartBar class="w-5 h-5" />
              </button>
              <button
                @click="remarksLightboxProduct = product"
                class="transition-colors cursor-pointer"
                :class="product.remarks_count > 0 ? 'text-green-500 hover:text-green-600' : 'text-gray-400 hover:text-black'"
                title="View Remarks"
              >
                <PhChatText class="w-5 h-5" />
              </button>
            </td>
            <td class="py-4 pl-2 text-right">
              <button @click="toggleProductSelection(product.id)" class="text-gray-400 hover:text-black transition-colors cursor-pointer">
                <PhCheckSquare v-if="selectedProducts.includes(product.id)" class="w-5 h-5 text-black" />
                <PhSquare v-else class="w-5 h-5" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Filters Lightbox -->
  <FiltersLightbox
    :show="filtersLightbox"
    :categories="categories"
    :selected-categories="selectedCategories"
    :selected-state="selectedState"
    @close="filtersLightbox = false"
    @toggle-category="toggleCategory"
    @toggle-state="toggleState"
    @clear-filters="clearFilters"
  />

  <!-- Variations Lightbox -->
  <VariationsLightbox
    :product="variationsLightboxProduct"
    @close="variationsLightboxProduct = null"
  />

  <!-- Pricing Analysis Lightbox -->
  <PricingAnalysisLightbox
    :product="analysisLightboxProduct"
    @close="analysisLightboxProduct = null"
  />

  <!-- Multi-Edit Lightbox -->
  <MultiEditLightbox
    :show="multiEditLightbox"
    :selected-count="visibleSelectedProducts.length"
    :product-ids="visibleSelectedProducts"
    @close="multiEditLightbox = false"
    @submit="handleMultiEditSubmit"
  />

  <!-- Remarks Lightbox -->
  <RemarksLightbox
    :product="remarksLightboxProduct"
    @close="remarksLightboxProduct = null"
    @remark-added="handleRemarkAdded"
    @remark-deleted="handleRemarkDeleted"
  />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ToastNotification from '../ToastNotification.vue';
import VariationsLightbox from '../lightbox/Variations.vue';
import PricingAnalysisLightbox from '../lightbox/PricingAnalysis.vue';
import RemarksLightbox from '../lightbox/Remarks.vue';
import MultiEditLightbox from '../lightbox/MultiEdit.vue';
import FiltersLightbox from '../lightbox/Filters.vue';
import { PhChatText, PhSquare, PhCheckSquare, PhChartBar, PhPencilSimple, PhFunnelSimple } from '@phosphor-icons/vue';

const allProducts = ref([]);
const loading = ref(true);
const sortColumn = ref(localStorage.getItem('products_sortColumn') || null);
const sortDirection = ref(localStorage.getItem('products_sortDirection') || 'asc');
const variationsLightboxProduct = ref(null);
const analysisLightboxProduct = ref(null);
const remarksLightboxProduct = ref(null);
const selectedCategories = ref([]);
const selectedState = ref(null);
const selectedProducts = ref([]);
const multiEditLightbox = ref(false);
const filtersLightbox = ref(false);

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

const categories = computed(() => {
  const uniqueCategories = new Set();
  allProducts.value.forEach(product => {
    if (product.categories) {
      const cats = product.categories.split(/[,>]/).map(c => c.trim()).filter(c => c);
      cats.forEach(cat => uniqueCategories.add(cat));
    }
  });
  return Array.from(uniqueCategories).sort();
});

const products = computed(() => {
  let filtered = allProducts.value;

  // Filter by state
  if (selectedState.value === 'done') {
    filtered = filtered.filter(product => product.done_at !== null);
  } else if (selectedState.value === 'pending') {
    filtered = filtered.filter(product => product.done_at === null);
  }

  // Filter by category
  if (selectedCategories.value.length > 0) {
    filtered = filtered.filter(product => {
      if (!product.categories) return false;
      const productCategories = product.categories.split(/[,>]/).map(c => c.trim()).filter(c => c);
      return selectedCategories.value.some(selected => productCategories.includes(selected));
    });
  }

  if (!sortColumn.value) {
    return filtered;
  }

  const sorted = [...filtered].sort((a, b) => {
    let aVal, bVal;

    if (sortColumn.value === 'categories') {
      aVal = a.product_categories?.map(c => c.name).join(', ') || '';
      bVal = b.product_categories?.map(c => c.name).join(', ') || '';
    } else if (sortColumn.value === 'variations') {
      aVal = a.variations_count || 0;
      bVal = b.variations_count || 0;
    }

    if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1;
    if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });

  return sorted;
});

const allVisibleSelected = computed(() => {
  if (products.value.length === 0) return false;
  return products.value.every(product => selectedProducts.value.includes(product.id));
});

const visibleSelectedProducts = computed(() => {
  const visibleIds = products.value.map(p => p.id);
  return selectedProducts.value.filter(id => visibleIds.includes(id));
});

const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
  localStorage.setItem('products_sortColumn', sortColumn.value);
  localStorage.setItem('products_sortDirection', sortDirection.value);
};

const openVariationsLightbox = async (product) => {
  try {
    const response = await axios.get(`/api/products/${product.id}/variations`);
    variationsLightboxProduct.value = {
      ...product,
      variations: response.data
    };
  } catch (error) {
    console.error('Error fetching variations:', error);
  }
};

const openAnalysisLightbox = async (product) => {
  try {
    const response = await axios.get(`/api/products/${product.id}/variations`);
    analysisLightboxProduct.value = {
      ...product,
      variations: response.data
    };
  } catch (error) {
    console.error('Error fetching variations:', error);
  }
};

const handleRemarkAdded = (productId) => {
  const productIndex = allProducts.value.findIndex(p => p.id === productId);
  if (productIndex !== -1) {
    allProducts.value[productIndex].remarks_count++;
  }
  showToast('Remark added successfully!', 'success');
};

const handleRemarkDeleted = (productId) => {
  const productIndex = allProducts.value.findIndex(p => p.id === productId);
  if (productIndex !== -1 && allProducts.value[productIndex].remarks_count > 0) {
    allProducts.value[productIndex].remarks_count--;
  }
  showToast('Remark deleted successfully!', 'success');
};

const toggleCategory = (category) => {
  const index = selectedCategories.value.indexOf(category);
  if (index > -1) {
    selectedCategories.value.splice(index, 1);
  } else {
    selectedCategories.value.push(category);
  }
};

const toggleState = (state) => {
  selectedState.value = selectedState.value === state ? null : state;
};

const clearFilters = () => {
  selectedCategories.value = [];
  selectedState.value = null;
};

const toggleProductSelection = (productId) => {
  const index = selectedProducts.value.indexOf(productId);
  if (index > -1) {
    selectedProducts.value.splice(index, 1);
  } else {
    selectedProducts.value.push(productId);
  }
};

const toggleSelectAll = () => {
  if (allVisibleSelected.value) {
    const visibleIds = products.value.map(p => p.id);
    selectedProducts.value = selectedProducts.value.filter(id => !visibleIds.includes(id));
  } else {
    const visibleIds = products.value.map(p => p.id);
    const newSelections = visibleIds.filter(id => !selectedProducts.value.includes(id));
    selectedProducts.value.push(...newSelections);
  }
};

const handleMultiEditSubmit = async (payload) => {
  try {
    await axios.post('/api/products/bulk-edit', payload);

    const response = await axios.get('/api/products');
    allProducts.value = response.data;

    const editedIds = visibleSelectedProducts.value;
    selectedProducts.value = selectedProducts.value.filter(id => !editedIds.includes(id));
    multiEditLightbox.value = false;

    showToast('Records updated successfully!', 'success');
  } catch (error) {
    console.error('Error updating records:', error);
    showToast('Error updating records. Please try again.', 'error');
  }
};

onMounted(async () => {
  try {
    const response = await axios.get('/api/products');
    allProducts.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    loading.value = false;
  }
});
</script>
