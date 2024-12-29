<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold mr-4">Product List</h4>
                    <div>
                        <a class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-4"
                            :href="route('add_product')">
                            Add Product
                        </a>
                        <a class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-4"
                            :href="route('categorylist')">
                            Categories
                        </a>
                        <a class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-4"
                            :href="route('unitlist')">
                            Units
                        </a>
                    </div>
                </div>

                <DataTable :value="products" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem">

                    <!-- Columns -->
                    <Column field="id" header="ID" sortable></Column>

                    <Column header="Image">
                        <template #body="slotProps">
                            <img class="block mx-auto rounded w-full"
                                :src="slotProps.data.image ? '/storage/' + slotProps.data.image : '/storage/products/default.png'"
                                :alt="slotProps.data.product_name" style="max-width: 150px; object-fit: cover;" />
                        </template>
                    </Column>

                    <Column field="product_name" header="Product Name" sortable></Column>
                    <Column field="category.title" header="Category"></Column>
                    <Column field="shop.shop_name" header="Shop Name"></Column>

                    <Column field="average_rating" header="Rating">
                        <template #body="slotProps">
                            <div class="bg-surface-100 p-1" style="border-radius: 30px">
                                <div class="bg-surface-0 flex items-center gap-2 justify-center py-1 px-2"
                                    style="border-radius: 30px; box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1)">
                                    <span class="text-surface-900 font-medium text-sm">
                                        {{ slotProps.data.average_rating !== null && slotProps.data.average_rating !==
                                            undefined
                                            ? slotProps.data.average_rating
                                            : 'No Ratings' }}
                                    </span>
                                    <i class="pi pi-star-fill text-yellow-500"></i>
                                </div>
                            </div>
                        </template>
                    </Column>

                    <Column header="Actions">
                        <template #body="slotProps">
                            <div class="flex gap-2">
                                <Button icon="pi pi-eye" outlined></Button>
                                <Button icon="pi pi-pencil" outlined @click="goToEditPage(slotProps.data.id)"></Button>
                                <Button icon="pi pi-lock" outlined></Button>
                            </div>
                        </template>
                    </Column>
                </DataTable>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import Button from 'primevue/button';

// Reactive State
const products = ref([]);



// Fetch Products from API
const fetchProducts = async (page = 0, sort = null) => {
    try {
        const response = await axios.get('/api/products');
        products.value = response.data;
        console.log("test");

    } catch (e) {
        error.value = true;
        console.error('Failed to fetch products:', e);
    } finally {
    }
};


// Navigation to Edit Page
const goToEditPage = (productId) => {
    Inertia.visit(`/products/${productId}/edit`);
};

// Initial Fetch
onMounted(() => {
    fetchProducts();
});
</script>
