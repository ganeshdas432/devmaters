<template>

    <Head title="Product Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product Management</h2>
                <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center"
                    :href="route('product.add')">
                    <i class="pi pi-plus mr-2"></i>
                    Add Product
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Products</p>
                                <h3 class="text-2xl font-semibold text-gray-800">{{ totalProducts }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="pi pi-box text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Active Products</p>
                                <h3 class="text-2xl font-semibold text-gray-800">{{ activeProducts }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="pi pi-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Out of Stock</p>
                                <h3 class="text-2xl font-semibold text-gray-800">{{ outOfStockProducts }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="pi pi-exclamation-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Low Stock</p>
                                <h3 class="text-2xl font-semibold text-gray-800">{{ lowStockProducts }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="pi pi-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <!-- Search and Filter Section -->
                    <div
                        class="mb-4 flex flex-wrap gap-4 justify-between items-center p-4 border-b bg-gray-50 rounded-t-lg">
                        <div class="flex gap-4 flex-grow">
                            <span class="p-input-icon-left w-full md:w-96">
                                <i class="pi pi-search" />
                                <InputText v-model="filters.global.value" placeholder="Search products..."
                                    class="w-full" />
                            </span>
                            <Dropdown v-model="selectedCategory" :options="categories" optionLabel="name"
                                placeholder="All Categories" class="w-48" @change="onCategoryChange" />
                        </div>
                        <div class="flex gap-2">
                            <Button icon="pi pi-filter" severity="secondary" label="Filters" />
                            <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
                            <Button icon="pi pi-download" severity="secondary" label="Export" @click="exportCSV" />
                        </div>
                    </div>

                    <!-- Enhanced DataTable -->
                    <DataTable :value="filteredProducts" v-model:filters="filters" paginator :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50]" stripedRows showGridlines tableStyle="min-width: 50rem"
                        class="p-datatable-lg" :loading="loading" filterDisplay="menu"
                        :globalFilterFields="['product_name', 'category.title', 'shop.shop_name', 'status']">

                        <Column field="id" header="ID" sortable style="width: 8%">
                            <template #body="{ data }">
                                <span class="font-semibold text-gray-700">#{{ data.id }}</span>
                            </template>
                        </Column>

                        <Column field="product_name" header="Product" sortable style="width: 25%">
                            <template #body="{ data }">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img :src="data.image ? `/storage/${data.image}` : '/default-product.png'"
                                            :alt="data.product_name" class="w-12 h-12 rounded-lg object-cover shadow-sm"
                                            @error="handleImageError" />
                                        <div v-if="data.attributes.length === 0"
                                            class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs">!</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ data.product_name }}</div>
                                        <div class="text-sm text-gray-500">{{ data.category?.title }}</div>
                                        <div class="text-xs text-gray-400">{{ data.shop?.shop_name }}</div>
                                    </div>
                                </div>
                            </template>
                        </Column>

                        <Column field="description" header="Description" sortable style="width: 15%">
                            <template #body="{ data }">
                                <div class="text-sm text-gray-600">{{ data.description }}</div>
                                <div class="text-xs text-gray-400">Delivery Time: {{ data.time }} mins</div>
                            </template>
                        </Column>

                        <Column field="attributes" header="Price & Stock" sortable style="width: 20%">
                            <template #body="{ data }">
                                <div class="space-y-1">
                                    <template v-for="attr in data.attributes" :key="attr.id">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ attr.title }}:</span>
                                            <div>
                                                <span class="font-medium text-gray-900">₹{{ attr.price }}</span>
                                                <span class="text-xs text-gray-400 line-through ml-1">₹{{ attr.mrp
                                                    }}</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </Column>

                        <Column field="average_rating" header="Rating" sortable style="width: 12%">
                            <template #body="{ data }">
                                <div class="flex items-center gap-1">
                                    <i class="pi pi-star-fill"
                                        :class="data.average_rating ? 'text-yellow-400' : 'text-gray-300'"></i>
                                    <span>{{ data.average_rating || 'No ratings' }}</span>
                                    <span class="text-xs text-gray-500">({{ data.ratings?.length || 0 }})</span>
                                </div>
                            </template>
                        </Column>

                        <Column field="shop.shop_type" header="Type" sortable style="width: 10%">
                            <template #body="{ data }">
                                <Tag :value="getShopType(data.shop?.shop_type)"
                                    :severity="getShopTypeSeverity(data.shop?.shop_type)">
                                    {{ getShopType(data.shop?.shop_type) }}
                                </Tag>
                            </template>
                        </Column>

                        <Column field="status" header="Status" sortable style="width: 10%">
                            <template #body="{ data }">
                                <Tag :value="data.status" :severity="getStatusSeverity(data.status)">
                                    <i :class="getStatusIcon(data.status)" class="mr-1"></i>
                                    {{ data.status }}
                                </Tag>
                            </template>
                        </Column>

                        <Column header="Actions" :exportable="false" style="width: 15%">
                            <template #body="{ data }">
                                <div class="flex gap-2 justify-center">
                                    <Button icon="pi pi-pencil" text rounded severity="info" @click="handleEdit(data)"
                                        v-tooltip.top="'Edit Product'" />
                                    
                                    <Button icon="pi pi-trash" text rounded severity="danger"
                                        @click="handleDelete(data)" v-tooltip.top="'Delete Product'" />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import InputText from 'primevue/inputtext';
import Tooltip from 'primevue/tooltip';
import axios from 'axios';
import Dropdown from 'primevue/dropdown';

const products = ref([]);
const loading = ref(true);
const selectedCategory = ref(null);
const categories = ref([
    { name: 'All Categories', code: 'all' },
    { name: 'Biriyani', code: 'FS' },
    { name: 'Food Oils', code: 'GS' },
    { name: 'Veg Thali', code: 'FS' },
]);

const filters = ref({
    global: { value: null, matchMode: 'contains' },
    category: { value: null, matchMode: 'equals' },
    'shop.shop_type': { value: null, matchMode: 'equals' }
});

// Computed property for filtered products
const filteredProducts = computed(() => {
    let filtered = [...products.value];

    // Apply category filter
    if (selectedCategory.value && selectedCategory.value.code !== 'all') {
        filtered = filtered.filter(product =>
            product.shop?.shop_type === selectedCategory.value.code
        );
    }

    // Apply global search if exists
    if (filters.value.global.value) {
        const searchTerm = filters.value.global.value.toLowerCase();
        filtered = filtered.filter(product =>
            product.product_name.toLowerCase().includes(searchTerm) ||
            product.category?.title.toLowerCase().includes(searchTerm) ||
            product.shop?.shop_name.toLowerCase().includes(searchTerm) ||
            product.status.toLowerCase().includes(searchTerm)
        );
    }

    return filtered;
});

// Stats using filteredProducts instead of products
const totalProducts = computed(() => products.value.length);
const activeProducts = computed(() => filteredProducts.value.filter(p => p.status.toLowerCase() === 'active').length);
const outOfStockProducts = computed(() => filteredProducts.value.filter(p => !p.attributes.length).length);
const lowStockProducts = computed(() => filteredProducts.value.filter(p => p.attributes.some(attr => attr.value <= 5)).length);

const onCategoryChange = () => {
    if (selectedCategory.value) {
        filters.value.category.value = selectedCategory.value.code === 'all' ? null : selectedCategory.value.code;
    }
};

const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/products');
        products.value = response.data;
        console.log('Fetched products:', products.value); // Debug log
    } catch (error) {
        console.error('Error fetching products data:', error);
    } finally {
        loading.value = false;
    }
};

