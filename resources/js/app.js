import './bootstrap';
import { createApp } from 'vue';
import ProductsTable from './components/ProductsTable.vue';
import WcProductsTable from './components/WcProductsTable.vue';

const appElement = document.getElementById('app');
const wcAppElement = document.getElementById('wc-app');

if (appElement) {
    createApp(ProductsTable).mount('#app');
}

if (wcAppElement) {
    createApp(WcProductsTable).mount('#wc-app');
}
