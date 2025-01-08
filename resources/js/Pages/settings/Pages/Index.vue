<template>

  <Head title="Dashboard" />

  <Head title="Pages" />


</template>

    <div class="py-4">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
          <h4 class="text-lg font-semibold mr-4">Settings</h4>
          
          <Button label="Add New Page" icon="pi pi-plus" @click="openNew" />
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

        <DataTable :value="pages" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem" class="mt-4">
          <Column field="id" header="ID" style="width: 10%"></Column>
          <Column field="title" header="Title" style="width: 20%"></Column>
          <Column header="Content" style="width: 50%">
            <template #body="slotProps">
              <span>{{ truncateContent(slotProps.data.content, 50) }}</span>
          </Column>
          <Column field="status" header="Status" sortable>
          <Column header="Actions" style="width: 20%">
          <template #body="slotProps">
            <Button label="Edit" @click="openEditDialog(slotProps.data)" class="p-button-sm p-button-warning" />
          </template>
        </Column>
            <div class="field">
              <label for="title" class="font-semibold">Title</label>
      </div>
    </div>

    <Dialog v-model:visible="visible" header="Create New Page" :style="{ width: '50vw' }">
  <form @submit.prevent="submitForm">
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
      <input
        v-model="form.title"
        type="text"
        id="title"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        required
      />
    </div>
    <div class="mt-4">
      <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
      <textarea
        v-model="form.content"
        id="content"
        rows="6" 
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        required
      ></textarea>
    </div>
            <span>Are you sure you want to delete this page?</span>
    <button type="submit" class="btn btn-primary mt-4">Create Page</button>
  </form>
</Dialog>


<!-- Edit Dialog -->
<Dialog v-model:visible="editDialogVisible" header="Edit Page" :style="{ width: '50vw' }">
  <form @submit.prevent="submitEditForm">
    <div>
      <label for="editTitle" class="block text-sm font-medium text-gray-700">Title (Non Editable)</label>
      <input
        v-model="editForm.title"
        type="text"
        id="editTitle"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        required
        disabled
      />
    </div>
    <div class="mt-4">
      <label for="editContent" class="block text-sm font-medium text-gray-700">Content</label>
      <textarea
        v-model="editForm.content"
        id="editContent"
        rows="6"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
        required
      ></textarea>
    </div>
import { ref, onMounted } from 'vue';
    <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
  </form>
</Dialog>
import Dropdown from 'primevue/dropdown';
<Dialog v-model:visible="deleteDialogVisible" header="Confirm Delete" :style="{ width: '30vw' }">
  <p>Are you sure you want to delete this page?</p>
  <div class="flex justify-end">
    <Button label="Cancel" class="p-button-text" @click="deleteDialogVisible = false" />
    <Button label="Delete" class="p-button-danger" @click="deletePage" />
  </div>
</Dialog>


  try {
    const response = await axios.get('/api/pages');
    pages.value = response.data.pages;
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load pages', life: 3000 });
import { useForm } from '@inertiajs/vue3';
    loading.value = false;
import { Head } from '@inertiajs/vue3';
});
const openNew = () => {
import Column from 'primevue/column';
import NavLink from '@/Components/NavLink.vue';
  page.value = {
  submitted.value = false;
  editMode.value = false;
const pages = ref([]); // Reactive array to hold pages
const visible = ref(false); // For dialog visibility
const editDialogVisible = ref(false); // Visibility for the edit dialog
const editForm = ref({ id: null, title: '', content: '' }); // Data for the edit form
const deleteDialogVisible = ref(false); // To control the delete confirmation dialog
const pageIdToDelete = ref(null); // ID of the page to delete


// Fetch existing pages
const fetchPages = async () => {
  submitted.value = true;
    const response = await axios.get('/api/pages'); // API endpoint for fetching pages
    pages.value = response.data.pages || []; // Populate pages
    return;
    console.error('Error fetching pages data:', error);
    if (editMode.value) {
    toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message || 'Operation failed', life: 3000 });
  }
const truncateContent = (text, wordLimit) => {
  if (!text) return '';
  const words = text.split(' ');
  return words.length > wordLimit ? words.slice(0, wordLimit).join(' ') + '...' : text;
  deleteDialog.value = true;
};
const openEditDialog = (page) => {
  editForm.value = { ...page }; // Populate the edit form with the selected page's data
  editDialogVisible.value = true; // Show the dialog
    await axios.delete(`/api/pages/${page.value.id}`);
    pages.value = pages.value.filter(p => p.id !== page.value.id);
const confirmDelete = (id) => {
  pageIdToDelete.value = id; // Set the ID of the page to delete
  deleteDialogVisible.value = true; // Show the confirmation dialog
};

const submitEditForm = async () => {
const getStatusSeverity = (status) => {
    const response = await axios.put(`/api/pages/${editForm.value.id}`, editForm.value); // Send updated data to the server
    fetchPages(); // Refresh the pages list
    editDialogVisible.value = false; // Close the dialog
  padding: 0.5rem 1rem;
    console.error('Error updating page:', error);

.p-datatable.p-datatable-sm .p-datatable-tbody>tr>td {
  padding: 0.5rem 1rem;
</style>
    await axios.delete(`/api/pages/${pageIdToDelete.value}`); // Make DELETE API call
    fetchPages(); // Refresh the pages list
    deleteDialogVisible.value = false; // Close the dialog
    pageIdToDelete.value = null; // Reset the page ID  });