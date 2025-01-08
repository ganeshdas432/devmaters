<template>

    <Head title="Add Product" />
    <Toast />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Product</h2>
                <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center"
                    :href="route('product.list')">
                    <i class="pi pi-arrow-left mr-2"></i>
                    Back to Products
                </a>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitForm" class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                                Product Name <span class="text-red-500">*</span>
                                            </label>
                                            <InputText id="name" v-model="form.product_name" class="w-full"
                                                :class="{ 'p-invalid': errors.product_name }" />
                                            <small class="text-red-500" v-if="errors.product_name">{{
                                                errors.product_name
                                                }}</small>
                                        </div>

                                        <div>
                                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                                Category <span class="text-red-500">*</span>
                                            </label>
                                            <Dropdown id="category" v-model="form.cat_id" :options="categories"
                                                optionLabel="name" optionValue="code" placeholder="Select a category"
                                                class="w-full" :class="{ 'p-invalid': errors.cat_id }" />
                                            <small class="text-red-500" v-if="errors.cat_id">{{ errors.cat_id
                                                }}</small>
                                        </div>

                                        <div>
                                            <label for="shop" class="block text-sm font-medium text-gray-700 mb-1">
                                                Shop <span class="text-red-500">*</span>
                                            </label>
                                            <Dropdown id="shop" v-model="form.shop_id" :options="shops"
                                                optionLabel="name" optionValue="id" placeholder="Select a shop"
                                                class="w-full" :class="{ 'p-invalid': errors.shop_id }" />
                                            <small class="text-red-500" v-if="errors.shop_id">{{ errors.shop_id
                                                }}</small>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <!-- Remove price and stock fields -->
                                        </div>

                                        <div>
                                            <label for="time" class="block text-sm font-medium text-gray-700 mb-1">
                                                Time <span class="text-red-500">*</span>
                                            </label>
                                            <InputNumber id="time" v-model="form.time" class="w-full"
                                                :class="{ 'p-invalid': errors.time }" />
                                            <small class="text-red-500" v-if="errors.time">{{ errors.time }}</small>
                                        </div>

                                        <div>
                                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                                Type <span class="text-red-500">*</span>
                                            </label>
                                            <InputText id="type" v-model="form.type" class="w-full"
                                                :class="{ 'p-invalid': errors.type }" />
                                            <small class="text-red-500" v-if="errors.type">{{ errors.type }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                                    <Editor v-model="form.description" editorStyle="height: 250px"
                                        :class="{ 'p-invalid': errors.description }" />
                                    <small class="text-red-500" v-if="errors.description">{{ errors.description
                                        }}</small>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Media</h3>
                                    <div class="space-y-4">
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                            <div class="text-center" v-if="!imagePreview">
                                                <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000"
                                                    @select="onImageSelect" chooseLabel="Choose Image"
                                                    class="p-button-outlined" />
                                                <p class="text-sm text-gray-500 mt-2">
                                                    PNG, JPG up to 1MB
                                                </p>
                                            </div>
                                            <div v-else class="relative">
                                                <img :src="imagePreview" class="w-full h-48 object-cover rounded-lg" />
                                                <Button icon="pi pi-times" class="absolute -top-2 -right-2" rounded
                                                    severity="danger" @click="removeImage" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Status
                                            </label>
                                            <div class="flex gap-4">
                                                <RadioButton v-model="form.status" value="active"
                                                    inputId="status_active" />
                                                <label for="status_active">Active</label>

                                                <RadioButton v-model="form.status" value="inactive"
                                                    inputId="status_inactive" />
                                                <label for="status_inactive">Inactive</label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Options
                                            </label>
                                            <div class="space-y-2">
                                                <div class="flex items-center">
                                                    <Checkbox v-model="form.featured" inputId="featured"
                                                        :binary="true" />
                                                    <label for="featured" class="ml-2">Featured Product</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <Checkbox v-model="form.allowReviews" inputId="allowReviews"
                                                        :binary="true" />
                                                    <label for="allowReviews" class="ml-2">Allow Reviews</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attributes</h3>
                            <div v-for="(attribute, index) in form.attributes" :key="index"
                                class="attribute-row space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <InputText v-model="attribute.title" class="w-full" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                        <InputText v-model="attribute.value" class="w-full" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                        <InputNumber v-model="attribute.price" class="w-full" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">MRP</label>
                                        <InputNumber v-model="attribute.mrp" class="w-full" required />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                        <Dropdown v-model="attribute.unit" :options="units" optionLabel="title"
                                            optionValue="id" class="w-full" required />
                                    </div>
                                    <div class="flex justify-end">
                                        <Button type="button" icon="pi pi-times" class="p-button-danger"
                                            @click="removeAttribute(index)" />
                                    </div>
                                </div>
                            </div>
                            <Button type="button" label="Add Attribute" icon="pi pi-plus" class="p-button-success"
                                @click="addAttribute" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
                            <Button type="button" label="Cancel" severity="secondary" outlined
                                @click="$inertia.visit(route('product.list'))" />
                            <Button type="submit" label="Save Product" icon="pi pi-check" :loading="submitting" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from "primevue/usetoast";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import Editor from 'primevue/editor';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import RadioButton from 'primevue/radiobutton';
import Checkbox from 'primevue/checkbox';

const toast = useToast();
const submitting = ref(false);
const imagePreview = ref(null);
const errors = ref({});
const categories = ref([]);
const units = ref([]);
const shops = ref([]);

const form = reactive({
    product_name: '',
    cat_id: null,
    // Remove price and stock fields
    description: '',
    image: null,
    status: 'active',
    featured: false,
    allowReviews: true,
    attributes: [],
    shop_id: null,
    time: null, // Add time field
    type: '' // Add type field
});

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/catlist');
        categories.value = response.data.categories.map(category => ({
            name: category.title,
            code: category.id
        }));
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load categories',
            life: 3000
        });
    }
};

