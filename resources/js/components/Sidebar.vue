<template>
  <aside class="w-56 bg-gray-50 border-r border-gray-200 min-h-screen flex flex-col sticky top-0 left-0">
    <!-- Logo / Brand -->
    <div class="p-4">
      <svg width="70" height="80" viewBox="0 0 70 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-auto h-8">
        <path d="M0 40.41L35 20.21L49.97 28.85L15.83 48.56V80H0V40.41Z" fill="currentColor"></path>
        <path d="M54.17 0H70V39.59L35 59.79L20.03 51.15L54.17 31.44V0Z" fill="currentColor"></path>
        <path d="M70 80H54.17V53.57L70 44.43V80Z" fill="currentColor"></path>
        <path d="M15.83 0V26.43L0 35.57V0H15.83Z" fill="currentColor"></path>
      </svg>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4">
      <ul>
        <li>
          <router-link
            :to="{ name: 'products.index' }"
            class="flex items-center gap-3 p-4 text-sm transition-colors"
            :class="isActive('products') ? 'bg-gray-100' : 'text-gray-700 hover:bg-gray-200'"
          >
            <PhPackage class="w-5 h-5" />
            Produkte
          </router-link>
        </li>
        <li>
          <router-link
            :to="{ name: 'wood-types.index' }"
            class="flex items-center gap-3 p-4 text-sm transition-colors"
            :class="isActive('wood-types') ? 'bg-gray-100' : 'text-gray-700 hover:bg-gray-200'"
          >
            <PhTree class="w-5 h-5" />
            Holzarten
          </router-link>
        </li>
        <li>
          <router-link
            :to="{ name: 'categories.index' }"
            class="flex items-center gap-3 p-4 text-sm transition-colors"
            :class="isActive('categories') ? 'bg-gray-100' : 'text-gray-700 hover:bg-gray-200'"
          >
            <PhFolders class="w-5 h-5" />
            Kategorien
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { PhPackage, PhTree, PhFolders } from '@phosphor-icons/vue';

const route = useRoute();

const isActive = (section) => {
  return route.path.startsWith(`/${section}`);
};
</script>
