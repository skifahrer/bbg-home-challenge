<template>
    <div class="pagination mt-4 flex justify-center items-center" v-if="pagination">
        <button 
            class="px-4 py-2 bg-gray-200 rounded mr-2" 
            :disabled="!pagination.prev_page_url" 
            @click="$emit('page-changed', pagination.current_page - 1)">
            Previous
        </button>
        <span class="px-4 py-2">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
        <button 
            class="px-4 py-2 bg-gray-200 rounded ml-2" 
            :disabled="!pagination.next_page_url" 
            @click="$emit('page-changed', pagination.current_page + 1)">
            Next
        </button>
    </div>
</template>

<script>
export default {
    name: 'Paginator',
    props: {
        pagination: {
            type: Object,
            required: true
        }
    }
};
</script>

<style scoped>
.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>