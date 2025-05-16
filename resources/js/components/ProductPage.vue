<template>
    <div v-if="notFound" class="product-page p-4">
        <h1 class="text-2xl font-bold mb-4 text-red-600">404 - Product Not Found</h1>
        <router-link to="/" class="text-blue-500 hover:underline">Back to Home</router-link>
    </div>
    <div v-else class="product-page p-4">
        <h1 class="text-2xl font-bold mb-4">{{ product?.name }}</h1>
        <router-link to="/" class="text-blue-500 hover:underline mb-4">Back to Products</router-link>
        <img :src="product?.image_url" alt="Product Image" class="w-full h-64 object-cover rounded mb-4" />
        <p class="text-gray-600 mb-2">{{ product?.description }}</p>
        <p class="text-green-600 font-bold mb-2"><strong>Price:</strong> {{ product?.price }}</p>
        <p class="text-gray-800"><strong>Category:</strong> {{ product?.category.name }}</p>
    </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

interface Product {
    id: number;
    name: string;
    price: number;
    description: string;
    image_url: string;
    category: {
        name: string;
    };
}

const route = useRoute();
const product = ref<Product | null>(null);
const notFound = ref(false);

onMounted(async () => {
    try {
        const response = await axios.get(`http://localhost:8000/api/products/${route.params.id}`);
        if (!response.data || !response.data.id) {
            notFound.value = true;
        } else {
            product.value = response.data;
        }
    } catch (error) {
        notFound.value = true;
    }
});
</script>

<style scoped>
.product-page {
    max-width: 800px;
    margin: 0 auto;
    text-align: left;
}
</style>