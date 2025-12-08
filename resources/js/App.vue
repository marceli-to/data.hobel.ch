<template>
  <div class="flex min-h-screen">
    <Sidebar />
    <main class="flex-1 bg-white">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import Sidebar from './components/Sidebar.vue';
</script>
