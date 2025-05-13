<template>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">

<!--        navbar start-->
    <Navbar/>
<!--        end-->
    <Sidebar/>
        <!-- Sidebar -->
<!--end-->

     <slot/>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';


onMounted(() => {
    initFlowbite();

        const observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                mutation.addedNodes.forEach(node => {
                    if (
                        node.nodeType === 1 &&
                        node.classList.contains('bg-gray-900/50') &&
                        node.hasAttribute('drawer-backdrop')
                    ) {
                        // حذف العنصر من DOM
                        node.remove();
                        console.log('تم حذف العنصر drawer-backdrop');
                    }
                });
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });


})

</script>

