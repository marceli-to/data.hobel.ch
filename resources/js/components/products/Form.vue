<template>
  <div class="flex-1 container mx-auto max-w- p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <h1 class="text-2xl font-semibold text-black mb-1">
          {{ isEditing ? product?.name : 'Neues Produkt' }}
        </h1>
        <router-link
          :to="{ name: 'products.index' }"
          class="text-sm text-gray-500 hover:text-black transition-colors flex items-center gap-1 mb-2"
        >
          <PhArrowLeft class="w-4 h-4" />
          Zurück zu Produkten
        </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Lädt...
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

        <!-- Label -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bezeichnung</label>
          <input
            v-model="product.label"
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

        <!-- Description -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Beschreibung</label>
          <textarea
            v-model="product.description"
            rows="12"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm resize-y"
          ></textarea>
        </div>

        <!-- Product Attributes -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Attribute</label>
          <div class="space-y-2">
            <draggable
              v-model="productAttributes"
              item-key="id"
              handle=".drag-handle"
              ghost-class="opacity-50"
              class="space-y-2"
            >
              <template #item="{ element: attr, index }">
                <div class="flex items-center gap-1">
                  <button
                    type="button"
                    class="drag-handle text-gray-600 hover:text-gray-500 cursor-grab active:cursor-grabbing p-0.5"
                    title="Ziehen zum Sortieren"
                  >
                    <PhDotsSixVertical class="w-4 h-4" />
                  </button>
                  <input
                    v-model="attr.description"
                    type="text"
                    class="flex-1 border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
                    placeholder="z.B. Material: Eiche massiv"
                  />
                  <button
                    type="button"
                    @click="removeAttribute(index)"
                    class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer p-2"
                    title="Attribut entfernen"
                  >
                    <PhX class="w-4 h-4" />
                  </button>
                </div>
              </template>
            </draggable>
            <button
              type="button"
              @click="addAttribute"
              class="text-sm text-gray-500 hover:text-black transition-colors cursor-pointer flex items-center gap-1"
            >
              <PhPlus class="w-4 h-4" />
              Hinzufügen
            </button>
          </div>
        </div>

        <!-- Type -->
        <!-- <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Type</label>
          <select
            v-model="product.type"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          >
            <option value="simple">Simple</option>
            <option value="variable">Variable</option>
          </select>
        </div> -->

        <!-- Price -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Preis</label>
          <input
            v-model="product.price"
            type="text"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>

        <!-- Delivery Time -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Lieferzeit</label>
          <input
            v-model="product.delivery_time"
            type="text"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            placeholder="z.B. 2-3 Wochen"
          />
        </div>

        <!-- Shipping Methods -->
        <div v-if="shippingMethods.length > 0">
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Versandarten</label>
          <div class="">
            <label
              v-for="method in shippingMethods"
              :key="method.id"
              class="flex items-center gap-2 cursor-pointer rounded-sm px-2 py-1.5"
            >
              <input
                type="checkbox"
                :value="method.id"
                v-model="selectedShippingMethods"
                class="w-[0.875rem] h-[0.875rem] cursor-pointer accent-black"
              />
              <span class="text-sm text-gray-700">
                {{ method.name }}
                <span v-if="method.price"> – {{ method.price }}</span>
              </span>
             
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
          <button
            type="submit"
            :disabled="saving"
            class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
          >
            {{ saving ? 'Speichert...' : 'Speichern' }}
          </button>
          <router-link
            :to="{ name: 'products.index' }"
            class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
          >
            Abbrechen
          </router-link>
        </div>
      </form>

      <!-- Variations -->
      <div v-if="isEditing && variations.length > 0" class="mt-12">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-black">Varianten</h2>
          <button
            v-if="selectedVariations.length > 0"
            @click="showMultiEdit = true"
            class="px-3 py-1.5 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
          >
            {{ selectedVariations.length }} ausgewählt bearbeiten
          </button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bezeichnung</th>
                <th class="py-3 px-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="py-3 px-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Preis</th>
                <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-20"></th>
                <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                  <button @click="toggleAllVariations" class="text-gray-400 hover:text-black transition-colors cursor-pointer">
                    <PhCheckSquare v-if="allVariationsSelected" class="w-5 h-5" />
                    <PhSquare v-else class="w-5 h-5" />
                  </button>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="variation in variations"
                :key="variation.id"
                class="hover:bg-gray-50 transition-colors"
                :class="{ 'bg-gray-50': selectedVariations.includes(variation.id) }"
              >
                  <td class="py-3 pr-2 text-sm text-black">{{ variation.name }}</td>
                <td class="py-3 pr-2 text-sm text-black">{{ variation.label }}</td>
                <td class="py-3 px-2 text-sm text-gray-500">{{ variation.sku || '—' }}</td>
                <td class="py-3 px-2 text-sm text-right text-gray-900 tabular-nums">
                  {{ variation.price ? variation.price : '—' }}
                </td>
                <td class="py-3 pl-2 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="editVariation(variation)"
                      class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                      title="Variante bearbeiten"
                    >
                      <PhPencilSimple class="w-5 h-5" />
                    </button>
                    <button
                      @click="confirmDeleteVariation(variation)"
                      class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer"
                      title="Variante löschen"
                    >
                      <PhTrash class="w-5 h-5" />
                    </button>
                  </div>
                </td>
                <td class="py-3 pl-2 text-right">
                  <button @click="toggleVariation(variation.id)" class="text-gray-400 hover:text-black transition-colors cursor-pointer">
                    <PhCheckSquare v-if="selectedVariations.includes(variation.id)" class="w-5 h-5 text-black" />
                    <PhSquare v-else class="w-5 h-5" />
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

  <!-- Variation Multi Edit Lightbox -->
  <VariationMultiEditLightbox
    :show="showMultiEdit"
    :product-id="route.params.id"
    :selected-variation-ids="selectedVariations"
    @close="showMultiEdit = false"
    @saved="handleMultiEditSaved"
  />

  <!-- Delete Variation Confirmation Lightbox -->
  <div
    v-if="deleteVariationConfirmation"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="deleteVariationConfirmation = null"
  >
    <div class="bg-white p-6 rounded-sm shadow-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold text-black mb-4">Variante löschen</h3>
      <p class="text-sm text-gray-600 mb-6">
        Sind Sie sicher, dass Sie "{{ deleteVariationConfirmation.name }}" löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
      </p>
      <div class="flex justify-end gap-3">
        <button
          @click="deleteVariationConfirmation = null"
          class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer"
        >
          Abbrechen
        </button>
        <button
          @click="deleteVariation"
          class="px-4 py-2 text-sm bg-red-500 text-white hover:bg-red-600 transition-colors cursor-pointer rounded-sm"
        >
          Löschen
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { PhArrowLeft, PhPencilSimple, PhSquare, PhCheckSquare, PhTrash, PhX, PhPlus, PhDotsSixVertical } from '@phosphor-icons/vue';
import draggable from 'vuedraggable';
import VariationEditLightbox from '../lightbox/VariationEdit.vue';
import VariationMultiEditLightbox from '../lightbox/VariationMultiEdit.vue';

