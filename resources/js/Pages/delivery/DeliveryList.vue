<template>

    <Head title="Delivery Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Delivery Management</h2>
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
                            <InputText v-model="searchQuery" placeholder="Search deliveries..." class="w-full" />
                        </span>
                        <div class="flex gap-2">
                            <Button icon="pi pi-filter" severity="secondary" label="Filters" />
                            <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
                        </div>
                    </div>

                    <!-- Enhanced DataTable -->
                    <DataTable :value="deliveries" :paginator="true" :rows="10" :rowsPerPageOptions="[10, 20, 50]"
                        v-model:filters="filters" filterDisplay="menu" :loading="loading" stripedRows showGridlines
                        :globalFilterFields="['id', 'customer.name', 'delivery_type', 'order_payment_status']"
                        tableStyle="min-width: 50rem" class="p-datatable-lg">
                        <Column field="id" header="ID" sortable style="width: 8%">
                            <template #body="{ data }">
                                <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                            </template>
                        </Column>

                        <Column field="customer.name" header="Customer" sortable style="width: 20%">
                            <template #body="{ data }">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        {{ data.customer.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="font-medium">{{ data.customer.name }}</div>
                                </div>
                            </template>
                        </Column>

                        <Column field="order_value" header="Order Value" sortable style="width: 12%">
                            <template #body="{ data }">
                                <div class="font-medium text-gray-900">
                                    ${{ Number(data.order_value).toFixed(2) }}
                                </div>
                            </template>
                        </Column>

                        <Column field="delivery_cost" header="Delivery Cost" sortable style="width: 12%">
                            <template #body="{ data }">
                                <div class="font-medium text-gray-900">
                                    ${{ Number(data.delivery_cost).toFixed(2) }}
                                </div>
                            </template>
                        </Column>

                        <Column field="distance" header="Distance" sortable style="width: 12%">
                            <template #body="{ data }">
                                <div class="flex items-center gap-1">
                                    <i class="pi pi-map-marker text-gray-400"></i>
                                    <span>{{ data.distance }} km</span>
                                </div>
                            </template>
                        </Column>

                        <Column field="delivery_type" header="Type" sortable style="width: 12%">
                            <template #body="{ data }">
                                <Tag :value="data.delivery_type" :severity="getDeliveryTypeSeverity(data.delivery_type)"
                                    class="text-xs px-3 py-1">
                                    <i :class="getDeliveryTypeIcon(data.delivery_type)" class="mr-1"></i>
                                    {{ data.delivery_type }}
                                </Tag>
                            </template>
                        </Column>

                        <Column field="order_payment_status" header="Payment" sortable style="width: 12%">
                            <template #body="{ data }">
                                <Tag :value="data.order_payment_status"
                                    :severity="getPaymentStatusSeverity(data.order_payment_status)"
                                    class="text-xs px-3 py-1">
                                    <i :class="getPaymentStatusIcon(data.order_payment_status)" class="mr-1"></i>
                                    {{ data.order_payment_status }}
                                </Tag>
                            </template>
                        </Column>

                        <Column header="Actions" :exportable="false" style="width: 12%">
                            <template #body="slotProps">
                                <div class="flex gap-2 justify-center">
                                    <Button icon="pi pi-eye" text rounded severity="info"
                                        @click="viewDetails(slotProps.data.id)" v-tooltip.top="'View Details'" />
                                    <Button icon="pi pi-trash" text rounded severity="danger"
                                        @click="handleDelete(slotProps.data)" v-tooltip.top="'Delete Delivery'" />
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

const deliveries = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filters = ref({
    global: { value: null, matchMode: 'contains' },
});

const getDeliveryTypeIcon = (type) => {
    switch (type.toLowerCase()) {
        case 'express':
            return 'pi pi-bolt';
        case 'standard':
            return 'pi pi-truck';
        case 'scheduled':
            return 'pi pi-calendar';
        default:
            return 'pi pi-box';
    }
};

const getDeliveryTypeSeverity = (type) => {
    switch (type.toLowerCase()) {
        case 'express':
            return 'danger';
        case 'standard':
            return 'success';
        case 'scheduled':
            return 'info';
        default:
            return 'secondary';
    }
};

const getPaymentStatusIcon = (status) => {
    switch (status.toLowerCase()) {
        case 'paid':
            return 'pi pi-check-circle';
        case 'pending':
            return 'pi pi-clock';
        case 'failed':
            return 'pi pi-times-circle';
        default:
            return 'pi pi-question-circle';
    }
};

const getPaymentStatusSeverity = (status) => {
    switch (status.toLowerCase()) {
        case 'paid':
            return 'success';
        case 'pending':
            return 'warning';
        case 'failed':
            return 'danger';
        default:
            return 'secondary';
    }
};

const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/all_deliveries');
        deliveries.value = response.data.deliveries;
    } catch (error) {
        console.error('Error fetching deliveries data:', error);
    } finally {
        loading.value = false;
    }
};
const viewDetails = (id) => {
    Inertia.visit(route('delivery.show', { id: id }));
};
function handleDelete(rowData) {
    if (confirm(`Are you sure you want to delete delivery #${rowData.id}?`)) {
        axios.delete(`/api/deliveries/${rowData.id}`)
            .then(() => {
                refreshData();
            })
            .catch((error) => {
                console.error('Error deleting delivery:', error);
            });
    }
}

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
