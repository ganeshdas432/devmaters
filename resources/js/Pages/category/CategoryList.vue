<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold mr-4">Category List</h4>
                    <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-4" :href="route('category.add')">
                        Add Category
                    </a>
                </div>
                <div class="card">
                    <DataTable :value="categories" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem">
                        <Column field="id" header="ID" style="width: 25%"></Column>
                        <Column header="Image" >
                            <template #body="slotProps">
                                <Image :src="`/storage/${slotProps.data.image}`" alt="Image" width="100" />
                            </template>
                        </Column>
                        <Column field="title" header="Title" style="width: 25%"></Column>
                        <Column field="shop_type" header="Shop Type" style="width: 25%"></Column>
                        
                        <Column header="Actions" style="width: 4rem">
                            <template #body="slotProps">
                                <Button type="button" icon="pi pi-pencil" text size="small" @click="handleEdit(slotProps.data)" />

                                <Button type="button" icon="pi pi-trash" text size="small" @click="handleDelete(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import axios from 'axios';

import Image from 'primevue/image';


const categories = ref([]); // Reactive array to hold the categories

// Fetch the list of categories on mount
onMounted(async () => {
    try {
        const response = await axios.get('/api/catlist');
        categories.value = response.data.categories; // Set the fetched categories
    } catch (error) {
        console.error('Error fetching categories data:', error);
    }
});

// Action handler for Edit click
function handleEdit(rowData) {
    Inertia.visit(`/category/edit/${rowData.id}`);
  }
// Action handler for Delete click
function handleDelete(rowData) {
    if (confirm(`Are you sure you want to delete ${rowData.title}?`)) {
        axios
            .delete(`/api/category_delete/${rowData.id}`) // Send the delete request
            .then((response) => {
                console.log(response.data.message);
                // Remove the deleted category from the categories list
                categories.value = categories.value.filter((category) => category.id !== rowData.id);
            })
            .catch((error) => {
                console.error('There was an error deleting the category:', error);
            });
    }
}
</script>
