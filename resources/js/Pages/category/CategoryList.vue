<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
        </template>

        <div class="py-4">
            <div class="grid grid-cols-4 gap-4">
                <div class="card bg-white p-6 mb-8 col-span-1">
                    <form @submit.prevent="submitForm" class="w-full">
                        <div class="card bg-white p-4">
                            <div class="flex flex-col gap-4 mb-4">
                                <div class="min-w-[100px]">
                                    <label for="title" class="block text-sm font-medium">Category Name</label>
                                    <InputText id="title" v-model="Form.title" class="w-full p-2 border rounded" />
                                </div>
                                <div class="min-w-[100px]">
                                    <label for="shop_type" class="block text-sm font-medium">Shop Type</label>
                                    <Select id="shop_type" v-model="Form.shop_type" :options="shoptype"
                                        optionLabel="name" optionValue="code" placeholder="Select Shop Type"
                                        class="w-full border rounded" />
                                </div>
                                <div class="min-w-[100px]">
                                    <label class="block text-sm font-medium">Image</label>
                                    <FileUpload mode="basic" name="demo[]" accept="image/*" :maxFileSize="1000000"
                                        :auto="false" chooseLabel="Browse" @select="onFileSelect" class="w-full" />
                                </div>
                                <div class="min-w-[100px] flex items-end">
                                    <Button type="submit" class="btn btn-primary w-full">Submit</Button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card bg-white p-6 mb-8 col-span-3">
                    <DataTable :value="categories" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem">
                        <Column field="id" header="ID" style="width: 25%"></Column>
                        <Column header="Image">
                            <template #body="slotProps">
                                <Image :src="`/storage/${slotProps.data.image}`" alt="Image" width="100" />
                            </template>
                        </Column>
                        <Column field="title" header="Title" style="width: 25%"></Column>
                        <Column field="shop_type" header="Shop Type" style="width: 25%"></Column>
                        <Column field="description" header="Description" style="width: 25%"></Column>

                        <Column header="Actions" style="width: 4rem">
                            <template #body="slotProps">
                                <Button type="button" icon="pi pi-pencil" text size="small"
                                    @click="handleEdit(slotProps.data)" />

                                <Button type="button" icon="pi pi-trash" text size="small"
                                    @click="handleDelete(slotProps.data)" />
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
            <Toast ref="toast" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import axios from 'axios';
import Image from 'primevue/image';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import FileUpload from 'primevue/fileupload';
import { useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const categories = ref([]); // Reactive array to hold the categories
const toast = ref(null); // Initialize toast

// Fetch the list of categories on mount
onMounted(async () => {
    try {
        const response = await axios.get('/api/catlist');
        categories.value = response.data.categories; // Set the fetched categories
    } catch (error) {
        console.error('Error fetching categories data:', error);
    }
});

// Action handler for Edit click
function handleEdit(rowData) {
    Inertia.visit(`/category/edit/${rowData.id}`);
}

// Action handler for Delete click
function handleDelete(rowData) {
    if (confirm(`Are you sure you want to delete ${rowData.title}?`)) {
        axios
            .delete(`/api/category_delete/${rowData.id}`) // Send the delete request
            .then((response) => {
                console.log(response.data.message);
                // Remove the deleted category from the categories list
                categories.value = categories.value.filter((category) => category.id !== rowData.id);
                toast.value.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
            })
            .catch((error) => {
                console.error('There was an error deleting the category:', error);
                toast.value.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete category', life: 3000 });
            });
    }
}

// Initialize the shop form data using useForm
const Form = useForm({
    title: '',
    shop_type: '',
    image: null,
});

const onFileSelect = (event) => {
    Form.image = event.files[0]; // Store the selected file
};

const shoptype = ref([
    { name: 'Food Shop', code: 'food' },
    { name: 'Grocery Shop', code: 'grocery' },
]);

const submitForm = async () => {
    const formData = new FormData();

    // Append other form data
    formData.append('title', Form.title);
    formData.append('shop_type', Form.shop_type);
    if (Form.image) {
        formData.append('image', Form.image);
    }

    try {
        const response = await axios.post(route('category.store'), formData);
        Form.reset();
        Form.image = null; // Clear the upload field
        toast.value.add({ severity: 'success', summary: 'Success', detail: response.data.success, life: 3000 });
        // Refresh the list of categories
        const categoryResponse = await axios.get('/api/catlist');
        categories.value = categoryResponse.data.categories;
    } catch (error) {
        toast.value.add({ severity: 'error', summary: 'Error', detail: 'Failed to add category', life: 3000 });
    }
};
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
