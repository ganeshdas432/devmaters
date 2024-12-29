<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Company Details</h2>
    </template>

    <div class="py-12">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-semibold mr-4">Settings</h4>
          
                
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
                  <NavLink :href="route('settings.pages')" :active="route().current('settings.charges')">
                    Pages
                  </NavLink>
                 

        </div>

        <div class="card flex justify-center p-4">
          <form @submit.prevent="submitForm" class="w-full">
            <div class="grid grid-cols-1 gap-4 mb-4">
              <div>
                <label for="title" class="block text-sm font-medium mb-1">Company Name</label>
                <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
              </div>
              <div>
                <label for="mobile" class="block text-sm font-medium mb-1">Company Mobile No</label>
                <InputText id="mobile" v-model="Form.mobile" class="w-full p-2 border rounded" />
              </div>
              <div>
                <label for="email" class="block text-sm font-medium mb-1">Company Email</label>
                <InputText id="email" v-model="Form.email" class="w-full p-2 border rounded" />
              </div>
              <div>
                <label for="address" class="block text-sm font-medium mb-1">Company Address</label>
                <InputText id="address" v-model="Form.address" class="w-full p-2 border rounded" />
              </div>

             

              

              <div class="grid grid-cols-2 gap-4 mb-4">
               <!-- Display Existing Logo -->
              <div>
                <label class="block text-sm font-medium mb-1">Square Logo</label>
                <img v-if="company.company_logo_square" :src="'storage/'+company.company_logo_square" alt="Company Square Logo" class="w-32 h-32 object-cover" />
              </div>
                <div class="card flex justify-center">
                  <FileUpload 
                    mode="basic" 
                    name="company_logo_square" 
                    accept="image/*" 
                    :maxFileSize="1000000" 
                    :auto="false" 
                    chooseLabel="Upload Square Logo" 
                    @select="(event) => onFileSelect('company_logo_square', event)" 
                  />
                </div>
                <div>
                <label class="block text-sm font-medium mb-1">Rectangular Logo</label>
                <img v-if="company.company_logo_rectangular" :src="'storage/'+company.company_logo_rectangular" alt="Company Banner" class="w-100 h-32 object-cover" />
              </div>
                <div class="card flex justify-center">
                  <FileUpload 
                    mode="basic" 
                    name="company_logo_rectangular" 
                    accept="image/*" 
                    :maxFileSize="1000000" 
                    :auto="false" 
                    chooseLabel="Upload Rectangular Logo" 
                    @select="(event) => onFileSelect('company_logo_rectangular', event)" 
                  />
                </div>
              </div>
            </div>

            <Button type="submit" class="btn btn-primary w-full">Update Company Details</Button>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import NavLink from '@/Components/NavLink.vue';
import FileUpload from 'primevue/fileupload';
import axios from 'axios';

// Form data setup
const Form = useForm({
    title: '',
    mobile: '',
    email: '',
    address: '',
    company_logo_square: null,
    company_logo_rectangular: null,
});

// Store company details
const company = ref({});

// Fetch company details
const fetchCompanyDetails = async () => {
    try {
        const response = await axios.get('/api/details');
        company.value = response.data;
        Form.title = response.data.company_name;
        Form.mobile = response.data.company_mobile;
        Form.email = response.data.company_email;
        Form.address = response.data.company_address;
        Form.company_logo_square = response.data.company_logo_square; // Existing square logo if needed
        Form.company_logo_rectangular = response.data.company_logo_rectangular; // Existing rectangular logo if needed
    } catch (error) {
        console.error('Error fetching company details:', error);
    }
};

// Lifecycle hook to fetch company details on component mount
onMounted(() => {
    fetchCompanyDetails();
});

// Submit form data
const submitForm = () => {
    const formData = new FormData();
    formData.append('title', Form.title);
    formData.append('mobile', Form.mobile);
    formData.append('email', Form.email);
    formData.append('address', Form.address);

    if (Form.company_logo_square) {
        formData.append('company_logo_square', Form.company_logo_square);
    }

    if (Form.company_logo_rectangular) {
        formData.append('company_logo_rectangular', Form.company_logo_rectangular);
    }

    Form.post('/api/details/update', {
        data: formData,
        forceFormData: true,
        onSuccess: () => {
            // Optional: Redirect or show success message
        },
        preserveScroll: true,
        onError: () => {
            // Handle errors if needed
        }
    });
};

// Handle file selection for uploads
const onFileSelect = (type, event) => {
    Form[type] = event.files[0]; // Store the first file selected
};
</script>
