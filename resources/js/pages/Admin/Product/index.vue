<template>
    <AdminLayout>
        <main class="dark p-1 md:ml-57 h-auto pt-20">

                <ProductList :Products ="Products"></ProductList>
        </main>

    </AdminLayout>
</template>
<script setup>
defineProps({
    Products:Array
})
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from '../components/AdminLayout.vue';
import ProductList from '@/pages/Admin/Product/ProductList.vue';

onMounted(() => {
    initFlowbite();
})
</script>
