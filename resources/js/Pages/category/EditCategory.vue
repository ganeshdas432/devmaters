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
                <label for="title" class="block text-sm font-medium mb-1">Title</label>
                <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="shop_type" class="block text-sm font-medium mb-1">Shop Type</label>
                <Select
                  id="shop_type"
                  v-model="Form.shop_type"
                  :options="shopTypes"
                  optionLabel="name"
                  optionValue="code" 
                  placeholder="Select Shop Type"
                  class="w-full p-2 border rounded"
                />
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
const props = defineProps(['category']);

// Available shop types for the Select dropdown
const shopTypes = ref([
  { name: 'Food Shop', code: 'FS' },
  { name: 'Grocery Shop', code: 'GS' }
]);

// Initialize the form with default values
const Form = useForm({
  title: props.category?.title || '',
  shop_type: props.category?.shop_type || '',
  image: null,
});

const errors = ref({});  // Initialize an empty errors object



// Handle form submission
const submitForm = () => {

  const formData = new FormData();
  formData.append('title', Form.title);
  formData.append('shop_type', Form.shop_type);
  if (shopForm.image) {
    formData.append('image', Form.image);
  }


  Form.post(route('category.update', { id: props.category.id }), {
    data: formData,  
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
