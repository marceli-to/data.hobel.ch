<template>
  <div class="flex-1 container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Products</h1>

    <div v-if="loading" class="text-center py-8">
      Loading...
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full _bg-white _border border-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-2 pl-0 border-b border-b-gray-300 text-left text-sm font-medium text-black">Product</th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">
              <button @click="sortBy('category')" class="cursor-pointer flex items-center gap-1">
                Category
                <span v-if="sortColumn === 'category'">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
              </button>
            </th>
            <th class="p-2 border-b border-b-gray-300 text-left text-sm font-medium text-black">Article No</th>
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
                  :alt="product.title"
                  class="w-[40px] h-[40px] object-cover"
                />
                <a :href="product.url" target="_blank" class="hover:underline decoration-1 underline-offset-2">
                  {{ product.title }}
                </a>
              </div>
            </td>
            <td class="p-2 border-b border-b-gray-300 text-sm">{{ product.category?.name }}</td>
            <td class="p-2 border-b border-b-gray-300 text-sm">{{ product.article_no || '-' }}</td>
            <td class="p-2 border-b border-b-gray-300 text-sm">
              <span v-if="product.price">{{ product.price }}</span>
              <span v-else-if="product.price_range">{{ product.price_range }}</span>
              <span v-else>-</span>
            </td>
            <td class="p-2 border-b border-b-gray-300 text-sm text-right">
              <button
                v-if="product.variations"
                @click="openLightbox(product)"
                class="hover:underline decoration-1 underline-offset-2 cursor-pointer"
              >
                Yes
              </button>
              <span v-else>No</span>
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
          :key="category.id"
          @click="toggleCategory(category.id)"
          :class="[
            'px-3 py-1 rounded text-sm border transition-colors',
            selectedCategories.includes(category.id)
              ? 'bg-black text-white border-black'
              : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'
          ]"
        >
          {{ category.name }}
        </button>
      </div>
    </div>
  </div>

  <!-- Lightbox -->
  <div
    v-if="lightboxProduct"
    @click="closeLightbox"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
  >
    <div
      @click.stop
      class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6"
    >
      <div class="flex justify-between items-start mb-4">
        <h2 class="text-xl font-bold">{{ lightboxProduct.title }}</h2>
        <button
          @click="closeLightbox"
          class="text-gray-500 hover:text-gray-700 text-2xl flex items-center justify-center w-8 h-8 pb-2 pt-1 -mr-2 -mt-2 leading-none cursor-pointer">
          ×
        </button>
      </div>

      <h3 class="text-lg font-semibold mb-2">Variations</h3>
      <pre class="bg-gray-50 p-4 rounded text-sm overflow-x-auto">{{ JSON.stringify(lightboxProduct.variations, null, 2) }}</pre>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const allProducts = ref([]);
const loading = ref(true);
const sortColumn = ref(null);
const sortDirection = ref('asc');
const lightboxProduct = ref(null);
const selectedCategories = ref([]);

const categories = computed(() => {
  const uniqueCategories = new Map();
  allProducts.value.forEach(product => {
    if (product.category) {
      uniqueCategories.set(product.category.id, product.category);
    }
  });
  return Array.from(uniqueCategories.values()).sort((a, b) => a.name.localeCompare(b.name));
});

const products = computed(() => {
  let filtered = allProducts.value;

  // Filter by selected categories
  if (selectedCategories.value.length > 0) {
    filtered = filtered.filter(product =>
      product.category && selectedCategories.value.includes(product.category.id)
    );
  }

  // Sort if a sort column is selected
  if (!sortColumn.value) {
    return filtered;
  }

  const sorted = [...filtered].sort((a, b) => {
    let aVal, bVal;

    if (sortColumn.value === 'category') {
      aVal = a.category?.name || '';
      bVal = b.category?.name || '';
    } else if (sortColumn.value === 'variations') {
      aVal = a.variations ? 1 : 0;
      bVal = b.variations ? 1 : 0;
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

const openLightbox = (product) => {
  lightboxProduct.value = product;
};

const closeLightbox = () => {
  lightboxProduct.value = null;
};

const toggleCategory = (categoryId) => {
  const index = selectedCategories.value.indexOf(categoryId);
  if (index > -1) {
    selectedCategories.value.splice(index, 1);
  } else {
    selectedCategories.value.push(categoryId);
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
