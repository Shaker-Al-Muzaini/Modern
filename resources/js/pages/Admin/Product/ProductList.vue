<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue';
import Swal from 'sweetalert2';



const { Products } = defineProps({
    Products: Object
})

const brands = usePage().props.brands;
const categories = usePage().props.categories;

const isAddProduct = ref(false);
const editMode = ref(false);

//upload mulitpel images

const dialogVisible = ref(false);

const productImages = ref([]);
const dialogImageUrl = ref('');

const handleFileChange = (file) => {
    productImages.value.push(file);
};
const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url;
    dialogVisible.value = true;
};
const handleRemove = (file) => {
    console.log(file);
};

//product from data
const id = ref('');
const title = ref('');
const price = ref('');
const quantity = ref('');
const description = ref('');
const product_images = ref([]);
const published = ref('');
const category_id = ref('');
const brand_id = ref('');
const inStock = ref('');
//end

//open add modal
const openAddModal = () => {
    isAddProduct.value = true;
    dialogVisible.value = true;
    editMode.value = false;
};

// add product method
const AddProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('description', description.value);
    formData.append('brand_id', brand_id.value);
    formData.append('category_id', category_id.value);
    // Append product images to the FormData
    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    try {
        await router.post('Product', formData, {
            onSuccess: (page) => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                });
                dialogVisible.value = false;
                resetFormData();
            },
        });
    } catch (err) {
        console.log(err);
    }
};

const openEditModal = (Product, index) => {
    id.value = Product.id;
    title.value = Product.title;
    price.value = Product.price;
    quantity.value = Product.quantity;
    description.value = Product.description;
    brand_id.value = Product.brand_id;
    category_id.value = Product.category_id;
    product_images.value = Product.product_images;
    editMode.value = true;
    isAddProduct.value = false;
    dialogVisible.value = true;
};

//rest data after added
const resetFormData = () => {
    id.value = '';
    title.value = '';
    price.value = '';
    quantity.value = '';
    description.value = '';
};
const deleteImage = async (pimage, index) => {
    try {
        await router.delete('/admin/products/image/' + pimage.id, {
            onSuccess: (page) => {
                product_images.value.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        })
    } catch (err) {
        console.log(err);
    }
}

//update product method
const updateProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('description', description.value);
    formData.append('category_id', category_id.value);
    formData.append('brand_id', brand_id.value);
    formData.append("_method", 'PUT');
    // Append product images to the FormData
    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    try {
        await router.post('products/update/' + id.value, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                resetFormData();
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        })
    } catch (err) {
        console.log(err)
    }
}
//delete product method
const deleteProduct = (product, index) => {
    Swal.fire({
        title: 'Are you Sure',
        text: "This actions cannot undo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'no',
        confirmButtonText: 'yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete('products/destory/' + product.id, {
                    onSuccess: (page) => {
                        this.delete(product, index);
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            title: page.props.flash.success
                        });
                    }
                })
            } catch (err) {
                console.log(err)
            }
        }
    })

}

