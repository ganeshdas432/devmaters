<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Vehicles</h3>
            <Button @click="showAddVehicleDialog = true" icon="pi pi-plus" label="Add New Vehicle" severity="success" />
        </div>

        <!-- Vehicles DataTable -->
        <DataTable :value="vehicles" :loading="loadingVehicles" stripedRows showGridlines class="p-datatable-sm">
            <Column field="name" header="Vehicle Name" sortable></Column>
            <Column field="delivery_area" header="Delivery Area" sortable></Column>
            <Column header="Actions">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" text rounded severity="info" @click="editVehicle(data)" />
                        <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteVehicle(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <!-- Add/Edit Vehicle Dialog -->
        <Dialog v-model:visible="showAddVehicleDialog" :modal="true" :style="{ width: '50vw' }" :header="editingVehicle ? 'Edit Vehicle' : 'Add New Vehicle'">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Vehicle Name</label>
                    <InputText v-model="vehicleForm.name" class="w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Delivery Area</label>
                    <InputText v-model="vehicleForm.delivery_area" class="w-full" />
                </div>
            </div>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancel" severity="secondary" @click="showAddVehicleDialog = false" />
                    <Button :label="editingVehicle ? 'Update' : 'Save'" severity="success" @click="saveVehicle" />
                </div>
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:visible="showDeleteVehicleDialog" modal header="Confirm Delete" :style="{ width: '450px' }">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Are you sure you want to delete this vehicle?</span>
            </div>
            <template #footer>
                <Button label="No" severity="secondary" @click="showDeleteVehicleDialog = false" />
                <Button label="Yes" severity="danger" @click="deleteVehicle" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

// Vehicle management state
const vehicles = ref([]);
const loadingVehicles = ref(false);
const showAddVehicleDialog = ref(false);
const showDeleteVehicleDialog = ref(false);
const editingVehicle = ref(null);

const vehicleForm = ref({
    name: '',
    delivery_area: ''
});

// Fetch vehicles
const fetchVehicles = async () => {
    loadingVehicles.value = true;
    try {
        const response = await axios.get('/api/vehicles');
        vehicles.value = response.data;
    } catch (error) {
        console.error('Error fetching vehicles:', error);
    } finally {
        loadingVehicles.value = false;
    }
};

// Edit vehicle
const editVehicle = (vehicle) => {
    editingVehicle.value = vehicle;
    vehicleForm.value = { ...vehicle };
    showAddVehicleDialog.value = true;
};

// Save vehicle
const saveVehicle = async () => {
    try {
        if (editingVehicle.value) {
            await axios.put(`/api/vehicles/${editingVehicle.value.id}`, vehicleForm.value);
        } else {
            await axios.post('/api/vehicles', vehicleForm.value);
        }
        showAddVehicleDialog.value = false;
        fetchVehicles();
        resetVehicleForm();
    } catch (error) {
        console.error('Error saving vehicle:', error);
    }
};

// Confirm delete vehicle
const confirmDeleteVehicle = (vehicle) => {
    editingVehicle.value = vehicle;
    showDeleteVehicleDialog.value = true;
};

// Delete vehicle
const deleteVehicle = async () => {
    try {
        await axios.delete(`/api/vehicles/${editingVehicle.value.id}`);
        showDeleteVehicleDialog.value = false;
        fetchVehicles();
    } catch (error) {
        console.error('Error deleting vehicle:', error);
    }
};

// Reset vehicle form
const resetVehicleForm = () => {
    vehicleForm.value = {
        name: '',
        delivery_area: ''
    };
    editingVehicle.value = null;
};

onMounted(() => {
    fetchVehicles();
});
</script>