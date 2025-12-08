<template>
  <div class="flex-1 container mx-auto max-w- p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <h1 class="text-2xl font-semibold text-black mb-1">
          {{ isEditing ? category?.name : 'Neue Kategorie' }}
        </h1>
        <router-link
          :to="{ name: 'categories.index' }"
          class="text-sm text-gray-500 hover:text-black transition-colors flex items-center gap-1 mb-2"
        >
          <PhArrowLeft class="w-4 h-4" />
          Zurück zu Kategorien
        </router-link>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-16 text-gray-400">
      Lädt...
    </div>

    <!-- Form -->
    <div v-else-if="category" class="max-w-4xl">
      <form @submit.prevent="saveCategory" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
          <input
            v-model="category.name"
            type="text"
            required
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
            {{ saving ? 'Speichert...' : 'Speichern' }}
          </button>
          <router-link
            :to="{ name: 'categories.index' }"
            class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
          >
            Abbrechen
          </router-link>
        </div>
      </form>

      <!-- Tags Section -->
      <div v-if="isEditing" class="mt-12">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold text-black">Tags</h2>
          <button
            @click="showAddTag = true"
            class="px-3 py-1.5 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm flex items-center gap-1"
          >
            <PhPlus class="w-4 h-4" />
            Tag hinzufügen
          </button>
        </div>

        <div v-if="tags.length === 0" class="text-sm text-gray-500 py-4">
          Keine Tags vorhanden.
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="py-3 pr-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="py-3 pl-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-20"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="tag in tags"
                :key="tag.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="py-3 pr-2 text-sm text-black">{{ tag.name }}</td>
                <td class="py-3 pl-2 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="editTag(tag)"
                      class="text-gray-400 hover:text-black transition-colors cursor-pointer"
                      title="Tag bearbeiten"
                    >
                      <PhPencilSimple class="w-5 h-5" />
                    </button>
                    <button
                      @click="confirmDeleteTag(tag)"
                      class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer"
                      title="Tag löschen"
                    >
                      <PhTrash class="w-5 h-5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Tag Lightbox -->
  <div
    v-if="showAddTag || editingTag"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="closeTagForm"
  >
    <div class="bg-white p-6 rounded-sm shadow-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold text-black mb-4">
        {{ editingTag ? 'Tag bearbeiten' : 'Neuer Tag' }}
      </h3>
      <form @submit.prevent="saveTag">
        <div class="mb-4">
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Name</label>
          <input
            v-model="tagForm.name"
            type="text"
            required
            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors rounded-sm"
          />
        </div>
        <div class="flex justify-end gap-3">
          <button
            type="button"
            @click="closeTagForm"
            class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer"
          >
            Abbrechen
          </button>
          <button
            type="submit"
            class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
          >
            Speichern
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Tag Confirmation Lightbox -->
  <div
    v-if="deleteTagConfirmation"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    @click.self="deleteTagConfirmation = null"
  >
    <div class="bg-white p-6 rounded-sm shadow-lg max-w-md w-full mx-4">
      <h3 class="text-lg font-semibold text-black mb-4">Tag löschen</h3>
      <p class="text-sm text-gray-600 mb-6">
        Sind Sie sicher, dass Sie "{{ deleteTagConfirmation.name }}" löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
      </p>
      <div class="flex justify-end gap-3">
        <button
          @click="deleteTagConfirmation = null"
          class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer"
        >
          Abbrechen
        </button>
        <button
          @click="deleteTag"
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
import { PhArrowLeft, PhPencilSimple, PhTrash, PhPlus } from '@phosphor-icons/vue';

const route = useRoute();
const router = useRouter();

const category = ref(null);
const tags = ref([]);
const loading = ref(true);
const saving = ref(false);
const showAddTag = ref(false);
const editingTag = ref(null);
const deleteTagConfirmation = ref(null);
const tagForm = ref({ name: '' });

const isEditing = computed(() => !!route.params.id);

const fetchCategory = async () => {
  if (!route.params.id) {
    category.value = {
      name: ''
    };
    loading.value = false;
    return;
  }

  try {
    const response = await axios.get(`/api/categories/${route.params.id}`);
    category.value = response.data;
    await fetchTags();
  } catch (error) {
    console.error('Error fetching category:', error);
  } finally {
    loading.value = false;
  }
};

const fetchTags = async () => {
  if (!route.params.id) return;

  try {
    const response = await axios.get(`/api/categories/${route.params.id}/tags`);
    tags.value = response.data;
  } catch (error) {
    console.error('Error fetching tags:', error);
  }
};

const saveCategory = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/categories/${route.params.id}`, category.value);
    } else {
      await axios.post('/api/categories', category.value);
    }
    router.push({ name: 'categories.index' });
  } catch (error) {
    console.error('Error saving category:', error);
  } finally {
    saving.value = false;
  }
};

const editTag = (tag) => {
  editingTag.value = tag;
  tagForm.value = { name: tag.name };
};

const closeTagForm = () => {
  showAddTag.value = false;
  editingTag.value = null;
  tagForm.value = { name: '' };
};

const saveTag = async () => {
  try {
    if (editingTag.value) {
      await axios.put(`/api/categories/${route.params.id}/tags/${editingTag.value.id}`, tagForm.value);
    } else {
      await axios.post(`/api/categories/${route.params.id}/tags`, tagForm.value);
    }
    await fetchTags();
    closeTagForm();
  } catch (error) {
    console.error('Error saving tag:', error);
  }
};

const confirmDeleteTag = (tag) => {
  deleteTagConfirmation.value = tag;
};

const deleteTag = async () => {
  if (!deleteTagConfirmation.value) return;

  try {
    await axios.delete(`/api/categories/${route.params.id}/tags/${deleteTagConfirmation.value.id}`);
    tags.value = tags.value.filter(t => t.id !== deleteTagConfirmation.value.id);
  } catch (error) {
    console.error('Error deleting tag:', error);
  } finally {
    deleteTagConfirmation.value = null;
  }
};

onMounted(() => {
  fetchCategory();
});
</script>
