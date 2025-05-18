import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import ProductList from './components/ProductList.vue';
import ProductPage from './components/ProductPage.vue';
import NotFound from './components/NotFound.vue';

const routes = [
    { path: '/', component: ProductList },
    { path: '/product/:id', component: ProductPage, props: true },
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');
