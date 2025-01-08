<template>

    <Head title="User Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Profile</h2>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Information</h3>
                            <Button v-if="canActivateAccount" label="Activate Account" class="ml-4"
                                @click="activateAccount" />
                        </div>
                        <div class="mt-5" v-if="user">
                            <p><strong>Name:</strong> {{ user.name }}</p>
                            <p><strong>Email:</strong> {{ user.email }}</p>
                            <p><strong>Mobile:</strong> {{ user.mobile }}</p>
                            <p><strong>Age:</strong> {{ user.age }}</p>
                            <p><strong>Gender:</strong> {{ user.gender }}</p>
                            <p><strong>Role:</strong> {{ user.role }}</p>
                            <p><strong>Status:</strong> {{ user.status }}</p>
                        </div>
                        <div v-else>
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">KYC Documents</h3>
                            <Button label="Upload KYC" class="ml-4" @click="uploadKyc" />
                        </div>
                        <div class="mt-5" v-if="user">
                            <DataTable :value="user.kyc" :paginator="true" :rows="10" :rowsPerPageOptions="[10, 20, 50]"
                                stripedRows showGridlines tableStyle="min-width: 50rem" class="p-datatable-lg">
                                <Column field="id" header="ID" sortable style="width: 5%">
                                    <template #body="{ data }">
                                        <span class="font-semibold text-gray-700">#{{ data.id }}</span>
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
                                        <Tag :value="slotProps.data.status"
                                            :severity="getSeverity(slotProps.data.status)" class="text-xs px-3 py-1">
                                            <i :class="getStatusIcon(slotProps.data.status)" class="mr-1"></i>
                                            {{ slotProps.data.status }}
                                        </Tag>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                        <div v-else>
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Tag from 'primevue/tag';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

const user = ref(null);
const loading = ref(true);
const { props } = usePage();
const userId = ref(props.userId);

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

const fetchUserData = async (id) => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/user/view/${id}`);
        user.value = response.data.user;
    } catch (error) {
        console.error('Error fetching user data:', error);
    } finally {
        loading.value = false;
    }
};

const canActivateAccount = computed(() => {
    return user.value && user.value.kyc.every(doc => doc.status === 'approved');
});

const activateAccount = async () => {
    try {
        await axios.put(`/api/user/activate/${user.value.id}`);
        user.value.status = 1; // Update user status locally
        alert('Account activated successfully');
    } catch (error) {
        console.error('Error activating account:', error);
        alert('Failed to activate account');
    }
};

const uploadKyc = () => {
    Inertia.visit(`/kyc/upload/${user.value.id}/${user.value.role}`);
};

onMounted(() => {
    fetchUserData(userId.value).catch(error => {
        console.error('Error in onMounted:', error);
    });
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

:deep(.p-tag) {
    @apply rounded-full font-medium;
}
</style>
