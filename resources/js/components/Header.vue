<template>
    <div class="header flex items-center p-4 bg-gray-800 text-white">
        <router-link to="/" class="text-xl font-bold hover:underline focus:outline-none">Logo</router-link>
    </div>
</template>

<script>
export default {
    name: 'Header',
};
</script>

<style scoped>
.header {
    display: flex;
    align-items: center;
    padding: 1rem;
    color: white;
}
</style>