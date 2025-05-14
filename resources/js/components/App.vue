<template>
    <div class="container py-4">
        <h1>Product Catalog</h1>
        <router-view></router-view>
        <!-- Tu neskôr pridáme Filter, Pagination -->
    </div>
</template>

<script setup>
import { ref } from 'vue';

const products = ref([]);
const fetchProducts = async () => {
    // Fetch products from an API or data source
};

fetchProducts();
</script>