<template>
  <Toast />

  <Head title="Add Rider" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Rider</h2>
        <Link href="/riders" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
        <i class="pi pi-arrow-left mr-2"></i>Back to Riders
        </Link>
      </div>
    </template>
    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Personal Information Section -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="field">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <InputText v-model="name" id="name" class="w-full" :class="{ 'p-invalid': submitted && !name }"
                      placeholder="Enter full name" />
                    <small class="p-error" v-if="submitted && !name">Name is required.</small>
                  </div>

                  <div class="field">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <InputText v-model="email" id="email" type="email" class="w-full"
                      :class="{ 'p-invalid': submitted && !email }" placeholder="Enter email address" />
                    <small class="p-error" v-if="submitted && !email">Email is required.</small>
                  </div>

                  <div class="field">
                    <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <InputText v-model="mobile" id="mobile" class="w-full"
                      :class="{ 'p-invalid': submitted && !mobile }" placeholder="Enter mobile number" />
                    <small class="p-error" v-if="submitted && !mobile">Mobile number is required.</small>
                  </div>

                  <div class="field">
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <InputNumber v-model="age" id="age" class="w-full" :min="18" :max="65" placeholder="Enter age"
                      :class="{ 'p-invalid': submitted && !age }" />
                    <small class="p-error" v-if="submitted && !age">Age is required and must be between 18-65.</small>
                  </div>

                  <div class="field">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <Dropdown v-model="gender" :options="genderOptions" optionLabel="label" optionValue="value"
                      placeholder="Select gender" class="w-full" :class="{ 'p-invalid': submitted && !gender }" />
                    <small class="p-error" v-if="submitted && !gender">Gender is required.</small>
                  </div>


                </div>
              </div>

              <!-- Vehicle Information Section -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vehicle Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="field">
                    <label for="vehicle" class="block text-sm font-medium text-gray-700">Vehicle</label>
                    <Dropdown v-model="vehicle" :options="vehicleOptions" optionLabel="name" optionValue="id"
                      placeholder="Select vehicle" class="w-full" :class="{ 'p-invalid': submitted && !vehicle }" />
                    <small class="p-error" v-if="submitted && !vehicle">Vehicle is required.</small>
                  </div>

                  <div class="field">
                    <label for="vehicleNo" class="block text-sm font-medium text-gray-700">Vehicle Number</label>
                    <InputText v-model="vehicleNo" id="vehicleNo" class="w-full"
                      :class="{ 'p-invalid': submitted && !vehicleNo }" placeholder="Enter vehicle number" />
                    <small class="p-error" v-if="submitted && !vehicleNo">Vehicle number is required.</small>
                  </div>

                  <div class="field">
                    <label for="zone" class="block text-sm font-medium text-gray-700">Zone</label>
                    <Dropdown id="zone" v-model="zone" :options="zoneOptions" optionLabel="name" optionValue="id"
                      placeholder="Select Zone" class="w-full" :class="{ 'p-invalid': submitted && !zone }" />
                    <small class="p-error" v-if="submitted && !zone">Zone is required.</small>
                  </div>
                </div>
              </div>

              <!-- Account Credentials Section -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Credentials</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="field">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <Password v-model="password" id="password" class="w-full"
                      :class="{ 'p-invalid': submitted && !password }" :feedback="true" toggleMask />
                    <small class="p-error" v-if="submitted && !password">Password is required.</small>
                  </div>

                  <div class="field">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm
                      Password</label>
                    <Password v-model="confirmPassword" id="confirmPassword" class="w-full"
                      :class="{ 'p-invalid': submitted && !confirmPassword }" :feedback="false" toggleMask />
                    <small class="p-error" v-if="submitted && !confirmPassword">Please confirm password.</small>
                    <small class="p-error" v-if="submitted && password !== confirmPassword">Passwords do not
                      match.</small>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-3">
                <Button type="button" label="Cancel" class="p-button-secondary" @click="$inertia.visit('/riders')" />
                <Button type="submit" label="Create Rider Account" icon="pi pi-user-plus" :loading="isSubmitting" />
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Password from 'primevue/password';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const toast = useToast();

// Form data
const name = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const age = ref(null);
const gender = ref(null);
const role = ref("rider");
const vehicle = ref(null);
const vehicleNo = ref("");
const zone = ref([]);

// Form state
const submitted = ref(false);
const isSubmitting = ref(false);

// Options for dropdowns
const genderOptions = [
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' }
];



const vehicleOptions = ref([]);
const zoneOptions = ref([]);

// Fetch vehicle options
const fetchVehicles = async () => {
  try {
    const response = await axios.get("/api/vehicles");
    vehicleOptions.value = response.data;
  } catch (error) {
    console.error("Error fetching vehicles:", error);
  }
};

// Fetch zone options
const fetchZones = async () => {
  try {
    const response = await axios.get("/api/zones");
    zoneOptions.value = response.data.zones;
    console.log("zoneOptions:", zoneOptions.value); // Add this line to debug
  } catch (error) {
    console.error("Error fetching zones:", error);
  }
};

onMounted(() => {
  fetchVehicles();
  fetchZones();
});

// Form submission handler
const handleSubmit = async () => {
  submitted.value = true;

  // Debugging logs
  console.log("name:", name.value);
  console.log("email:", email.value);
  console.log("password:", password.value);
  console.log("confirmPassword:", confirmPassword.value);
  console.log("age:", age.value);
  console.log("gender:", gender.value);
  console.log("mobile:", mobile.value);
  console.log("vehicle:", vehicle.value);
  console.log("vehicleNo:", vehicleNo.value);
  console.log("zone:", zone.value);

  // Validation checks
  if (!name.value || !email.value || !password.value || !confirmPassword.value ||
    !age.value || !gender.value || !mobile.value || !vehicle.value || !vehicleNo.value || !zone.value) {
    toast.add({
      severity: 'error',
      summary: 'Validation Error',
      detail: 'Please fill in all required fields',
      life: 3000
    });
    return;
  }

  if (password.value !== confirmPassword.value) {
    toast.add({
      severity: 'error',
      summary: 'Password Mismatch',
      detail: 'Passwords do not match',
      life: 3000
    });
    return;
  }

  isSubmitting.value = true;

  try {
    const response = await axios.post("/api/register", {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirmPassword.value,
      age: age.value,
      gender: gender.value,
      mobile: mobile.value,
      status: 2,
      role: role.value,
      zone_id: zone.value,
    });

    const riderId = response.data.user.id;

    await axios.post("/api/vehicles/assign", {
      vehicle_id: vehicle.value,
      rider_id: riderId,
      vehicle_number: vehicleNo.value,
    });

    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Rider account created and vehicle assigned successfully',
      life: 3000
    });

    // Reset form
    name.value = "";
    email.value = "";
    password.value = "";
    confirmPassword.value = "";
    age.value = null;
    gender.value = null;
    mobile.value = "";
    vehicle.value = null;
    vehicleNo.value = "";
    zone.value = null;
    submitted.value = false;

  } catch (error) {
    let errorMessage = 'An unexpected error occurred';
    if (error.response?.data?.errors) {
      errorMessage = Object.values(error.response.data.errors).join(", ");
    }

    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: errorMessage,
      life: 5000
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.field {
  margin-bottom: 1rem;
}

:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-password),
:deep(.p-inputnumber) {
  width: 100%;
}

:deep(.p-password-input) {
  width: 100%;
}

:deep(.p-button) {
  min-width: 120px;
}

.p-error {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

:deep(.p-invalid) {
  border-color: #ef4444;
}
</style>
