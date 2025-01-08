<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Units</h2>
        </template>

        <div class="py-4">
            <div class="grid grid-cols-4 gap-4">
                <div class="card bg-white p-6 mb-8 col-span-1">
                    <form @submit.prevent="submitForm" class="w-full">
                        <div class="card bg-white p-4">
                            <div class="flex flex-col gap-4 mb-4">
                                <div class="min-w-[100px]">
                                    <label for="title" class="block text-sm font-medium">Unit Name</label>
                                    <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
                                </div>

                                <div class="min-w-[100px] flex items-end">
                                    <Button type="submit" class="btn btn-primary w-full">Add Unit</Button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card bg-white p-6 mb-8 col-span-3">
                    <DataTable :value="units" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem">
                        <Column field="id" header="ID" style="width: 25%"></Column>
                        <Column field="title" header="Title" style="width: 25%"></Column>

                        <Column header="Actions" style="width: 4rem">
                            <template #body="slotProps">
                                <Button type="button" icon="pi pi-pencil" text size="small"
                                    class="p-button-rounded p-button-info" @click="openEditDialog(slotProps.data)" />
                                <Button type="button" icon="pi pi-trash" text size="small"
                                    class="p-button-rounded p-button-danger" @click="handleDelete(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
            <Toast ref="toast" />
        </div>
    </AuthenticatedLayout>
    <Dialog v-model:visible="editDialogVisible" header="Edit Unit" :modal="true" :closable="true"
        class="rounded-lg shadow-lg">
        <form @submit.prevent="submitEditForm" class="w-full max-w-lg">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="editTitle" class="block text-sm font-medium mb-1">Title</label>
                    <InputText id="editTitle" v-model="editForm.title" class="w-full p-2 border rounded" />
                </div>
                <div>
                    <label for="editValue" class="block text-sm font-medium mb-1">Value</label>
                    <InputText id="editValue" v-model="editForm.value" class="w-full p-2 border rounded" />
                </div>
            </div>
            <Button type="submit" class="btn btn-primary w-full">Update Unit</Button>
        </form>
    </Dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

const units = ref([]);
const editDialogVisible = ref(false);
const editForm = ref({ id: '', title: '' });
const toast = ref(null);

const Form = useForm({
    title: '',
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/unitlist');
        units.value = response.data.units;
    } catch (error) {
        console.error('Error fetching units data:', error);
    }
});

const submitForm = () => {
    const formData = new FormData();
    formData.append('title', Form.title);

    axios.post(route('unit.store'), formData)
        .then((response) => {
            Form.reset();
            fetchUnits();
            toast.value.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        })
        .catch((error) => {
            toast.value.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
        });
};

const openEditDialog = (unit) => {
    editForm.value = { ...unit };
    editDialogVisible.value = true;
};

const submitEditForm = () => {
    const formData = new FormData();
    formData.append('title', editForm.value.title);

    axios.post(`/api/unit/${editForm.value.id}`, formData)
        .then((response) => {
            editDialogVisible.value = false;
            fetchUnits();
            toast.value.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
        })
        .catch((error) => {
            toast.value.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 3000 });
        });
};

const handleDelete = (rowData) => {
    if (confirm(`Are you sure you want to delete ${rowData.title}?`)) {
        axios.delete(`/api/unit_delete/${rowData.id}`)
            .then((response) => {
                console.log(response.data.message);
                units.value = units.value.filter((unit) => unit.id !== rowData.id);
                toast.value.add({ severity: 'success', summary: 'Success', detail: 'Unit deleted successfully', life: 3000 });
            })
            .catch((error) => {
                toast.value.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete unit', life: 3000 });
            });
    }
};

const fetchUnits = async () => {
    try {
        const response = await axios.get('/api/unitlist');
        units.value = response.data.units;
    } catch (error) {
        console.error('Error fetching units data:', error);
    }
};

// Example toast for refund settings update
const updateRefundSettings = () => {
    // ... logic to update refund settings ...
    toast.value.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Refund settings updated successfully',
        life: 3000
    });
};
</script>

<style scoped>
.card {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.btn-primary {
    background-color: #1d4ed8;
    border: none;
    color: #fff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.p-button-rounded {
    border-radius: 50%;
}

.p-button-info {
    background-color: #3b82f6;
    border: none;
}

.p-button-info:hover {
    background-color: #60a5fa;
}

.p-button-danger {
    background-color: #ef4444;
    border: none;
}

.p-button-danger:hover {
    background-color: #f87171;
}
</style>