const route = useRoute();
const router = useRouter();

const product = ref(null);
const variations = ref([]);
const shippingMethods = ref([]);
const selectedShippingMethods = ref([]);
const productAttributes = ref([]);
let attributeIdCounter = 0;
const loading = ref(true);
const saving = ref(false);
const editingVariation = ref(null);
const selectedVariations = ref([]);
const showMultiEdit = ref(false);
const deleteVariationConfirmation = ref(null);

const isEditing = computed(() => !!route.params.id);

const fetchShippingMethods = async () => {
  try {
    const response = await axios.get('/api/shipping-methods');
    shippingMethods.value = response.data;
  } catch (error) {
    console.error('Error fetching shipping methods:', error);
  }
};

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
    // Set selected shipping methods from product data
    if (product.value.shipping_methods) {
      selectedShippingMethods.value = product.value.shipping_methods.map(m => m.id);
    }
    // Set product attributes from product data
    if (product.value.product_attributes) {
      productAttributes.value = product.value.product_attributes.map(a => ({ 
        id: ++attributeIdCounter, 
        description: a.description 
      }));
    }
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

const allVariationsSelected = computed(() => {
  return variations.value.length > 0 && selectedVariations.value.length === variations.value.length;
});

const toggleVariation = (id) => {
  const index = selectedVariations.value.indexOf(id);
  if (index === -1) {
    selectedVariations.value.push(id);
  } else {
    selectedVariations.value.splice(index, 1);
  }
};

const toggleAllVariations = () => {
  if (allVariationsSelected.value) {
    selectedVariations.value = [];
  } else {
    selectedVariations.value = variations.value.map(v => v.id);
  }
};

const handleMultiEditSaved = () => {
  selectedVariations.value = [];
  fetchVariations();
};

const confirmDeleteVariation = (variation) => {
  deleteVariationConfirmation.value = variation;
};

const deleteVariation = async () => {
  if (!deleteVariationConfirmation.value) return;

  try {
    await axios.delete(`/api/products/${route.params.id}/variations/${deleteVariationConfirmation.value.id}`);
    variations.value = variations.value.filter(v => v.id !== deleteVariationConfirmation.value.id);
  } catch (error) {
    console.error('Error deleting variation:', error);
  } finally {
    deleteVariationConfirmation.value = null;
  }
};

const addAttribute = () => {
  productAttributes.value.push({ id: ++attributeIdCounter, description: '' });
};

const removeAttribute = (index) => {
  productAttributes.value.splice(index, 1);
};

const saveProduct = async () => {
  saving.value = true;
  try {
    // Filter out empty attributes
    const filteredAttributes = productAttributes.value.filter(a => a.description.trim());
    const payload = {
      ...product.value,
      shipping_method_ids: selectedShippingMethods.value,
      product_attributes: filteredAttributes
    };
    if (isEditing.value) {
      await axios.put(`/api/products/${route.params.id}`, payload);
    } else {
      await axios.post('/api/products', payload);
    }
    router.push({ name: 'products.index' });
  } catch (error) {
    console.error('Error saving product:', error);
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  fetchShippingMethods();
  fetchProduct();
});
</script>
