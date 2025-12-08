import { createRouter, createWebHistory } from 'vue-router';
import ProductsTable from './components/products/Table.vue';
import ProductsForm from './components/products/Form.vue';
import WoodTypesTable from './components/wood-types/Table.vue';
import WoodTypesForm from './components/wood-types/Form.vue';

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
  },
  {
    path: '/wood-types',
    name: 'wood-types.index',
    component: WoodTypesTable
  },
  {
    path: '/wood-types/:id/edit',
    name: 'wood-types.edit',
    component: WoodTypesForm
  },
  {
    path: '/wood-types/create',
    name: 'wood-types.create',
    component: WoodTypesForm
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
