<script setup>
import { Link, router } from '@inertiajs/vue3';
import Products from '../User/Components/Products.vue'
import Hero from '@/pages/User/LayoutU/Hero.vue';
import UserLayout from '@/pages/User/LayoutU/UserLayout.vue';
//products list
defineProps({
    products: Array
})


</script>
<template>
    <UserLayout>
        <!-- here section  -->
        <Hero></Hero>
        <!-- end -->
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">List of products</h2>

                <!-- product list component -->
                <Products :products="products"></Products>
                <!-- end -->
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.index')"
                          class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                        View All Products</Link>
                </div>


            </div>
        </div>
    </UserLayout>
</template>
