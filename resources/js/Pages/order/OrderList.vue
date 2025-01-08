<template>

    <Head title="Order Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Management</h2>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="card">
                    <!-- Search and Filter Section -->
                    <div
                        class="mb-4 flex flex-wrap gap-4 justify-between items-center p-4 border-b bg-gray-50 rounded-t-lg">
                        <span class="p-input-icon-left w-full md:w-96">
                            <i class="pi pi-search" />
                            <InputText v-model="searchQuery" placeholder="Search orders..." class="w-full" />
                        </span>
                        <div class="flex gap-2">
                            <Button icon="pi pi-filter" severity="secondary" label="Filters" />
                            <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
                        </div>
                    </div>

                    <!-- Enhanced DataTable -->
                    <DataTable :value="orders" :paginator="true" :rows="10" :rowsPerPageOptions="[10, 20, 50]"
                        v-model:filters="filters" filterDisplay="menu" :loading="loading" stripedRows showGridlines
                        :globalFilterFields="['id', 'customer.name', 'subtotal', 'order_status.title']"
                        tableStyle="min-width: 50rem" class="p-datatable-lg">
                        <Column field="id" header="Order ID" sortable style="width: 10%">
                            <template #body="{ data }">
                                <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                            </template>
                        </Column>

                        <Column field="customer.name" header="Customer" sortable style="width: 25%">
                            <template #body="{ data }">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        {{ data.customer.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ data.customer.name }}</div>
                                        <div class="text-sm text-gray-500">{{ data.customer.email }}</div>
                                    </div>
                                </div>
                            </template>
                        </Column>

                        <Column field="subtotal" header="Amount" sortable style="width: 15%">
                            <template #body="{ data }">
                                <div class="font-medium text-gray-900">
                                    ${{ Number(data.subtotal).toFixed(2) }}
                                </div>
                            </template>
                        </Column>

                        <Column field="order_status.title" header="Status" sortable style="width: 15%">
                            <template #body="slotProps">
                                <Tag :value="slotProps.data.order_status.title"
                                    :severity="getOrderSeverity(slotProps.data.order_status.title)"
                                    class="text-xs px-3 py-1">
                                    <i :class="getOrderStatusIcon(slotProps.data.order_status.title)" class="mr-1"></i>
                                    {{ slotProps.data.order_status.title }}
                                </Tag>
                            </template>
                        </Column>

                        <Column header="Actions" :exportable="false" style="width: 15%">
                            <template #body="slotProps">
                                <div class="flex gap-2 justify-center">
                                    <Button icon="pi pi-eye" text rounded severity="info"
                                        @click="viewDetails(slotProps.data.id)" v-tooltip.top="'View Details'" />
                                    <Button icon="pi pi-pencil" text rounded severity="success"
                                        @click="handleEdit(slotProps.data)" v-tooltip.top="'Edit Order'" />
                                    <Button icon="pi pi-trash" text rounded severity="danger"
                                        @click="handleDelete(slotProps.data)" v-tooltip.top="'Delete Order'" />
                                </div>
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
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import Tooltip from 'primevue/tooltip';
import axios from 'axios';

const orders = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filters = ref({
    global: { value: null, matchMode: 'contains' },
});

const getOrderStatusIcon = (status) => {
    switch (status.toLowerCase()) {
        case 'pending':
            return 'pi pi-clock';
        case 'processing':
            return 'pi pi-sync';
        case 'completed':
            return 'pi pi-check-circle';
        case 'cancelled':
            return 'pi pi-times-circle';
        default:
            return 'pi pi-question-circle';
    }
};

const getOrderSeverity = (status) => {
    switch (status.toLowerCase()) {
        case 'pending':
            return 'warning';
        case 'processing':
            return 'info';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
};

const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/orderlist');
        orders.value = response.data.orders;
    } catch (error) {
        console.error('Error fetching orders data:', error);
    } finally {
        loading.value = false;
    }
};

function handleEdit(rowData) {
    Inertia.visit(`/orders/edit/${rowData.id}`);
}

function handleDelete(rowData) {
    if (confirm(`Are you sure you want to delete order #${rowData.id}?`)) {
        axios.delete(`/api/orders/${rowData.id}`)
            .then(() => {
                refreshData();
            })
            .catch((error) => {
                console.error('Error deleting order:', error);
            });
    }
}

const viewDetails = (id) => {
    Inertia.visit(route('orderdetailsview', { id: id }));
};


onMounted(() => {
    refreshData();
});
</script>

<style scoped>
:deep(.p-datatable) {
    @apply bg-white rounded-lg shadow-sm;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
    @apply bg-gray-50 text-gray-700 font-semibold py-4;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
    @apply transition-colors duration-200;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
    @apply bg-gray-50;
}

:deep(.p-button) {
    @apply transition-transform duration-200;
}

:deep(.p-button:hover) {
    @apply transform scale-105;
}

:deep(.p-inputtext) {
    @apply border-gray-300 focus:border-emerald-500 focus:ring-emerald-500;
}

:deep(.p-tag) {
    @apply rounded-full font-medium;
}

:deep(.p-paginator) {
    @apply bg-white border-t border-gray-200 rounded-b-lg;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page.p-highlight) {
    @apply bg-emerald-500 text-white;
}
</style>
