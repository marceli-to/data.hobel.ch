<template>
  <div class="flex-1 container mx-auto p-4">
    <div class="mb-6">
      <h1 class="text-2xl font-bold mb-1">Products shop.hobel.ch</h1>
      Total Products: {{ products.length }}
    </div>
    <div v-if="loading" class="text-center py-8">
      Loading...
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full _bg-white _border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-2 pl-0 border-b border-b-gray-300 text-left text-sm font-medium text-black">Product</th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">Type</th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">SKU</th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">
              <button @click="sortBy('categories')" class="cursor-pointer flex items-center gap-1">
                Categories
                <span v-if="sortColumn === 'categories'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">Price</th>
            <th class="p-2 border-b border-b-gray-300 text-sm font-medium text-black text-right">
              <button @click="sortBy('variations')" class="cursor-pointer flex items-center gap-1 ml-auto">
                Variations
                <span v-if="sortColumn === 'variations'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="p-2 pl-0 border-b border-b-gray-300 text-sm">
              <div class="flex items-center gap-4">
                <img
                  v-if="product.images && product.images.length > 0"
                  :src="product.images[0].url"
                  :alt="product.name"
                  class="w-[40px] h-[40px] object-cover"
                />
                <div>
                  {{ product.name }}
                </div>
              </div>
            </td>
            <td class="p-2 border-b border-b-gray-300 text-sm">
              <span class="inline-block px-2 py-0.5 rounded text-xs" :class="product.type === 'simple' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'">
                {{ product.type }}
              </span>
            </td>
            <td class="p-2 border-b border-b-gray-300 text-sm">{{ product.sku || '-' }}</td>
            <td class="p-2 border-b border-b-gray-300 text-sm">{{ product.categories || '-' }}</td>
            <td class="p-2 border-b border-b-gray-300 text-sm">
              <span v-if="product.price">{{ product.price }}</span>
              <span v-else>-</span>
            </td>
            <td class="p-2 border-b border-b-gray-300 text-sm text-right">
              <div class="flex items-center justify-end gap-2">
                <button
                  v-if="product.variations_count > 0"
                  @click="openLightbox(product)"
                  class="hover:underline decoration-1 underline-offset-2 cursor-pointer"
                >
                  {{ product.variations_count }}
                </button>
                <span v-else>0</span>
                <button
                  v-if="product.id === 364"
                  @click="openAnalysisLightbox(product)"
                  class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 cursor-pointer"
                  title="Price Analysis & Calculator"
                >
                  📊
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer with category filters -->
  <div class="bg-gray-50 border-t border-gray-300 sticky bottom-0">
    <div class="container mx-auto p-4">
      <div class="flex flex-wrap gap-2">
        <button
          v-for="category in categories"
          :key="category"
          @click="toggleCategory(category)"
          :class="[
            'px-3 py-1 rounded text-sm border transition-colors',
            selectedCategories.includes(category)
              ? 'bg-black text-white border-black'
              : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'
          ]"
        >
          {{ category }}
        </button>
      </div>
    </div>
  </div>

  <!-- Lightbox for variations -->
  <div
    v-if="lightboxProduct"
    @click="closeLightbox"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] flex flex-col"
    >
      <div class="flex justify-between items-start p-4 pb-4 border-b border-gray-200">
        <h2 class="text-xl"><strong>{{ lightboxProduct.name }}</strong><br>Variations ({{ lightboxProduct.variations?.length || 0 }})</h2>
        <button
          @click="closeLightbox"
          class="text-gray-500 hover:text-gray-700 text-2xl flex items-center justify-center w-8 h-8 pb-2 pt-1 -mr-2 -mt-2 leading-none cursor-pointer">
          ×
        </button>
      </div>

      <div v-if="lightboxProduct.variations && lightboxProduct.variations.length > 0" class="overflow-y-auto p-4 pt-4">
        <!-- Regular Variations List -->
        <div class="space-y-3">
          <div
            v-for="variation in lightboxProduct.variations"
            :key="variation.id"
            class="border-b border-gray-200 pb-4"
          >
            <div class="flex items-start gap-4">
              <img
                v-if="variation.image"
                :src="variation.image"
                :alt="variation.name"
                class="w-16 h-16 object-cover rounded"
              />
              <div class="flex-1">
                <div class="font-medium">{{ variation.name }}</div>
                <div class="text-sm  mt-1">SKU: {{ variation.sku || '-' }}</div>
                <div v-if="variation.attribute_values" class="text-sm  mt-1">
                  <span v-for="(value, key) in variation.attribute_values" :key="key" class="inline-block mr-3">
                   {{ key }}: {{ value }}
                  </span>
                </div>
                <div class="flex gap-4 mt-2 text-sm">
                  <span v-if="variation.price" class="font-medium">CHF {{ variation.price }}</span>
                  <span :class="variation.in_stock ? 'text-green-600' : 'text-red-600'">
                    {{ variation.in_stock ? 'In Stock' : 'Out of Stock' }}
                  </span>
                  <span v-if="variation.stock">Stock: {{ variation.stock }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-gray-500 text-sm p-6">
        No variations found.
      </div>
    </div>
  </div>

  <!-- Pricing Analysis Lightbox -->
  <div
    v-if="analysisLightboxProduct"
    @click="closeAnalysisLightbox"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white rounded-lg max-w-5xl w-full max-h-[90vh] flex flex-col"
    >
      <div class="flex justify-between items-start p-4 pb-4 border-b border-gray-200">
        <h2 class="text-xl"><strong>{{ analysisLightboxProduct.name }}</strong><br><span class="text-base font-normal">Price Analysis & Calculator</span></h2>
        <button
          @click="closeAnalysisLightbox"
          class="text-gray-500 hover:text-gray-700 text-2xl flex items-center justify-center w-8 h-8 pb-2 pt-1 -mr-2 -mt-2 leading-none cursor-pointer">
          ×
        </button>
      </div>

      <div class="overflow-y-auto p-4 pt-4">
        <PricingAnalysis :variations="analysisLightboxProduct.variations" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import PricingAnalysis from './PricingAnalysis.vue';

const allProducts = ref([]);
const loading = ref(true);
const sortColumn = ref(null);
const sortDirection = ref('asc');
const lightboxProduct = ref(null);
const analysisLightboxProduct = ref(null);
const selectedCategories = ref([]);

const categories = computed(() => {
  const uniqueCategories = new Set();
  allProducts.value.forEach(product => {
    if (product.categories) {
      // Split categories by comma or ">" separator
      const cats = product.categories.split(/[,>]/).map(c => c.trim()).filter(c => c);
      cats.forEach(cat => uniqueCategories.add(cat));
    }
  });
  return Array.from(uniqueCategories).sort();
});

const products = computed(() => {
  let filtered = allProducts.value;

  // Filter by selected categories
  if (selectedCategories.value.length > 0) {
    filtered = filtered.filter(product => {
      if (!product.categories) return false;
      const productCategories = product.categories.split(/[,>]/).map(c => c.trim()).filter(c => c);
      return selectedCategories.value.some(selected => productCategories.includes(selected));
    });
  }

  // Sort if a sort column is selected
  if (!sortColumn.value) {
    return filtered;
  }

  const sorted = [...filtered].sort((a, b) => {
    let aVal, bVal;

    if (sortColumn.value === 'categories') {
      aVal = a.categories || '';
      bVal = b.categories || '';
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

const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

const openLightbox = async (product) => {
  // Fetch variations for this product
  try {
    const response = await axios.get(`/api/wc-products/${product.id}/variations`);
    lightboxProduct.value = {
      ...product,
      variations: response.data
    };
  } catch (error) {
    console.error('Error fetching variations:', error);
  }
};

const closeLightbox = () => {
  lightboxProduct.value = null;
};

const openAnalysisLightbox = async (product) => {
  try {
    const response = await axios.get(`/api/wc-products/${product.id}/variations`);
    analysisLightboxProduct.value = {
      ...product,
      variations: response.data
    };
  } catch (error) {
    console.error('Error fetching variations:', error);
  }
};

const closeAnalysisLightbox = () => {
  analysisLightboxProduct.value = null;
};

const toggleCategory = (category) => {
  const index = selectedCategories.value.indexOf(category);
  if (index > -1) {
    selectedCategories.value.splice(index, 1);
  } else {
    selectedCategories.value.push(category);
  }
};

onMounted(async () => {
  try {
    const response = await axios.get('/api/wc-products');
    allProducts.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
  } finally {
    loading.value = false;
  }
});
</script>
