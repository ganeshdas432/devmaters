<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-4">Order Settings</h3>
        <form @submit.prevent="submitCancellationReasonForm" class="w-full mb-4">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label for="reason" class="block text-sm font-medium mb-1">Cancellation Reason</label>
                    <InputText id="reason" v-model="cancellationReasonForm.reason" class="w-full p-2 border rounded" />
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium mb-1">Role</label>
                    <Dropdown id="role" v-model="selectedRole" :options="roles" optionLabel="title" class="w-full" />
                </div>
            </div>
            <Button type="submit" class="btn btn-primary w-full">Add Reason</Button>
        </form>

        <DataTable :value="cancellationReasons" class="p-datatable-sm">
            <Column field="reason" header="Reason" sortable></Column>
            <Column field="type" header="Role" sortable></Column>
            <Column header="Actions">
                <template #body="{ data }">
                    <Button icon="pi pi-pencil" text rounded severity="info" @click="editCancellationReason(data)" />
                    <Button icon="pi pi-trash" text rounded severity="danger" @click="deleteCancellationReason(data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="showEditDialog" :modal="true" :style="{ width: '50vw' }" header="Edit Reason">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Reason</label>
                    <InputText v-model="cancellationReasonForm.reason" class="w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <Dropdown v-model="selectedRole" :options="roles" optionLabel="title" class="w-full" />
                </div>
            </div>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancel" severity="secondary" @click="showEditDialog = false" />
                    <Button label="Save" severity="success" @click="updateCancellationReason" />
                </div>
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import { useToast } from "primevue/usetoast";

// Cancellation Reasons
const cancellationReasonForm = useForm({
    reason: '',
    type: null,
});

const cancellationReasons = ref([]);
const roles = ref([]);
const selectedRole = ref(null);
const showEditDialog = ref(false);
const editingReason = ref(null);

// Fetch cancellation reasons
const fetchCancellationReasons = async () => {
    try {
        const response = await axios.get('/api/cancellation-reasons');
        cancellationReasons.value = response.data;
    } catch (error) {
        console.error('Error fetching cancellation reasons:', error);
    }
};

// Fetch roles
const fetchRoles = async () => {
    try {
        const response = await axios.get('/api/roles');
        roles.value = response.data;
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

// Submit cancellation reason form
const submitCancellationReasonForm = async () => {
    try {
        cancellationReasonForm.type = selectedRole.value.title;
        await axios.post('/api/cancellation-reasons', cancellationReasonForm);
        fetchCancellationReasons();
        cancellationReasonForm.reset();
        selectedRole.value = null;
    } catch (error) {
        console.error('Error adding cancellation reason:', error);
    }
};

// Edit cancellation reason
const editCancellationReason = (reason) => {
    editingReason.value = reason;
    cancellationReasonForm.reason = reason.reason;
    selectedRole.value = roles.value.find(role => role.title === reason.type);
    showEditDialog.value = true;
};

// Update cancellation reason
const updateCancellationReason = async () => {
    try {
        cancellationReasonForm.type = selectedRole.value.title;
        await axios.put(`/api/cancellation-reasons/${editingReason.value.id}`, cancellationReasonForm);
        fetchCancellationReasons();
        showEditDialog.value = false;
        selectedRole.value = null;
    } catch (error) {
        console.error('Error updating cancellation reason:', error);
    }
};

// Delete cancellation reason
const deleteCancellationReason = async (reason) => {
    try {
        await axios.delete(`/api/cancellation-reasons/${reason.id}`);
        fetchCancellationReasons();
    } catch (error) {
        console.error('Error deleting cancellation reason:', error);
    }
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchCancellationReasons();
    fetchRoles();
});
</script>
