<template>
    <div class="flex flex-col min-h-screen">
        <Header class="w-full" />
        <div class="flex flex-1 justify-center items-center p-4">
            <router-view></router-view>
            <!-- Tu neskôr pridáme Filter, Pagination -->
        </div>
        <Footer class="w-full" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Header from './Header.vue';
import Footer from './Footer.vue';

const products = ref([]);
const fetchProducts = async () => {
    // Fetch products from an API or data source
};

fetchProducts();
</script>

<style scoped>
</style>