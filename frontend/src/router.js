import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import Adios from './views/Adios.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/Adios', component: Adios },
  // Otras rutas
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

