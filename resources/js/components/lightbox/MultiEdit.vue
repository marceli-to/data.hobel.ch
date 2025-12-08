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
          <h2 class="text-lg font-semibold text-black">Edit Records</h2>
          <p class="text-sm text-gray-500">{{ selectedCount }} item{{ selectedCount > 1 ? 's' : '' }} selected</p>
        </div>
        <button @click="$emit('close')" class="text-gray-400 w-8 h-8 absolute top-2 right-2 flex items-center justify-center hover:text-black transition-colors cursor-pointer rounded-full">
          <PhX class="w-5 h-5" />
        </button>
      </div>

      <div class="overflow-y-auto flex-1 p-4">
        <!-- Action Selector -->
        <div v-if="!selectedAction">
          <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Action</label>
          <select
            v-model="selectedAction"
            class="w-full border border-gray-200 px-1 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
          >
            <option value="">Select an action...</option>
            <option value="remarks">Add Remarks</option>
            <option value="category_tags">Set Category/Tags</option>
            <option value="set_type">Set type</option>
            <option value="mark_done">Mark as done</option>
            <option value="delete">Delete</option>
          </select>
        </div>

        <!-- Add Remarks Form -->
        <div v-if="selectedAction === 'remarks'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-medium text-black">Add Remarks</h3>
            <button @click="selectedAction = ''" class="text-xs text-gray-500 hover:text-black transition-colors cursor-pointer rounded-sm">Back</button>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Remarks</label>
            <textarea
              v-model="form.remarks"
              rows="4"
              class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black transition-colors resize-none rounded-sm"
              placeholder="Enter remarks..."
            ></textarea>
          </div>
          <div class="flex gap-3 justify-end pt-2">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
            >
              Cancel
            </button>
            <button
              @click="submit"
              class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
            >
              Save
            </button>
          </div>
        </div>

        <!-- Set Category/Tags Form -->
        <div v-if="selectedAction === 'category_tags'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-medium text-black">Set Category/Tags</h3>
            <button @click="selectedAction = ''" class="text-xs text-gray-500 hover:text-black transition-colors cursor-pointer rounded-sm">Back</button>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category</label>
            <select
              v-model="form.selectedCategory"
              @change="loadTagsForCategory"
              class="w-full border border-gray-200 px-1 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
            >
              <option value="">Select a category...</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <div v-if="form.selectedCategory && tags.length > 0">
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tags</label>
            <div class="space-y-1 max-h-48 overflow-y-auto grid grid-cols-2">
              <label
                v-for="tag in tags"
                :key="tag.id"
                class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 rounded-sm px-2 py-1.5 transition-colors"
              >
                <input
                  type="checkbox"
                  :value="tag.id"
                  v-model="form.selectedTags"
                  class="w-[0.875rem] h-[0.875rem] cursor-pointer accent-black"
                />
                <span class="text-sm text-gray-700">{{ tag.name }}</span>
              </label>
            </div>
          </div>

          <div class="flex gap-3 justify-end pt-2">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
            >
              Cancel
            </button>
            <button
              @click="submit"
              :disabled="!form.selectedCategory"
              class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              Save
            </button>
          </div>
        </div>

        <!-- Set Type Form -->
        <div v-if="selectedAction === 'set_type'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-medium text-black">Set Type</h3>
            <button @click="selectedAction = ''" class="text-xs text-gray-500 hover:text-black transition-colors cursor-pointer rounded-sm">Back</button>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Type</label>
            <select
              v-model="form.selectedType"
              class="w-full border border-gray-200 px-1 py-2 text-sm rounded-sm focus:outline-none focus:border-black transition-colors"
            >
              <option value="">Select a type...</option>
              <option value="configurable">Configurable</option>
              <option value="simple">Simple</option>
              <option value="variations">Variations</option>
            </select>
          </div>

          <div class="flex gap-3 justify-end pt-2">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
            >
              Cancel
            </button>
            <button
              @click="submit"
              :disabled="!form.selectedType"
              class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              Save
            </button>
          </div>
        </div>

        <!-- Mark as Done Confirmation -->
        <div v-if="selectedAction === 'mark_done'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-medium text-black">Mark as Done</h3>
            <button @click="selectedAction = ''" class="text-xs text-gray-500 hover:text-black transition-colors cursor-pointer rounded-sm">Back</button>
          </div>
          <div class="bg-green-100 border border-green-300 rounded-sm p-3">
            <p class="text-sm text-black">
              Mark {{ selectedCount }} record{{ selectedCount > 1 ? 's' : '' }} as done?
            </p>
          </div>
          <div class="flex gap-3 justify-end pt-2">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
            >
              Cancel
            </button>
            <button
              @click="submit"
              class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
            >
              Confirm
            </button>
          </div>
        </div>

        <!-- Delete Confirmation -->
        <div v-if="selectedAction === 'delete'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h3 class="text-sm font-medium text-black">Delete Records</h3>
            <button @click="selectedAction = ''" class="text-xs text-gray-500 hover:text-black transition-colors cursor-pointer rounded-sm">Back</button>
          </div>
          <div class="bg-red-100 border border-red-300 rounded-sm p-3">
            <p class="text-sm text-black">
              Are you sure you want to delete {{ selectedCount }} record{{ selectedCount > 1 ? 's' : '' }}?
              This action cannot be undone.
            </p>
          </div>
          <div class="flex gap-3 justify-end pt-2">
            <button
              @click="$emit('close')"
              class="px-4 py-2 text-sm text-gray-600 hover:text-black transition-colors cursor-pointer rounded-sm"
            >
              Cancel
            </button>
            <button
              @click="submit"
              class="px-4 py-2 text-sm bg-black text-white hover:bg-gray-800 transition-colors cursor-pointer rounded-sm"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { PhX } from '@phosphor-icons/vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  selectedCount: {
    type: Number,
    default: 0
  },
  productIds: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'submit']);

const selectedAction = ref('');
const categories = ref([]);
const tags = ref([]);
const form = ref({
  remarks: '',
  selectedCategory: '',
  selectedTags: [],
  selectedType: '',
});

const resetForm = () => {
  selectedAction.value = '';
  form.value = {
    remarks: '',
    selectedCategory: '',
    selectedTags: [],
    selectedType: '',
  };
  tags.value = [];
};

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const loadTagsForCategory = async () => {
  if (!form.value.selectedCategory) {
    tags.value = [];
    return;
  }

  try {
    const response = await axios.get(`/api/categories/${form.value.selectedCategory}/tags`);
    tags.value = response.data;
    form.value.selectedTags = [];
  } catch (error) {
    console.error('Error fetching tags:', error);
  }
};

const submit = () => {
  const payload = {
    product_ids: props.productIds,
    action: selectedAction.value,
  };

  if (selectedAction.value === 'remarks') {
    payload.remarks = form.value.remarks;
  } else if (selectedAction.value === 'category_tags') {
    payload.category_id = form.value.selectedCategory;
    payload.tag_ids = form.value.selectedTags;
  } else if (selectedAction.value === 'set_type') {
    payload.type = form.value.selectedType;
  }

  emit('submit', payload);
};

watch(() => props.show, (newShow) => {
  if (newShow) {
    fetchCategories();
  } else {
    resetForm();
  }
});
</script>
