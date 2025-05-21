<script setup>
import { computed, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import UserLayout from '@/pages/User/LayoutU/UserLayout.vue'

// Props
defineProps({
    userAddress: Object
})

// البيانات المأخوذة من Inertia
const page = usePage()
const carts = computed(() => page.props.cart.data.items)
const products = computed(() => page.props.cart.data.products)
const total = computed(() => page.props.cart.data.total)

// إيجاد موقع العنصر في السلة
const itemId = (id) => carts.value.findIndex(item => item.product_id === id)

// نموذج العنوان الجديد
const form = reactive({
    address1: null,
    state: null,
    city: null,
    zipcode: null,
    country_code: null,
    type: null
})

// تحقق من تعبئة النموذج
const formFilled = computed(() => {
    return Object.values(form).every(value => value !== null)
})

// تحديث كمية منتج
const update = (product, quantity) => {
    router.patch(route('cart.update', product), { quantity })
}

// إزالة منتج من السلة
const remove = (product) => {
    router.delete(route('cart.delete', product))
}

// تأكيد الطلب
const submit = () => {
    router.visit(route('checkout.store'), {
        method: 'post',
        data: {
            carts: carts.value,
            products: products.value,
            total: total.value,
            address: form
        }
    })
}
</script>

<template>
    <UserLayout>
        <section class="bg-gray-100 py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- السلة -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Cart</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th></th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in products" :key="product.id" class="border-b">
                                    <td class="py-3">
                                        <img
                                            :src="product.product_images.length ? `/${product.product_images[0].image}` : 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png'"
                                            class="h-16 w-16 object-cover rounded"
                                        />
                                    </td>
                                    <td class="py-3">{{ product.title }}</td>
                                    <td class="py-3">
                                        <div class="flex items-center space-x-2">
                                            <button @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)" class="bg-gray-200 text-black  rounded-full w-6 h-6 text-center text-sm font-bold">-</button>
                                            <input
                                                type="number"
                                                v-model="carts[itemId(product.id)].quantity"
                                                class="w-12 text-black  text-center border rounded px-1 py-0.5"
                                                min="1"
                                            />
                                            <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)" class="bg-gray-200 text-black rounded-full w-6 h-6 text-center text-sm font-bold">+</button>
                                        </div>
                                    </td>
                                    <td class="py-3">${{ product.price }}</td>
                                    <td class="py-3 text-red-600">
                                        <button @click="remove(product)" class="hover:underline">Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ملخص الدفع -->
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Summary</h2>
                        <p class="text-gray-600 mb-4">Total: <span class="font-semibold">${{ total }}</span></p>

                        <div v-if="userAddress" class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-1">Shipping To:</h3>
                            <p class="text-gray-600">
                                {{ userAddress.address1 }}, {{ userAddress.city }}, {{ userAddress.zipcode }}
                            </p>
                            <p class="text-sm text-gray-500 mt-2">You can update the address below.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-4">
                            <div v-for="(label, key) in {
                address1: 'Address 1',
                city: 'City',
                state: 'State',
                zipcode: 'Zip Code',
                country_code: 'Country Code',
                type: 'Address Type'
              }" :key="key">
                                <label :for="key" class="block text-sm text-gray-700">{{ label }}</label>
                                <input
                                    type="text"
                                    v-model="form[key]"
                                    :id="key"
                                    :name="key"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                />
                            </div>

                            <button
                                type="submit"
                                :disabled="!(formFilled || userAddress)"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 transition disabled:bg-gray-400"
                            >
                                {{ formFilled || userAddress ? 'Proceed to Checkout' : 'Fill Address to Continue' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>

