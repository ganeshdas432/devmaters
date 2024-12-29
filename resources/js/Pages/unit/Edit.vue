<template>
  <Head title="Edit Unit" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Unit</h2>
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
                <label for="value" class="block text-sm font-medium mb-1">Value</label>
                <InputText id="value" v-model="Form.value" class="w-full p-2 border rounded" />
              </div>
              
            </div>

          
            <!-- Submit Button -->
            <Button type="submit" class="btn btn-primary w-full">Update</Button>
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
const props = defineProps(['unit']);



// Initialize the form with default values
const Form = useForm({
  title: props.unit?.title || '',
  value: props.unit?.value || '',
});

const errors = ref({});  // Initialize an empty errors object



// Handle form submission
const submitForm = () => {

  const formData = new FormData();
  formData.append('title', Form.title);
  formData.append('value', Form.value);



  Form.post(route('unit.update', { id: props.unit.id }), {
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
