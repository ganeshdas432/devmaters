<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">orders</h2>
        </template>

        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold mr-4">Order List</h4>

                </div>
                <div class="card">
                    <DataTable :value="orders" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem">
                        <Column field="id" header="ID"></Column>
                        <Column field="customer.name" header="Customer Name"></Column>

                        <Column field="subtotal" header="Subtotal" style="width: 25%"></Column>
                        <Column field="order_status.title" header="Order Status" style="width: 25%"></Column>

                        <Column header="Actions" bodyClass="text-center">
                            <template #body="slotProps">
                                <a :href="route('orderdetailsview', { id: slotProps.data.id })"
                                    class="p-button p-component">
                                    <i class="pi pi-eye"></i>
                                </a>


                                <Button type="button" icon="pi pi-pencil" text size="small"
                                    @click="handleEdit(slotProps.data)" />

                                <Button type="button" icon="pi pi-trash" text size="small"
                                    @click="handleDelete(slotProps.data)" />
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

const orders = ref([]); // Reactive array to hold the categories

// Fetch the list of categories on mount
onMounted(async () => {
    try {
        const response = await axios.get('/api/orderlist');
        orders.value = response.data.orders; // Set the fetched orders
    } catch (error) {
        console.error('Error fetching orders data:', error);
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
                // Remove the deleted category from the orders list
                orders.value = orders.value.filter((category) => category.id !== rowData.id);
            })
            .catch((error) => {
                console.error('There was an error deleting the category:', error);
            });
    }
}
</script>
