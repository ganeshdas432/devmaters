<template>

    <Head title="Rider Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rider Management</h2>

            </div>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="card">
                    <!-- Search and Filter Section -->
                    <div
                        class="mb-4 flex flex-wrap gap-4 justify-between items-center p-4 border-b bg-gray-50 rounded-t-lg">
                        <span class="p-input-icon-left w-full md:w-96">
                            <i class="pi pi-search" />
                            <InputText v-model="searchQuery" placeholder="Search riders..." class="w-full" />
                        </span>
                        <div class="flex gap-2">
                            <Button icon="pi pi-filter" severity="secondary" label="Filters" />
                            <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
                            <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center"
                                :href="route('add.rider')">
                                <i class="pi pi-plus mr-2"></i>
                                Add Rider
                            </a>

                        </div>
                    </div>

                    <!-- Tabs Section -->
                    <TabView v-model:activeIndex="activeTab">
                        <TabPanel header="Active">
                            <DataTable :value="filteredRiders('Active')" :paginator="true" :rows="10"
                                :rowsPerPageOptions="[10, 20, 50]" v-model:filters="filters" filterDisplay="menu"
                                :loading="loading" stripedRows showGridlines
                                :globalFilterFields="['name', 'mobile', 'status.title']" tableStyle="min-width: 50rem"
                                class="p-datatable-lg">
                                <Column field="id" header="ID" sortable style="width: 10%">
                                    <template #body="{ data }">
                                        <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                                    </template>
                                </Column>

                                <Column field="name" header="Name" sortable style="width: 25%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                {{ data.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ data.name }}</div>
                                                <div class="text-sm text-gray-500">{{ data.email }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="mobile" header="Mobile" sortable style="width: 20%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <i class="pi pi-phone text-gray-400"></i>
                                            {{ data.mobile }}
                                        </div>
                                    </template>
                                </Column>

                                <Column field="status" header="Status" sortable style="width: 15%">
                                    <template #body="slotProps">
                                        <Tag :value="slotProps.data.status.title"
                                            :severity="getSeverity(slotProps.data.status.title)"
                                            class="text-xs px-3 py-1">
                                            <i :class="getStatusIcon(slotProps.data.status.title)" class="mr-1"></i>
                                            {{ slotProps.data.status.title }}
                                        </Tag>
                                    </template>
                                </Column>

                                <Column header="Actions" :exportable="false" style="width: 15%">
                                    <template #body="slotProps">
                                        <div class="flex gap-2 justify-center">

                                            <Button icon="pi pi-eye" text rounded severity="success"
                                                v-tooltip.top="'View Details'" />

                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </TabPanel>
                        <TabPanel header="Applied">
                            <DataTable :value="filteredRiders('Applied')" :paginator="true" :rows="10"
                                :rowsPerPageOptions="[10, 20, 50]" v-model:filters="filters" filterDisplay="menu"
                                :loading="loading" stripedRows showGridlines
                                :globalFilterFields="['name', 'mobile', 'status.title']" tableStyle="min-width: 50rem"
                                class="p-datatable-lg">
                                <Column field="id" header="ID" sortable style="width: 10%">
                                    <template #body="{ data }">
                                        <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                                    </template>
                                </Column>

                                <Column field="name" header="Name" sortable style="width: 25%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                {{ data.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ data.name }}</div>
                                                <div class="text-sm text-gray-500">{{ data.email }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="mobile" header="Mobile" sortable style="width: 20%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <i class="pi pi-phone text-gray-400"></i>
                                            {{ data.mobile }}
                                        </div>
                                    </template>
                                </Column>

                                <Column field="status" header="Status" sortable style="width: 15%">
                                    <template #body="slotProps">
                                        <Tag :value="slotProps.data.status.title"
                                            :severity="getSeverity(slotProps.data.status.title)"
                                            class="text-xs px-3 py-1">
                                            <i :class="getStatusIcon(slotProps.data.status.title)" class="mr-1"></i>
                                            {{ slotProps.data.status.title }}
                                        </Tag>
                                    </template>
                                </Column>

                                <Column header="Actions" :exportable="false" style="width: 15%">
                                    <template #body="slotProps">
                                        <div class="flex gap-2 justify-center">

                                            <Button icon="pi pi-eye" text rounded severity="success"
                                                v-tooltip.top="'View Details'" />

                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </TabPanel>
                        <TabPanel header="Rejected">
                            <DataTable :value="filteredRiders('Rejected')" :paginator="true" :rows="10"
                                :rowsPerPageOptions="[10, 20, 50]" v-model:filters="filters" filterDisplay="menu"
                                :loading="loading" stripedRows showGridlines
                                :globalFilterFields="['name', 'mobile', 'status.title']" tableStyle="min-width: 50rem"
                                class="p-datatable-lg">
                                <Column field="id" header="ID" sortable style="width: 10%">
                                    <template #body="{ data }">
                                        <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                                    </template>
                                </Column>

                                <Column field="name" header="Name" sortable style="width: 25%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                {{ data.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ data.name }}</div>
                                                <div class="text-sm text-gray-500">{{ data.email }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="mobile" header="Mobile" sortable style="width: 20%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <i class="pi pi-phone text-gray-400"></i>
                                            {{ data.mobile }}
                                        </div>
                                    </template>
                                </Column>

                                <Column field="status" header="Status" sortable style="width: 15%">
                                    <template #body="slotProps">
                                        <Tag :value="slotProps.data.status.title"
                                            :severity="getSeverity(slotProps.data.status.title)"
                                            class="text-xs px-3 py-1">
                                            <i :class="getStatusIcon(slotProps.data.status.title)" class="mr-1"></i>
                                            {{ slotProps.data.status.title }}
                                        </Tag>
                                    </template>
                                </Column>

                                <Column header="Actions" :exportable="false" style="width: 15%">
                                    <template #body="slotProps">
                                        <div class="flex gap-2 justify-center">

                                            <Button icon="pi pi-eye" text rounded severity="success"
                                                v-tooltip.top="'View Details'" />

                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </TabPanel>
                        <TabPanel header="Deactivated">
                            <DataTable :value="filteredRiders('Deactivated')" :paginator="true" :rows="10"
                                :rowsPerPageOptions="[10, 20, 50]" v-model:filters="filters" filterDisplay="menu"
                                :loading="loading" stripedRows showGridlines
                                :globalFilterFields="['name', 'mobile', 'status.title']" tableStyle="min-width: 50rem"
                                class="p-datatable-lg">
                                <Column field="id" header="ID" sortable style="width: 10%">
                                    <template #body="{ data }">
                                        <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                                    </template>
                                </Column>

                                <Column field="name" header="Name" sortable style="width: 25%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                {{ data.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ data.name }}</div>
                                                <div class="text-sm text-gray-500">{{ data.email }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="mobile" header="Mobile" sortable style="width: 20%">
                                    <template #body="{ data }">
                                        <div class="flex items-center gap-2">
                                            <i class="pi pi-phone text-gray-400"></i>
                                            {{ data.mobile }}
                                        </div>
                                    </template>
                                </Column>

                                <Column field="status" header="Status" sortable style="width: 15%">
                                    <template #body="slotProps">
                                        <Tag :value="slotProps.data.status.title"
                                            :severity="getSeverity(slotProps.data.status.title)"
                                            class="text-xs px-3 py-1">
                                            <i :class="getStatusIcon(slotProps.data.status.title)" class="mr-1"></i>
                                            {{ slotProps.data.status.title }}
                                        </Tag>
                                    </template>
                                </Column>

                                <Column header="Actions" :exportable="false" style="width: 15%">
                                    <template #body="slotProps">
                                        <div class="flex gap-2 justify-center">

                                            <Button icon="pi pi-eye" text rounded severity="success"
                                                v-tooltip.top="'View Details'" />

                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </TabPanel>
                    </TabView>
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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

const customers = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filters = ref({
    global: { value: null, matchMode: 'contains' },
});
const activeTab = ref(0);

const getStatusIcon = (status) => {
    switch (status) {
        case 'Active':
            return 'pi pi-check-circle';
        case 'Deactive':
            return 'pi pi-times-circle';
        default:
            return 'pi pi-question-circle';
    }
};

const getSeverity = (status) => {
    switch (status) {
        case 'Deactive':
            return 'danger';
        case 'Active':
            return 'success';
        default:
            return 'info';
    }
};

const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/appuser/rider');
        customers.value = response.data.users;
    } catch (error) {
        console.error('Error fetching riders data:', error);
    } finally {
        loading.value = false;
    }
};

const filteredRiders = (status) => {
    return customers.value.filter(customer => {
        switch (status) {
            case 'Active':
                return customer.status.id === 1;
            case 'Applied':
                return customer.status.id === 2;
            case 'Rejected':
                return customer.status.id === 3;
            case 'Deactivated':
                return customer.status.id === 4;
            default:
                return false;
        }
    });
};

onMounted(() => {
    refreshData();
});

function handleEdit(rowData) {
    Inertia.visit(`/appuser/edit/${rowData.id}`);
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