</script>
<template>
    <section class="p-3 sm:p-5">
        <!-- dialog for adding product or editing product -->
        <el-dialog
            v-model="dialogVisible"
            :title="editMode ? 'Edit product' : 'Add Product'"
            width="70%"
            :before-close="handleClose"
            class="dark-dialog"
        >
            <!-- form start -->

            <form @submit.prevent="editMode ? updateProduct() : AddProduct()">
                <div class="group relative z-0 mb-6 w-full">
                    <input
                        v-model="title"
                        type="text"
                        name="floating_title"
                        id="floating_title"
                        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:ring-0 focus:outline-none dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                        placeholder=" "
                        required
                    />
                    <label
                        for="floating_title"
                        class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:left-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 dark:text-gray-400 peer-focus:dark:text-blue-500"
                        >Title</label
                    >
                </div>
                <div class="group relative z-0 mb-6 w-full">
                    <input
                        type="text"
                        name="floating_price"
                        id="floating_price"
                        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:ring-0 focus:outline-none dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                        placeholder=" "
                        required
                        v-model="price"
                    />
                    <label
                        for="floating_price"
                        class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:left-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 dark:text-gray-400 peer-focus:dark:text-blue-500"
                        >Price</label
                    >
                </div>
                <div class="group relative z-0 mb-6 w-full">
                    <input
                        type="number"
                        name="qty"
                        id="floating_qty"
                        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:ring-0 focus:outline-none dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                        placeholder=" "
                        required
                        v-model="quantity"
                    />
                    <label
                        for="floating_qty"
                        class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:left-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 dark:text-gray-400 peer-focus:dark:text-blue-500"
                        >Quantity</label
                    >
                </div>

                <div>
                    <label for="countries" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select Category</label>
                    <select
                        id="countries"
                        v-model="category_id"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    >
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                    </select>
                </div>

                <div>
                    <label for="countries" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select Brand</label>
                    <select
                        id="countries"
                        v-model="brand_id"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    >
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                    </select>
                </div>

                <div class="grid md:gap-6">
                    <div class="group relative z-0 mb-6 w-full">
                        <label for="message" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea
                            id="message"
                            rows="4"
                            v-model="description"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Leave a comment..."
                        ></textarea>
                    </div>
                </div>
                <!--                 multiple images upload -->
                <div class="grid md:gap-6">
                    <div class="group relative z-0 mb-6 w-full">
                        <el-upload
                            v-model:file-list="productImages"
                            list-type="picture-card"
                            multiple
                            :on-preview="handlePictureCardPreview"
                            :on-remove="handleRemove"
                            :on-change="handleFileChange"
                            :auto-upload="false"
                        >
                            <el-icon><Plus /></el-icon>
                        </el-upload>
                    </div>
                </div>
                <!-- end -->

                <!-- list of images for selected product -->
                <div class="mb-8 flex flex-nowrap">
                    <div v-for="(pimage, index) in product_images" :key="pimage.id" class="relative h-32 w-32">
                        <img class="h-20 w-24 rounded" :src="`/${pimage.image}`" alt="" />
                        <span
                            class="absolute top-0 right-8 h-3.5 w-3.5 -translate-y-1/2 transform rounded-full border-2 border-white bg-red-400 dark:border-gray-800"
                        >
                            <span
                                @click="deleteImage(pimage, index)"
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transform text-xs font-bold text-white cursor-pointer"
                                >x</span
                            >
                        </span>
                    </div>
                </div>

                <!-- end -->

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
            </form>

            <!-- end -->
        </el-dialog>

        <!-- end -->
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg dark:bg-gray-800">
                <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg
                                        aria-hidden="true"
                                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor"
                                        viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="simple-search"
                                    class="focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-500 dark:focus:border-primary-500 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-10 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    placeholder="Search"
                                    required=""
                                />
                            </div>
                        </form>
                    </div>
                    <div
                        class="flex w-full flex-shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-y-0 md:space-x-3"
                    >
                        <button
                            type="button"
                            @click="openAddModal"
                            class="flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            <svg
                                class="mr-2 h-3.5 w-3.5"
                                fill="currentColor"
                                viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >
                                <path
                                    clip-rule="evenodd"
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                />
                            </svg>
                            Add product
                        </button>
                        <div class="flex w-full items-center space-x-3 md:w-auto">
                            <button
                                id="actionsDropdownButton"
                                data-dropdown-toggle="actionsDropdown"
                                class="hover:text-primary-700 flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-200 focus:outline-none md:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                type="button"
                            >
                                <svg
                                    class="mr-1.5 -ml-1 h-5 w-5"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    />
                                </svg>
                                Actions
                            </button>
                            <div
                                id="actionsDropdown"
                                class="z-10 hidden w-44 divide-y divide-gray-100 rounded bg-white shadow dark:divide-gray-600 dark:bg-gray-700"
                            >
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="actionsDropdownButton">
                                    <li>
                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                            >Mass Edit</a
                                        >
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a
                                        href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >Delete all</a
                                    >
                                </div>
                            </div>
                            <button
                                id="filterDropdownButton"
                                data-dropdown-toggle="filterDropdown"
                                class="hover:text-primary-700 flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:z-10 focus:ring-4 focus:ring-gray-200 focus:outline-none md:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                type="button"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                    class="mr-2 h-4 w-4 text-gray-400"
                                    viewbox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Filter
                                <svg
                                    class="-mr-1 ml-1.5 h-5 w-5"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        clip-rule="evenodd"
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    />
                                </svg>
                            </button>
                            <div id="filterDropdown" class="z-10 hidden w-48 rounded-lg bg-white p-3 shadow dark:bg-gray-700">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Choose brand</h6>
                                <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                    <li class="flex items-center">
                                        <input
                                            id="apple"
                                            type="checkbox"
                                            value=""
                                            class="text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 h-4 w-4 rounded border-gray-300 bg-gray-100 focus:ring-2 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700"
                                        />
                                        <label for="apple" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Apple (56)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="fitbit"
                                            type="checkbox"
                                            value=""
                                            class="text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 h-4 w-4 rounded border-gray-300 bg-gray-100 focus:ring-2 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700"
                                        />
                                        <label for="fitbit" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Microsoft (16)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="razor"
                                            type="checkbox"
                                            value=""
                                            class="text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 h-4 w-4 rounded border-gray-300 bg-gray-100 focus:ring-2 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700"
                                        />
                                        <label for="razor" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Razor (49)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="nikon"
                                            type="checkbox"
                                            value=""
                                            class="text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 h-4 w-4 rounded border-gray-300 bg-gray-100 focus:ring-2 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700"
                                        />
                                        <label for="nikon" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nikon (12)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input
                                            id="benq"
                                            type="checkbox"
                                            value=""
                                            class="text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 h-4 w-4 rounded border-gray-300 bg-gray-100 focus:ring-2 dark:border-gray-500 dark:bg-gray-600 dark:ring-offset-gray-700"
                                        />
                                        <label for="benq" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">BenQ (74)</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Product name</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">Quantity</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Publish</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in Products.data" :key="product.id" class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ product.title }}
                                </th>
                                <td class="px-4 py-3">{{ product.category.name }}</td>
                                <td class="px-4 py-3">{{ product.brand.name }}</td>
                                <td class="px-4 py-3">{{ product.quantity }}</td>
                                <td class="px-4 py-3">${{ product.price }}</td>

                                <td class="px-4 py-3">
                                    <span
                                        v-if="product.inStock == 1"
                                        class="mr-2 rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300"
                                        >inStock</span
                                    >
                                    <span
                                        v-else
                                        class="mr-2 rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300"
                                        >Out of Stock</span
                                    >
                                </td>
                                <td class="px-4 py-3">
                                    <button
                                        v-if="product.published == 1"
                                        type="button"
                                        class="rounded-lg bg-green-700 px-3 py-2 text-center text-xs font-medium text-white hover:bg-green-800 focus:ring-4 focus:ring-green-300 focus:outline-none dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                    >
                                        Published
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="rounded-lg bg-red-700 px-3 py-2 text-center text-xs font-medium text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300 focus:outline-none dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                    >
                                        UnPublished
                                    </button>
                                </td>

                                <td class="flex items-center justify-end px-4 py-3">
                                    <button
                                        :id="`${product.id}-button`"
                                        :data-dropdown-toggle="`${product.id}`"
                                        class="inline-flex items-center rounded-lg p-0.5 text-center text-sm font-medium text-gray-500 hover:text-gray-800 focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button"
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                                            />
                                        </svg>
                                    </button>
                                    <div
                                        :id="`${product.id}`"
                                        class="z-10 hidden w-44 divide-y divide-gray-100 rounded bg-white shadow dark:divide-gray-600 dark:bg-gray-700"
                                    >
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" :aria-labelledby="`${product.id}-button`">
                                            <li>
                                                <a
                                                    href="#"
                                                    @click="openEditModal(product)"
                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    >Edit</a
                                                >
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a
                                                href="#"
                                                @click="deleteProduct(product, index)"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white"
                                                >Delete</a
                                            >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <nav
                    class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation"
                >
                    <!-- Showing X-Y of Z -->
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
      Showing
      <span class="font-semibold text-gray-900 dark:text-white">{{ Products.from }}-{{ Products.to }}</span>
      of
      <span class="font-semibold text-gray-900 dark:text-white">{{ Products.total }}</span>
    </span>

                    <!-- Pagination buttons -->
                    <ul class="inline-flex items-stretch -space-x-px">
                        <!-- Previous -->
                        <li v-if="Products.prev_page_url">
                            <a
                                href="#"
                                @click.prevent="$inertia.get(Products.prev_page_url)"
                                class="ml-0 flex h-full items-center justify-center rounded-l-lg border border-gray-300 bg-white px-3 py-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <li
                            v-for="page in Products.last_page"
                            :key="page"
                        >
                            <a
                                href="#"
                                @click.prevent="$inertia.get(`?page=${page}`)"
                                :class="[
            'flex items-center justify-center border px-3 py-2 text-sm leading-tight',
            page === Products.current_page
              ? 'z-10 text-white bg-blue-600 border-blue-600'
              : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
          ]"
                            >
                                {{ page }}
                            </a>
                        </li>

                        <!-- Next -->
                        <li v-if="Products.next_page_url">
                            <a
                                href="#"
                                @click.prevent="$inertia.get(Products.next_page_url)"
                                class="flex h-full items-center justify-center rounded-r-lg border border-gray-300 bg-white px-3 py-1.5 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</template>

<style scoped>
.dark-dialog {
    max-height: 80vh;
    overflow-y: auto;
}
input {
    color: #000000; /* أسود غامق */
    font-weight: 500; /* مثلا متوسط سمك الخط */
}
</style>
