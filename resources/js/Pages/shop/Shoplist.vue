<template>

  <Head title="Shop Management" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Shop Management</h2>

      </div>
    </template>

    <div class="py-12">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="card">
          <!-- Search and Filter Section -->
          <div class="mb-4 flex flex-wrap gap-4 justify-between items-center p-4 border-b bg-gray-50 rounded-t-lg">
            <span class="p-input-icon-left w-full md:w-96">
              <i class="pi pi-search" />
              <InputText v-model="searchQuery" placeholder="Search shops..." class="w-full" />
            </span>
            <div class="flex gap-2">
              <Button icon="pi pi-filter" severity="secondary" label="Filters" />
              <Button icon="pi pi-refresh" severity="secondary" rounded @click="refreshData" />
              <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center"
                :href="route('shop.add')">
                <i class="pi pi-plus mr-2"></i>
                Add Shop
              </a>
            </div>
          </div>

          <!-- Enhanced DataTable -->
          <DataTable :value="shops" :paginator="true" :rows="10" :rowsPerPageOptions="[10, 20, 50]"
            v-model:filters="filters" filterDisplay="menu" :loading="loading" stripedRows showGridlines
            :globalFilterFields="['shop_name', 'mobile', 'address', 'shop_type']" tableStyle="min-width: 50rem"
            class="p-datatable-lg">
            <Column field="id" header="ID" sortable style="width: 8%">
              <template #body="{ data }">
                <span class="font-semibold text-gray-700">#{{ data.id }}</span>
              </template>
            </Column>

            <Column header="Shop" sortable field="shop_name" style="width: 25%">
              <template #body="{ data }">
                <div class="flex items-center gap-3">
                  <img :src="`/storage/${data.image}`" :alt="data.shop_name"
                    class="w-12 h-12 rounded-lg object-cover shadow-sm" @error="handleImageError" />
                  <div>
                    <div class="font-medium">{{ data.shop_name }}</div>
                    <div class="text-sm text-gray-500">{{ data.shop_type }}</div>
                  </div>
                </div>
              </template>
            </Column>

            <Column field="address" header="Address" sortable style="width: 20%">
              <template #body="{ data }">
                <div class="flex items-center gap-2">
                  <i class="pi pi-map-marker text-gray-400"></i>
                  <span class="text-sm">{{ data.address }}</span>
                </div>
              </template>
            </Column>

            <Column field="mobile" header="Contact" sortable style="width: 15%">
              <template #body="{ data }">
                <div class="flex items-center gap-2">
                  <i class="pi pi-phone text-gray-400"></i>
                  {{ data.mobile }}
                </div>
              </template>
            </Column>

            <Column field="status" header="Status" sortable style="width: 12%">
              <template #body="slotProps">
                <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data.status)"
                  class="text-xs px-3 py-1">
                  <i :class="getStatusIcon(slotProps.data.status)" class="mr-1"></i>
                  {{ slotProps.data.status }}
                </Tag>
              </template>
            </Column>

            <Column header="Actions" :exportable="false" style="width: 15%">
              <template #body="slotProps">
                <div class="flex gap-2 justify-center">
                  <Button icon="pi pi-pencil" text rounded severity="info" @click="handleAction(slotProps.data)"
                    v-tooltip.top="'Edit Shop'" />
                  <Button icon="pi pi-user" text rounded severity="success" @click="handleUserAsign(slotProps.data)"
                    v-tooltip.top="'Assign User'" />
                  <Button icon="pi pi-trash" text rounded severity="danger" v-tooltip.top="'Delete Shop'" />
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
import { ref, onMounted } from 'vue';
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

const shops = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const filters = ref({
  global: { value: null, matchMode: 'contains' },
});

const getStatusIcon = (status) => {
  switch (status) {
    case 'active':
      return 'pi pi-check-circle';
    case 'inactive':
      return 'pi pi-times-circle';
    default:
      return 'pi pi-question-circle';
  }
};

const getSeverity = (status) => {
  switch (status) {
    case 'inactive':
      return 'danger';
    case 'active':
      return 'success';
    default:
      return 'info';
  }
};

const handleImageError = (event) => {
  event.target.src = '/default-shop-image.png'; // Provide a default image path
};

const refreshData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/shoplist');
    shops.value = response.data.shops;
  } catch (error) {
    console.error('Error fetching shop data:', error);
  } finally {
    loading.value = false;
  }
};

function handleAction(rowData) {
  Inertia.visit(`/shops/edit/${rowData.id}`);
}

function handleUserAsign(rowData) {
  Inertia.visit(`/shops/asignuser/${rowData.id}`);
}

async function updateStatus(shop) {
  try {
    const newStatus = shop.status === 'active' ? 'inactive' : 'active';
    await axios.put(`/api/shops/${shop.id}/status`, { status: newStatus });
    shop.status = newStatus;
  } catch (error) {
    console.error('Error updating status:', error);
  }
}

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
</style>