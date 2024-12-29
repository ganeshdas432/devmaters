<template>
    <Head title="Dashboard" />
  
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Category</h2>
      </template>
  
      <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
          <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold mr-4">Add New Category</h4>
          </div>
          <div class="card flex justify-center p-4">
            <form @submit.prevent="submitForm" class="w-full max-w-lg">
              <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                  <label for="title" class="block text-sm font-medium mb-1">Category Name</label>
                  <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
                </div>
                <div>
                  <label for="shop_type" class="block text-sm font-medium mb-1">Shop Type</label>
                  <Select
                      id="shop_type"
                      v-model="Form.shop_type"
                      :options="shoptype"
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
  
              <Button type="submit" class="btn btn-primary w-full">Add Category</Button>
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
      shop_type: '',
      image:null,
  });

  const onFileSelect = (event) => {
    Form.image = event.files[0]; // Store the selected file
};
  
  const shoptype = ref([
      { name: 'Food Shop', code: 'FS' },
      { name: 'Grocery Shop', code: 'GS' },
  ]);
  

  const submitForm = () => {
      const formData = new FormData();
  
      // Append other form data
      formData.append('title', Form.title);
      formData.append('shop_type', Form.shop_type);
      if (Form.image) {
      formData.append('image', Form.image);
    }
   
      Form.post(route('category.store'), {
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
  