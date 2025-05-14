<template>
    <div class="product-list">
        <h1 class="text-2xl font-bold mb-4">Product List</h1>
        <div v-if="products.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="product in products" :key="product.id" class="product-item border p-4 rounded shadow">
                <router-link :to="`/product/${product.id}`">
                    <img :src="product.image_url" :alt="product.name" class="w-full h-48 object-cover mb-2" />
                    <h2 class="text-lg font-semibold">{{ product.name }}</h2>
                    <p class="text-gray-600">{{ product.description }}</p>
                    <p class="text-green-600 font-bold">{{ product.price }}</p>
                </router-link>
            </div>
        </div>
        <div v-else>
            <p>No products available.</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProductList',
    data() {
        return {
            products: [],
        };
    },
    created() {
        axios.get('http://localhost:8000/api/products')
            .then(response => {
                console.log('Products fetched:', response.data);
                this.products = response.data.data;
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    },
};
</script>

<style scoped>
.product-list {
    padding: 1rem;
}

.product-item img {
    border-radius: 0.5rem;
}
</style>