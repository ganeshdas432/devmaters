<template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Unit</h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
          <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold mr-4">Add New Unit</h4>

          </div>
          <div class="card flex justify-center p-4">
            <form @submit.prevent="submitForm" class="w-full max-w-lg">
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                  <label for="title" class="block text-sm font-medium mb-1">Unit Name</label>
                  <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
                </div>
                <div>
                  <label for="value" class="block text-sm font-medium mb-1">Unit Value</label>
                  <InputText id="value" v-model="Form.value" class="w-full p-2 border rounded" />
                </div>
            
              </div>
  
              <Button type="submit" class="btn btn-primary w-full">Add Unit</Button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import InputText from 'primevue/inputtext';
  import Button from 'primevue/button';
  import Select from 'primevue/select';
  import FileUpload from 'primevue/fileupload';
  
  // Initialize the shop form data using useForm
  const Form = useForm({
      title: '',
      value: '',
  });
  

  

  const submitForm = () => {
      const formData = new FormData();
  
      // Append other form data
      formData.append('title', Form.title);
      formData.append('value', Form.value);
      
  
   
      Form.post(route('unit.store'), {
          data: formData,
          onSuccess: () => {
              Form.reset();
          },
          preserveScroll: true,
          onError: () => {
              // Handle the error if needed
          }
      });
  };
  </script>
  
  
  
  <style scoped>
  label {
    margin-bottom: 0.25rem; /* Adds space between label and input */
  }
  
  input {
    margin-bottom: 1rem; /* Adds space between input fields */
  }
  </style>
  