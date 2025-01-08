<template>
  <Toast />

  <Head title="Edit User" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </template>

    <div class="py-4">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="card flex justify-center p-4">
          <form @submit.prevent="submitForm" class="w-full max-w-lg">
            <!-- Shop Type Dropdown -->
            <div class="mb-4">
              <label for="user_status" class="block text-sm font-medium mb-1">User Status</label>
              <Select id="user_status" v-model="Form.user_status" :options="statusOptions" optionLabel="label"
                optionValue="value" placeholder="Select User Status" class="w-full p-1 border rounded" />
              <p v-if="errors.user_status" class="text-red-500 text-sm">{{ errors.user_status }}</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-5">
              <Button type="submit" class="btn btn-primary">Update</Button>
            </div>
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
import Select from 'primevue/select';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";

// Toast instance
const toast = useToast();

// Props
const props = defineProps(['user']);


const statusOptions = [
  { label: 'Active', value: 1 },
  { label: 'Deactive', value: 2 }
];

// Form setup
const Form = useForm({
  user_status: props.user?.status ?? null, // Use integer or null directly
});

const errors = ref({});

// Form submission
const submitForm = () => {
  Form.put(route('appuser.update', { id: props.user.id }), {
    onSuccess: () => {
      toast.add({ severity: 'success', summary: 'Updated', detail: 'Updated Successfully', life: 3000 });
    },
    onError: (serverErrors) => {
      errors.value = serverErrors;
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update', life: 3000 });
    },
  });
};
</script>
