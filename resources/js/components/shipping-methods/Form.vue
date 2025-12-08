<template>
  <div class="flex-1 container mx-auto p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <h1 class="text-2xl font-semibold text-black mb-1">
          {{ isEditing ? shippingMethod?.name : 'Neue Versandart' }}
        </h1>
        <router-link
          :to="{ name: 'shipping-methods.index' }"
          class="text-sm text-gray-500 hover:text-black transition-colors flex items-center gap-1 mb-2"
        >
          <PhArrowLeft class="w-4 h-4" />
          Zurück zu Versandarten
        </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Lädt...
    </div>

    <!-- Form -->
    <div v-else-if="shippingMethod" class="max-w-4xl">
      <form @submit.prevent="saveShippingMethod" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
          <input
            v-model="shippingMethod.name"
            type="text"
            required
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>

        <!-- Price -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Preis (CHF)</label>
          <input
            v-model="shippingMethod.price"
            type="number"
            step="0.01"
            min="0"
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
            placeholder="Optional"
          />
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
            :to="{ name: 'shipping-methods.index' }"
            class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
          >
            Abbrechen
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { PhArrowLeft } from '@phosphor-icons/vue';

const route = useRoute();
const router = useRouter();

const shippingMethod = ref(null);
const loading = ref(true);
const saving = ref(false);

const isEditing = computed(() => !!route.params.id);

const fetchShippingMethod = async () => {
  if (!route.params.id) {
    shippingMethod.value = {
      name: '',
      price: null
    };
    loading.value = false;
    return;
  }

  try {
    const response = await axios.get(`/api/shipping-methods/${route.params.id}`);
    shippingMethod.value = response.data;
  } catch (error) {
    console.error('Error fetching shipping method:', error);
  } finally {
    loading.value = false;
  }
};

const saveShippingMethod = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/shipping-methods/${route.params.id}`, shippingMethod.value);
    } else {
      await axios.post('/api/shipping-methods', shippingMethod.value);
    }
    router.push({ name: 'shipping-methods.index' });
  } catch (error) {
    console.error('Error saving shipping method:', error);
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  fetchShippingMethod();
});
</script>
