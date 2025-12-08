import { createRouter, createWebHistory } from 'vue-router';
import ProductsTable from './components/products/Table.vue';
import ProductsForm from './components/products/Form.vue';
import WoodTypesTable from './components/wood-types/Table.vue';
import WoodTypesForm from './components/wood-types/Form.vue';
import CategoriesTable from './components/categories/Table.vue';
import CategoriesForm from './components/categories/Form.vue';
import ShippingMethodsTable from './components/shipping-methods/Table.vue';
import ShippingMethodsForm from './components/shipping-methods/Form.vue';

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
  },
  {
    path: '/categories',
    name: 'categories.index',
    component: CategoriesTable
  },
  {
    path: '/categories/:id/edit',
    name: 'categories.edit',
    component: CategoriesForm
  },
  {
    path: '/categories/create',
    name: 'categories.create',
    component: CategoriesForm
  },
  {
    path: '/shipping-methods',
    name: 'shipping-methods.index',
    component: ShippingMethodsTable
  },
  {
    path: '/shipping-methods/:id/edit',
    name: 'shipping-methods.edit',
    component: ShippingMethodsForm
  },
  {
    path: '/shipping-methods/create',
    name: 'shipping-methods.create',
    component: ShippingMethodsForm
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