const fetchUnits = async () => {
    try {
        const response = await axios.get('/api/unitlist');
        units.value = response.data.units;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load units',
            life: 3000
        });
    }
};

const fetchShops = async () => {
    try {
        const response = await axios.get('/api/shoplist');
        shops.value = response.data.shops.map(shop => ({
            name: shop.shop_name,
            id: shop.id
        }));
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load shops',
            life: 3000
        });
    }
};

onMounted(() => {
    fetchCategories();
    fetchUnits();
    fetchShops();
});

const addAttribute = () => {
    form.attributes.push({ title: '', value: '', price: '', mrp: '', unit: '' });
};

const removeAttribute = (index) => {
    form.attributes.splice(index, 1);
};

const onImageSelect = (event) => {
    const file = event.files[0];
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
};

const submitForm = async () => {
    submitting.value = true;
    errors.value = {};

    try {
        const formData = new FormData();
        Object.keys(form).forEach(key => {
            if (key === 'attributes') {
                form[key].forEach((attribute, index) => {
                    Object.keys(attribute).forEach(attrKey => {
                        formData.append(`attributes[${index}][${attrKey}]`, attribute[attrKey]);
                    });
                });
            } else {
                formData.append(key, form[key]);
            }
        });

        const response = await axios.post('/api/products', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Product added successfully',
            life: 3000
        });

        // Redirect to product list
        window.location = route('product.list');
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        }
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to add product',
            life: 3000
        });
    } finally {
        submitting.value = false;
    }
};
</script>

<style scoped>
:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-inputnumber-input) {
    @apply w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500;
}

:deep(.p-editor-container) {
    @apply border border-gray-300 rounded-md overflow-hidden;
}

:deep(.p-editor-toolbar) {
    @apply border-b border-gray-300 bg-gray-50;
}

:deep(.p-editor-content) {
    @apply min-h-[200px];
}

:deep(.p-checkbox),
:deep(.p-radiobutton) {
    @apply cursor-pointer;
}

:deep(.p-fileupload-choose) {
    @apply w-full;
}

:deep(.p-button) {
    @apply transition-all duration-200;
}

:deep(.p-button:not(:disabled):hover) {
    @apply transform scale-105;
}
</style>