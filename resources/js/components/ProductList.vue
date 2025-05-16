<template>
    <div class="product-list">
        <h1 class="text-2xl font-bold mb-4">Product List</h1>

        <!-- Category Selector -->
        <CategorySelector :categories="categories" :selectedCategory="selectedCategory"
            @category-changed="handleCategoryChange" />

        <!-- Product List -->
        <div v-if="products.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
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

        <!-- Pagination -->
        <Paginator v-if="pagination" :pagination="pagination" @page-changed="fetchProducts" />
    </div>
</template>

<script>
import axios from 'axios';
import CategorySelector from './CategorySelector.vue';
import Paginator from './Paginator.vue';

export default {
    name: 'ProductList',
    components: {
        CategorySelector,
        Paginator
    },
    data() {
        return {
            products: [],
            pagination: null,
            categories: [],
            selectedCategory: '',
        };
    },
    created() {
        this.fetchCategories();
        this.fetchProducts(1);
    },
    methods: {
        fetchProducts(page) {
            const categoryFilter = this.selectedCategory ? `&category=${this.selectedCategory}` : '';
            axios.get(`http://localhost:8000/api/products?page=${page}${categoryFilter}`)
                .then(response => {
                    console.log('Products fetched:', response.data);
                    this.products = response.data.data;
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        prev_page_url: response.data.prev_page_url,
                        next_page_url: response.data.next_page_url,
                    };
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
        },
        fetchCategories() {
            axios.get('http://localhost:8000/api/categories')
                .then(response => {
                    console.log('Categories fetched:', response.data);
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        handleCategoryChange(newCategory) {
            this.selectedCategory = newCategory;
            this.fetchProducts(1);
        }
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