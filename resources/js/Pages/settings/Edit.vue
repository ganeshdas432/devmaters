<template>
    <Head title="Edit Charge" />
  
    <AuthenticatedLayout>
      <!-- Header Section -->
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Edit Charge Details
        </h2>
      </template>
  
      <!-- Content Section -->
      <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        
          <!-- Form Section -->
          <div class="card p-6 mt-6 bg-white shadow-sm rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
              Edit Charges
            </h3>
            <Form :charge="charge" />
          </div>
        </div>
      </div>

    
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import Form from '@/Pages/settings/charges_form.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import NavLink from '@/Components/NavLink.vue';
  import Dialog from 'primevue/dialog';

  
  defineProps({
    charge: Object,
  });
  </script>
  