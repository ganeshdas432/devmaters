<template>
  <Toast />

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product</h2>
    </template>

    <div class="py-12">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-semibold mr-4">Add New Product</h4>
        </div>
        <div class="card flex justify-center p-4">
          <form @submit.prevent="submitForm" class="w-full">
            <div class="grid grid-cols-3 gap-4 mb-4">
              <div>
                <label for="shop_id" class="block text-sm font-medium mb-1">Shop</label>
                <Select id="shop_id" v-model="productForm.shop_id" :options="shops" optionLabel="shop_name"
                  optionValue="id" placeholder="Select Shop" class="w-full  border rounded" />
              </div>

              <div>
                <label for="cat_id" class="block text-sm font-medium mb-1">Categories</label>
                <Select id="cat_id" v-model="productForm.cat_id" :options="cats" optionLabel="title"
                  optionValue="id" placeholder="Select Category" class="w-full  border rounded" />
              </div>

              <div class="card flex justify-center">
                <FileUpload mode="basic" name="product_image[]" accept="image/*" :maxFileSize="1000000" :auto="false"
                  chooseLabel="Browse" @select="onFileSelect" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="product_name" class="block text-sm font-medium mb-1">Product Name</label>
                <InputText id="product_name" v-model="productForm.product_name" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="description" class="block text-sm font-medium mb-1">Description</label>
                <InputText id="description" v-model="productForm.description" class="w-full p-2 border rounded" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label for="time" class="block text-sm font-medium mb-1">Preparation Time(Min.)</label>
                <InputText id="time" v-model="productForm.time" type="number" class="w-full p-2 border rounded" />
              </div>

              <div>
                <label for="type" class="block text-sm font-medium mb-1">Type</label>
                <InputText id="type" v-model="productForm.type" type="number" class="w-full p-2 border rounded" />
              </div>
            </div>

            <div v-for="(attribute, index) in productForm.attributes" :key="index" class="grid grid-cols-6 gap-4 mb-4">
              <div>
                <label :for="'attribute_title_' + index" class="block text-sm font-medium mb-1">Attribute Title</label>
                <InputText :id="'attribute_title_' + index" v-model="attribute.title"
                  class="w-full p-2 border rounded" />
              </div>

              <div>
                <label :for="'attribute_value_' + index" class="block text-sm font-medium mb-1">Attribute Value</label>
                <InputText :id="'attribute_value_' + index" v-model="attribute.value"
                  class="w-full p-2 border rounded" />
              </div>

              <div>
                <label :for="'price_' + index" class="block text-sm font-medium mb-1">Price</label>
                <InputText :id="'price_' + index" v-model="attribute.price" type="number"
                  class="w-full p-2 border rounded" />
              </div>

              <div>
                <label :for="'mrp_' + index" class="block text-sm font-medium mb-1">MRP</label>
                <InputText :id="'mrp_' + index" v-model="attribute.mrp" type="number"
                  class="w-full p-2 border rounded" />
              </div>

              <div>
                <label :for="'unit_' + index" class="block text-sm font-medium mb-1">Unit</label>
                <Select :id="'unit_' + index" v-model="attribute.unit" :options="units" optionLabel="title"
                  optionValue="id" placeholder="Select Unit" class="w-full  border rounded" />
              </div>
              <Button type="button" icon="pi pi-times" text size="small" @click="removeAttribute(index)" />

            </div>

            <Button type="button" @click="addAttribute" class="btn btn-secondary mb-4">Add Attribute</Button>
            <Button type="submit" class="btn btn-primary w-full">Add Product</Button>
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
import { useToast } from 'primevue/usetoast';

const toast = useToast();

// Initialize the product form data using useForm
const productForm = useForm({
  shop_id: '',
  cat_id: '',
  product_name: '',
  description: '',
  time: '',
  type: '',
  image: null, // This will hold the file data
  attributes: [{ title: '', value: '', price: '', mrp: '', unit: '' }] // Initialize with one attribute row
});

const shops = ref([]); // Shops fetched from API
const units = ref([]); // Units fetched from API
const cats = ref([]); // Units fetched from API


// Fetch shops and units from the API
const fetchShopsAndUnits = async () => {
  try {
    const shopResponse = await fetch('/api/shoplist'); // Replace with your API endpoint
    const unitResponse = await fetch('/api/unitlist'); // Replace with your API endpoint
    const catResponse = await fetch('/api/catlist'); // Replace with your API endpoint


    if (!shopResponse.ok) throw new Error('Failed to fetch shops');
    if (!unitResponse.ok) throw new Error('Failed to fetch units');
    if (!catResponse.ok) throw new Error('Failed to fetch categories');


    const shopData = await shopResponse.json();
    const unitData = await unitResponse.json();
    const catData = await catResponse.json();



    shops.value = shopData.shops || []; // Ensure you have shops data
    units.value = unitData.units || []; // Ensure you have units data
    cats.value = catData.categories || []; // Ensure you have units data


    console.log('Fetched units:', units.value); // Debugging output
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to fetch data: ' + error.message, life: 3000 });
  }
};

// Call the function on component setup
fetchShopsAndUnits();

// Handle file selection
const onFileSelect = (event) => {
  productForm.image = event.files[0]; // Store the selected file
};

// Add new attribute row
const addAttribute = () => {
  productForm.attributes.push({ title: '', value: '', price: '', mrp: '', unit: '' });
};

// Remove attribute row
const removeAttribute = (index) => {
  productForm.attributes.splice(index, 1);
};

// Handle form submission
const submitForm = () => {
  const formData = new FormData();

  // Append other form data
  formData.append('shop_id', productForm.shop_id);
  formData.append('cat_id', productForm.cat_id);

  formData.append('product_name', productForm.product_name);
  formData.append('description', productForm.description);
  formData.append('time', productForm.time);
  formData.append('type', productForm.type);

  // Append the image file if selected
  if (productForm.image) {
    formData.append('image', productForm.image);
  }

  // Append attributes
  productForm.attributes.forEach((attribute, index) => {
    formData.append(`attributes[${index}][title]`, attribute.title);
    formData.append(`attributes[${index}][value]`, attribute.value);
    formData.append(`attributes[${index}][price]`, attribute.price);
    formData.append(`attributes[${index}][mrp]`, attribute.mrp);
    formData.append(`attributes[${index}][unit]`, attribute.unit);


  });

  for (let pair of formData.entries()) {
    console.log(pair[0]+ ', '+ pair[1]); 
  }

  // Submit the form using Inertia
  productForm.post(route('product.store'), {
    data: formData,
    forceFormData: true,
    onSuccess: ({ props }) => {
      toast.add({ severity: 'success', summary: 'Product Added', detail: 'Product Added Successfully', life: 3000 });
      productForm.reset();
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
  margin-bottom: 0.25rem;
  /* Adds space between label and input */
}

input {
  margin-bottom: 1rem;
  /* Adds space between input fields */
}

.btn-secondary {
  margin-right: 0.5rem;
}
</style>
