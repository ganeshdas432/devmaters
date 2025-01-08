<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Shop</h2>
    </template>

    <div class="py-4">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-semibold mr-4">Settings</h4>
          <Button label="Upload" @click="visible = true" />
        </div>

        <div class="space-x-4 sm:-my-px sm:ms-4 sm:flex">
          <NavLink :href="route('settings.company_details')" :active="route().current('settings.company_details')">
            Details
          </NavLink>
          <NavLink :href="route('settings.sliders')" :active="route().current('settings.sliders')">
            Sliders
          </NavLink>
          <NavLink :href="route('settings.charges')" :active="route().current('settings.charges')">
            Charges
          </NavLink>
          <NavLink :href="route('settings.pages')" :active="route().current('settings.pages')">
            Pages
          </NavLink>
        </div>


        <DataTable :value="sliders" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
          tableStyle="min-width: 50rem" class="mt-4">
          <Column field="id" header="ID" style="width: 25%"></Column>

          <Column header="URL" style="width: 25%">
            <template #body="slotProps">
              <img class="block mx-auto rounded w-48" :src="'/storage/' + slotProps.data.url"
                :alt="slotProps.data.name" />
            </template>
          </Column>


          <Column field="shop_type" header="Shop Type" style="width: 25%"></Column>
          <Column header="Actions" style="width: 25%">
            <template #body="slotProps">

              <Button type="button" icon="pi pi-trash" text size="small" @click="handleDelete(slotProps.data)" />
            </template>
          </Column>
        </DataTable>

      </div>
    </div>
    <template>
      <div class="card flex justify-center">

        <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
          <form @submit.prevent="submitForm" class="w-full max-w-lg">
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <!-- <label for="shop_type" class="block text-sm font-medium mb-1">Shop Type</label> -->
                <Select id="shop_type" v-model="Form.shop_type" :options="shoptype" optionLabel="name"
                  optionValue="code" placeholder="Select Shop Type" class="w-full p-2 border rounded" />
              </div>

              <div class="card flex justify-center">

                <FileUpload mode="basic" :key="fileKey" name="demo[]" accept="image/*" :maxFileSize="1000000"
                  :auto="false" chooseLabel="Browse" @select="onFileSelect" />
              </div>
            </div>


            <Button type="submit" class="btn btn-primary w-full">Add Shop</Button>
          </form>
        </Dialog>
      </div>
    </template>
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
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import NavLink from '@/Components/NavLink.vue';
import Dialog from 'primevue/dialog';




const sliders = ref([]); // Reactive array to hold the categories
const visible = ref(false);

const fetchSliders = async () => {
  try {
    const response = await axios.get('/api/slider_images');
    sliders.value = response.data.sliders; // Update the sliders list
    console.log("Sliders list updated:", sliders.value);
  } catch (error) {
    console.error('Error fetching sliders data:', error);
  }
};

onMounted(fetchSliders);
const fileKey = ref(0);

// Initialize the shop form data using useForm
const Form = useForm({
  shop_type: '',
  image: null // This will hold the file data
});

const shoptype = ref([
  { name: 'Food Shop', code: 'FS' },
  { name: 'Grocery Shop', code: 'GS' },
]);

// Handle file selection
const onFileSelect = (event) => {
  Form.image = event.files[0]; // Store the selected file
};

// Handle form submission
const submitForm = () => {
  const formData = new FormData();


  formData.append('shop_type', Form.shop_type);

  // Append the image file if selected
  if (Form.image) {
    formData.append('image', Form.image);
  }

  // Submit the form using Inertia
  Form.post(route('slider.store'), {
    data: formData,
    forceFormData: true,
    onSuccess: () => {
      Form.reset();
      fileKey.value += 1;
      fetchSliders(); // Refresh the sliders list after successful submission

    },
    preserveScroll: true,
    onError: () => {
      // Handle the error if needed
    }
  });
};

function handleDelete(rowData) {
  if (confirm(`Are you sure you want to delete ${rowData.title}?`)) {
    axios
      .delete(`/api/slide_delete/${rowData.id}`) // Send the delete request
      .then((response) => {
        console.log(response.data.message);
        // Remove the deleted category from the categories list
        sliders.value = sliders.value.filter((slider) => slider.id !== rowData.id);
      })
      .catch((error) => {
        console.error('There was an error deleting the category:', error);
      });
  }
}
</script>



<style scoped>
label {
  margin-bottom: 0.25rem;
  /* Adds space between label and input */
}

input {
  margin-bottom: 1rem;
  /* Adds space between input fields */
}
</style>