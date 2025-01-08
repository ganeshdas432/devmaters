<template>

    <Head title="KYC Documents" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">KYC Documents</h2>
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
                            <InputText v-model="searchQuery" placeholder="Search documents..." class="w-full" />
                        </span>
                        <div class="flex gap-2">
                            <Button icon="pi pi-filter" severity="secondary" label="Filters" />
                            <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
                        </div>
                    </div>

                    <!-- Enhanced DataTable -->
                    <DataTable :value="documents" :paginator="true" :rows="10" :rowsPerPageOptions="[10, 20, 50]"
                        v-model:filters="filters" filterDisplay="menu" :loading="loading" stripedRows showGridlines
                        :globalFilterFields="['user_id', 'kyc_type', 'document_type', 'document_number', 'status']"
                        tableStyle="min-width: 50rem" class="p-datatable-lg">
                        <Column field="id" header="ID" sortable style="width: 5%">
                            <template #body="{ data }">
                                <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                            </template>
                        </Column>

                        <Column field="user_id" header="User ID" sortable style="width: 10%">
                            <template #body="{ data }">
                                <div class="font-medium">{{ data.user_id }}</div>
                            </template>
                        </Column>

                        <Column field="kyc_type" header="KYC Type" sortable style="width: 10%">
                            <template #body="{ data }">
                                <div class="font-medium">{{ data.kyc_type }}</div>
                            </template>
                        </Column>

                        <Column field="document_type" header="Document Type" sortable style="width: 15%">
                            <template #body="{ data }">
                                <div class="font-medium">{{ data.document_type }}</div>
                            </template>
                        </Column>

                        <Column field="document_number" header="Document Number" sortable style="width: 15%">
                            <template #body="{ data }">
                                <div class="font-medium">{{ data.document_number }}</div>
                            </template>
                        </Column>

                        <Column field="status" header="Status" sortable style="width: 10%">
                            <template #body="slotProps">
                                <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data.status)"
                                    class="text-xs px-3 py-1">
                                    <i :class="getStatusIcon(slotProps.data.status)" class="mr-1"></i>
                                    {{ slotProps.data.status }}
                                </Tag>
                            </template>
                        </Column>

                        <Column header="Actions" :exportable="false" style="width: 15%">
                            <template #body="slotProps">
                                <div class="flex gap-2 justify-center">
                                    <Button icon="pi pi-pencil" text rounded severity="info"
                                        @click="handleEdit(slotProps.data)" v-tooltip.top="'Edit Document'" />
                                    <Button icon="pi pi-eye" text rounded severity="success"
                                        v-tooltip.top="'View Details'" />
                                    <Button icon="pi pi-trash" text rounded severity="danger"
                                        v-tooltip.top="'Delete Document'" />
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
import Tag from 'primevue/tag';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Tooltip from 'primevue/tooltip';

const documents = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filters = ref({
    global: { value: null, matchMode: 'contains' },
});

const getStatusIcon = (status) => {
    switch (status) {
        case 'approved':
            return 'pi pi-check-circle';
        case 'pending':
            return 'pi pi-clock';
        case 'rejected':
            return 'pi pi-times-circle';
        default:
            return 'pi pi-question-circle';
    }
};

const getSeverity = (status) => {
    switch (status) {
        case 'rejected':
            return 'danger';
        case 'approved':
            return 'success';
        case 'pending':
            return 'warning';
        default:
            return 'info';
    }
};

const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/kyc-documents');
        documents.value = response.data.kyc_documents;
    } catch (error) {
        console.error('Error fetching documents data:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    refreshData();
});

function handleEdit(rowData) {
    Inertia.visit(`/appuser/edit-document/${rowData.id}`);
}
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
