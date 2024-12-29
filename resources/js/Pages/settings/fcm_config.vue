<template>
    <div class="p-grid p-align-center p-justify-center p-mt-5">
      <div class="p-col-12 p-md-8">
        <div class="card">
          <h2 class="p-text-center">FCM Configuration</h2>
  
          <!-- Success Message -->
          <div v-if="successMessage" class="p-mb-4">
            <div class="p-alert p-alert-success">{{ successMessage }}</div>
          </div>
  
          <!-- Form -->
          <form @submit.prevent="saveConfig">
            <div class="p-field">
              <label for="api_key">API Key</label>
              <InputText v-model="config.api_key" id="api_key" required />
            </div>
  
            <div class="p-field">
              <label for="project_id">Project ID</label>
              <InputText v-model="config.project_id" id="project_id" required />
            </div>
  
            <div class="p-field">
              <label for="auth_domain">Auth Domain</label>
              <InputText v-model="config.auth_domain" id="auth_domain" required />
            </div>
  
            <div class="p-field">
              <label for="storage_bucket">Storage Bucket</label>
              <InputText v-model="config.storage_bucket" id="storage_bucket" required />
            </div>
  
            <div class="p-field">
              <label for="messaging_sender_id">Messaging Sender ID</label>
              <InputText v-model="config.messaging_sender_id" id="messaging_sender_id" required />
            </div>
  
            <div class="p-field">
              <label for="app_id">App ID</label>
              <InputText v-model="config.app_id" id="app_id" required />
            </div>
  
            <div class="p-field">
              <label for="measurement_id">Measurement ID</label>
              <InputText v-model="config.measurement_id" id="measurement_id" required />
            </div>
  
            <Button label="Save Configuration" icon="pi pi-save" type="submit" class="p-mt-3 p-button-success" />
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/inertia-vue3';
  import InputText from 'primevue/inputtext';
  import Button from 'primevue/button';
  
  export default {
    props: {
      config: Object,
      success: String,
    },
    components: {
      InputText,
      Button,
    },
    setup(props) {
      const successMessage = ref(props.success);
      const config = ref({ ...props.config });
  
      // Submit the form to store configuration
      const saveConfig = () => {
        Inertia.post('/fcm-config', config.value);
      };
  
      return { config, successMessage, saveConfig };
    },
  };
  </script>
  
  <style scoped>
  .card {
    padding: 2rem;
  }
  .p-text-center {
    text-align: center;
  }
  </style>
  