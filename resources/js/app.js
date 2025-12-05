import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import App from './App.vue';

const appElement = document.getElementById('app');
const wcAppElement = document.getElementById('wc-app');

if (appElement) {
    createApp(App).use(router).mount('#app');
}