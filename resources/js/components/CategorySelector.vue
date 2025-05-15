<template>
    <div class="mb-4">
        <label for="category" class="block text-sm font-medium text-gray-700">Filter by Category:</label>
        <select 
            id="category" 
            class="mt-1 block pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            :value="selectedCategory"
            @change="$emit('category-changed', $event.target.value)">
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    name: 'CategorySelector',
    props: {
        categories: {
            type: Array,
            required: true
        },
        selectedCategory: {
            type: String,
            required: true
        }
    }
};
</script>

<style scoped>
</style>