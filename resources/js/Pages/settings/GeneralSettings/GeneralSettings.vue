<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-4">General Settings</h3>
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
                        <img v-if="company.company_logo_square" :src="'storage/' + company.company_logo_square" alt="Company Square Logo" class="w-32 h-32 object-cover" />
                    </div>
                    <div class="card flex justify-center">
                        <FileUpload mode="basic" name="company_logo_square" accept="image/*" :maxFileSize="1000000" :auto="false" chooseLabel="Upload Square Logo" @select="(event) => onFileSelect('company_logo_square', event)" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Rectangular Logo</label>
                        <img v-if="company.company_logo_rectangular" :src="'storage/' + company.company_logo_rectangular" alt="Company Banner" class="w-100 h-32 object-cover" />
                    </div>
                    <div class="card flex justify-center">
                        <FileUpload mode="basic" name="company_logo_rectangular" accept="image/*" :maxFileSize="1000000" :auto="false" chooseLabel="Upload Rectangular Logo" @select="(event) => onFileSelect('company_logo_rectangular', event)" />
                    </div>
                </div>
            </div>

            <Button type="submit" class="btn btn-primary w-full">Update Company Details</Button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import { useToast } from "primevue/usetoast";

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
        Form.company_logo_square = response.data.company_logo_square;
        Form.company_logo_rectangular = response.data.company_logo_rectangular;
    } catch (error) {
        console.error('Error fetching company details:', error);
    }
};

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
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Company details updated successfully',
                life: 3000
            });
            fetchCompanyDetails(); // Refresh the data
        },
        onError: (errors) => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: Object.values(errors).join('\n'),
                life: 3000
            });
        },
        preserveScroll: true
    });
};

// Handle file selection for uploads
const onFileSelect = (type, event) => {
    Form[type] = event.files[0];
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchCompanyDetails();
});
</script>
