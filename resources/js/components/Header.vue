<template>
    <div class="header flex items-center p-4 bg-gray-800 text-white">
        <h1 class="text-xl font-bold">Logo</h1>
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
    background-color: #2d3748; /* Tailwind gray-800 */
    color: white;
}
</style>