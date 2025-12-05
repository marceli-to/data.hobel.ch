import { createRouter, createWebHistory } from 'vue-router';
import ProductsTable from './components/products/Table.vue';
import ProductsForm from './components/products/Form.vue';

const routes = [
  {
    path: '/',
    redirect: '/products'
  },
  {
    path: '/products',
    name: 'products.index',
    component: ProductsTable
  },
  {
    path: '/products/:id/edit',
    name: 'products.edit',
    component: ProductsForm
  },
  {
    path: '/products/create',
    name: 'products.create',
    component: ProductsForm
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
