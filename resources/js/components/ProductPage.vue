<template>
    <div class="product-page p-4">
        <h1 class="text-2xl font-bold mb-4">{{ product.name }}</h1>
        <img :src="product.image_url" alt="Product Image" class="w-full h-64 object-cover rounded mb-4" />
        <p class="text-gray-600 mb-2">{{ product.description }}</p>
        <p class="text-green-600 font-bold mb-2"><strong>Price:</strong> {{ product.price }}</p>
        <p class="text-gray-800"><strong>Category:</strong> {{ product.category.name }}</p>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProductPage',
    props: {
        id: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            product: {}
        };
    },
    created() {
        axios.get(`http://localhost:8000/api/products/${this.id}`)
            .then(response => {
                this.product = response.data;
            })
            .catch(error => {
                console.error('Error fetching product:', error);
            });
    }
};
</script>

<style scoped>
.product-page {
    max-width: 800px;
    margin: 0 auto;
    text-align: left;
}
</style>