// Watch for changes in filters
watch(() => filters.value.global.value, (newValue) => {
    console.log('Search term changed:', newValue); // Debug log
});

const getStatusIcon = (status) => {
    switch (status.toLowerCase()) {
        case 'active':
            return 'pi pi-check-circle';
        case 'inactive':
            return 'pi pi-times-circle';
        case 'out_of_stock':
            return 'pi pi-exclamation-circle';
        default:
            return 'pi pi-question-circle';
    }
};

const getStatusSeverity = (status) => {
    switch (status.toLowerCase()) {
        case 'active':
            return 'success';
        case 'inactive':
            return 'danger';
        case 'out_of_stock':
            return 'warning';
        default:
            return 'secondary';
    }
};

const handleImageError = (event) => {
    event.target.src = '/default-product.png';
};

function handleEdit(rowData) {
    Inertia.visit(`/products/${rowData.id}/edit`);
}

function handleDelete(rowData) {
    if (confirm(`Are you sure you want to delete ${rowData.product_name}?`)) {
        axios.delete(`/api/products/${rowData.id}`)
            .then(() => {
                refreshData();
            })
            .catch((error) => {
                console.error('Error deleting product:', error);
            });
    }
}

const exportCSV = () => {
    // Implement CSV export functionality
};

const getShopType = (type) => {
    switch (type) {
        case 'FS':
            return 'Food Shop';
        case 'GS':
            return 'Grocery Shop';
        default:
            return 'Unknown';
    }
};

const getShopTypeSeverity = (type) => {
    switch (type) {
        case 'FS':
            return 'warning';
        case 'GS':
            return 'success';
        default:
            return 'info';
    }
};

onMounted(() => {
    refreshData();
});
</script>

<style scoped>
:deep(.p-datatable) {
    @apply bg-white rounded-lg shadow-sm;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
    @apply bg-gray-50 text-gray-700 font-semibold py-4;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
    @apply transition-colors duration-200;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
    @apply bg-gray-50;
}

:deep(.p-button) {
    @apply transition-transform duration-200;
}

:deep(.p-button:hover) {
    @apply transform scale-105;
}

:deep(.p-inputtext) {
    @apply border-gray-300 focus:border-emerald-500 focus:ring-emerald-500;
}

:deep(.p-tag) {
    @apply rounded-full font-medium;
}

:deep(.p-paginator) {
    @apply bg-white border-t border-gray-200 rounded-b-lg;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page.p-highlight) {
    @apply bg-emerald-500 text-white;
}

:deep(.p-dropdown) {
    @apply border-gray-300 focus:border-emerald-500 focus:ring-emerald-500;
}

.stats-card {
    @apply bg-white rounded-lg shadow-sm p-6 transition-transform duration-200;
}

.stats-card:hover {
    @apply transform scale-105;
}
</style>