<template>
  <Head title="Edit Shop" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Shop</h2>
    </template>

    <div class="py-12">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="card flex justify-center p-4">
          <form @submit.prevent="submitForm" class="w-full max-w-lg">
            <!-- First Row: Shop Name and File Upload -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="shop_name" class="block text-sm font-medium mb-1">Shop Name</label>
                <InputText id="shop_name" v-model="shopForm.shop_name" class="w-full p-2 border rounded" />
              </div>

              <div class="card flex justify-center">
                <FileUpload 
                      mode="basic" 
                      name="demo[]" 
                      accept="image/*" 
                      :maxFileSize="1000000" 
                      :auto="false" 
                      chooseLabel="Browse" 
                      @select="onFileSelect" 
                  />
              </div>
            </div>

            <!-- Second Row: Vendor ID and Address -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="vendor_id" class="block text-sm font-medium mb-1">Vendor ID</label>
                <InputText id="vendor_id" v-model="shopForm.vendor_id" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="address" class="block text-sm font-medium mb-1">Address</label>
                <InputText id="address" v-model="shopForm.address" class="w-full p-2 border rounded" />
              </div>
            </div>

            <!-- Third Row: Mobile and Shop Type -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="mobile" class="block text-sm font-medium mb-1">Mobile</label>
                <InputText id="mobile" v-model="shopForm.mobile" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="shop_type" class="block text-sm font-medium mb-1">Shop Type</label>
                <Select
                  id="shop_type"
                  v-model="shopForm.shop_type"
                  :options="shopTypes"
                  optionLabel="name"
                  optionValue="code" 
                  placeholder="Select Shop Type"
                  class="w-full p-2 border rounded"
                />
              </div>
            </div>

            <!-- Fourth Row: Latitude and Longitude -->
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="latitude" class="block text-sm font-medium mb-1">Latitude</label>
                <InputText id="latitude" v-model="shopForm.latitude" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="longitude" class="block text-sm font-medium mb-1">Longitude</label>
                <InputText id="longitude" v-model="shopForm.longitude" class="w-full p-2 border rounded" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="shop_type" class="block text-sm font-medium mb-1">Shop Type</label>
                <Select
                  id="status"
                  v-model="shopForm.status"
                  :options="statusTypes"
                  optionLabel="name"
                  optionValue="code" 
                  placeholder="Select Status"
                  class="w-full p-2 border rounded"
                />
              </div>
            </div>

           

            <!-- Submit Button -->
            <Button type="submit" class="btn btn-primary w-full">Update Shop</Button>
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
import Select from 'primevue/select';
import FileUpload from 'primevue/fileupload';

// Define props to receive 'shop' from the backend
const props = defineProps(['shop']);

// Available shop types for the Select dropdown
const shopTypes = ref([
  { name: 'Food Shop', code: 'FS' },
  { name: 'Grocery Shop', code: 'GS' }
]);
const statusTypes = ref([
  { name: 'Active', code: 'active' },
  { name: 'Inactive', code: 'inactive' }
]);

// Initialize the form with default values
const shopForm = useForm({
  shop_name: props.shop?.shop_name || '',
  image: null,  // This will be handled separately
  vendor_id: props.shop?.vendor_id || '',
  address: props.shop?.address || '',
  mobile: props.shop?.mobile || '',
  latitude: props.shop?.latitude || '',
  longitude: props.shop?.longitude || '',
  shop_type: props.shop?.shop_type || '',
  status:props.shop?.status||''
});

const errors = ref({});  // Initialize an empty errors object


const onFileSelect = (event) => {
      shopForm.image = event.files[0]; // Store the selected file
  };
// Handle form submission
const submitForm = () => {

  const formData = new FormData();
  formData.append('shop_name', shopForm.shop_name);
  formData.append('vendor_id', shopForm.vendor_id);
  formData.append('address', shopForm.address);
  formData.append('mobile', shopForm.mobile);
  formData.append('latitude', shopForm.latitude);
  formData.append('longitude', shopForm.longitude);
  formData.append('shop_type', shopForm.shop_type);
  formData.append('status',shopForm.status);

  if (shopForm.image) {
    formData.append('image', shopForm.image);
  }


  shopForm.post(route('shop.update', { id: props.shop.id }), {
    data: formData,  
    forceFormData: true,
    onSuccess: () => {
      console.log("updated");
    },
    onError: (serverErrors) => {
      errors.value = serverErrors;  // Capture errors from the backend
      console.log(serverErrors);  // Log the errors to the console for debugging
    },
  });
};

</script>
