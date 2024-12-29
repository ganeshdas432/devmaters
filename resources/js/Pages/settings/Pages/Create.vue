<template>
    <div>
      <h1 class="text-2xl mb-4">Create Page</h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="title" class="block mb-2">Title</label>
          <InputText id="title" v-model="form.title" class="w-full" />
        </div>
        <div class="mb-4">
          <label for="content" class="block mb-2">Content</label>
          <InputTextarea id="content" v-model="form.content" class="w-full" rows="6" />
        </div>
        <Button label="Save" type="submit" class="p-button-primary" />
      </form>
    </div>
  </template>
  
  <script>
  import { useForm } from '@inertiajs/vue3';
  import InputText from 'primevue/inputtext';
  import InputTextarea from 'primevue/inputtextarea';
  import Button from 'primevue/button';
  
  export default {
    components: { InputText, InputTextarea, Button },
    setup() {
      const form = useForm({ title: '', content: '' });
  
      const submit = () => {
        form.post(route('pages.store'));
      };
  
      return { form, submit };
    },
  };
  </script>
  