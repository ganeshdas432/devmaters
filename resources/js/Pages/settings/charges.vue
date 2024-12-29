<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Delivery List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-semibold mr-4">Settings</h4>
          <button @click="showDialog = true" class="btn btn-primary">Create Charge</button>
          
                
        </div>
        <div class=" space-x-4 sm:-my-px sm:ms-4 sm:flex">
                  <NavLink :href="route('settings.company_details')" :active="route().current('settings.company_details')">
                    Details
                  </NavLink>
                  <NavLink :href="route('settings.sliders')" :active="route().current('settings.sliders')">
                    Sliders
                  </NavLink>
                  <NavLink :href="route('settings.charges')" :active="route().current('settings.charges')">
                    Charges
                  </NavLink>
                  <NavLink :href="route('settings.pages')" :active="route().current('settings.pages')">
                    Pages
                  </NavLink>
        </div>

               
        <DataTable :value="charges" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
          tableStyle="min-width: 50rem" class="mt-4">
          <Column field="zone_id" header="Zone ID" style="width: 10%"></Column>
          <Column field="fs_per_km" header="Food/KM" style="width: 10%"></Column>
          <Column field="gs_per_km" header="Grocery/KM" style="width: 10%"></Column>
          <Column field="free_delivery_on" header="Free Delivery above" style="width: 10%"></Column>
          <Column field="p_per_km" header="Porter/KM" style="width: 10%"></Column>
          <Column field="p_weight" header="Weight" style="width: 10%"></Column>
          <Column field="p_per_weight" header="Charge/Weight" style="width: 10%"></Column>
          <Column field="r_per_km" header="Ride/KM" style="width: 10%"></Column>
          <Column field="r_per_person" header="Ride/KM" style="width: 10%"></Column>
          <Column field="tax_per" header="Tax %" style="width: 10%"></Column>


          
          <Column header="Actions" style="width: 25%">
            <template #body="slotProps">
                <a :href="`/charges/${slotProps.data.id}/edit`" class="btn btn-secondary">Edit</a>
                <button @click="handleDelete(slotProps.data.id)" class="btn btn-danger">Delete</button>
            </template>
          </Column>
        </DataTable>
        <div v-if="charges.length === 0">No charges available.</div>


            </div>
        </div>

        <!-- PrimeVue Dialog for Add Charge Form -->
        <Dialog v-model:visible="showDialog" header="Create New Charge" :style="{ width: '50vw' }">
        <form @submit.prevent="submitForm">
            <!-- First row with 3 fields -->
            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-gray-700">Zone ID</label>
                    <input v-model="form.zone_id" type="text" id="zone_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="fs_per_km" class="block text-sm font-medium text-gray-700">FS Per KM</label>
                    <input v-model="form.fs_per_km" type="number" id="fs_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="gs_per_km" class="block text-sm font-medium text-gray-700">GS Per KM</label>
                    <input v-model="form.gs_per_km" type="number" id="gs_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>

            <!-- Second row with 3 fields -->
            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="p_per_km" class="block text-sm font-medium text-gray-700">P Per KM</label>
                    <input v-model="form.p_per_km" type="number" id="p_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="r_per_km" class="block text-sm font-medium text-gray-700">R Per KM</label>
                    <input v-model="form.r_per_km" type="number" id="r_per_km" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="free_delivery_on" class="block text-sm font-medium text-gray-700">Free Delivery On</label>
                    <input v-model="form.free_delivery_on" type="number" id="free_delivery_on" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
               
            </div>

            <!-- Third row with 3 fields -->
            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label for="r_per_person" class="block text-sm font-medium text-gray-700">R Per Person</label>
                    <input v-model="form.r_per_person" type="number" id="r_per_person" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="p_per_weight" class="block text-sm font-medium text-gray-700">P Per Weight</label>
                    <input v-model="form.p_per_weight" type="number" id="p_per_weight" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
                <div>
                    <label for="p_weight" class="block text-sm font-medium text-gray-700">P Weight</label>
                    <input v-model="form.p_weight" type="number" id="p_weight" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>

            <!-- Fourth row with 2 fields -->
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="tax_per" class="block text-sm font-medium text-gray-700">Tax Per</label>
                    <input v-model="form.tax_per" type="number" id="tax_per" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required />
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Create Charge</button>
        </form>
    </Dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import NavLink from '@/Components/NavLink.vue';
import Column from 'primevue/column';

// Reactive variables for form and dialog visibility
const charges = ref([]);
const showDialog = ref(false);
const form = ref({
    zone_id: '',
    fs_per_km: '',
    gs_per_km: '',
    p_per_km: '',
    r_per_km: '',
    free_delivery_on: false,
    r_per_person: '',
    p_per_weight: '',
    p_weight: '',
    tax_per: ''
});

// Fetch the list of charges
onMounted(async () => {
    try {
        const response = await axios.get('/api/charges');
        charges.value = response.data.charges;
    } catch (error) {
        console.error('Error fetching charges:', error);
    }
});

// Handle Delete action using Inertia DELETE request
function handleDelete(chargeId) {
    if (confirm(`Are you sure you want to delete this charge?`)) {
        Inertia.delete(`/charges/${chargeId}`);
    }
}

// Handle form submission
function submitForm() {
    Inertia.post('/charges', form.value, {
        onSuccess: () => {
            // Close the dialog and reset the form
            showDialog.value = false;
            form.value = {
                zone_id: '',
                fs_per_km: '',
                gs_per_km: '',
                p_per_km: '',
                r_per_km: '',
                free_delivery_on: '',
                r_per_person: '',
                p_per_weight: '',
                p_weight: '',
                tax_per: ''
            };
            // Reload the charges list (or redirect)
            axios.get('/api/charges').then(response => {
                charges.value = response.data.charges;
            });
        },
        onError: (error) => {
            console.error('Error creating charge:', error);
        }
    });
}
</script>

<style scoped>
/* Styling adjustments */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 8px;
    border: 1px solid #ddd;
}

.table th {
    background-color: #f4f4f4;
}

.btn {
    padding: 8px 12px;
    margin-right: 8px;
    cursor: pointer;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-secondary {
    background-color: #f39c12;
    color: white;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}
</style>
