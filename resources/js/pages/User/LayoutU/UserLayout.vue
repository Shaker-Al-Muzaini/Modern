<script setup lang="ts">
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import Header from '@/pages/User/LayoutU/Header.vue';
import Footer from '@/pages/User/LayoutU/Fotter.vue';
import Hero from '@/pages/User/LayoutU/Hero.vue';
onMounted(()=>{
    initFlowbite();
})

</script>

<template>


    <Header></Header>
    <!--man contant-->


    <slot/>
    <!--end-->
    <!--    footer-->

<Footer></Footer>

</template>
<style scoped>

</style>
