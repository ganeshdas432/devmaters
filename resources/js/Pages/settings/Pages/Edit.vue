<template>
    <div>
      <h1 class="text-2xl mb-4">Edit Page</h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="title" class="block mb-2">Title</label>
          <InputText id="title" v-model="form.title" class="w-full" />
        </div>
        <div class="mb-4">
          <label for="content" class="block mb-2">Content</label>
          <InputTextarea id="content" v-model="form.content" class="w-full" rows="6" />
        </div>
        <Button label="Update" type="submit" class="p-button-primary" />
      </form>
    </div>
  </template>
  
  <script>
  import { useForm } from '@inertiajs/vue3';
  import InputText from 'primevue/inputtext';
  import InputTextarea from 'primevue/inputtextarea';
  import Button from 'primevue/button';
  
  export default {
    props: {
      page: Object,
    },
    components: { InputText, InputTextarea, Button },
    setup(props) {
      const form = useForm({ ...props.page });
  
      const submit = () => {
        form.put(route('pages.update', form.id));
      };
  
      return { form, submit };
    },
  };
  </script>
  