<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
    </template>

    <div class="py-12">
      <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-semibold mr-4">Ratings</h4>

        </div>
        <div class="card">
          <DataTable :value="ratings" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
            tableStyle="min-width: 50rem">
            <Column field="id" header="ID"></Column>

            <Column header="Image">
              <template #body="slotProps">
                <img class="block mx-auto rounded w-full"
                  :src="slotProps.data.product.image ? '/storage/' + slotProps.data.product.image : '/storage/products/default.png'"
                  :alt="slotProps.data.product_name" style="max-width: 150px; object-fit: cover;" />
              </template>
            </Column>
            <Column field="product.product_name" header="Product Title" style="width: 25%"></Column>

            <Column field="comment" header="Comment" style="width: 25%"></Column>
            <Column header="Stars" style="width: 25%">
              <template #body="{ data }">
                <div class="flex justify-center items-center">
                  <Rating v-model="data.stars" readonly :stars="5" />
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
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import axios from 'axios';

const ratings = ref([]);
// Fetch the list of ratings on mount
onMounted(async () => {
  try {
    const response = await axios.get('/api/ratinglist');
    ratings.value = response.data.ratings; // Set the fetched ratings
  } catch (error) {
    console.error('Error fetching ratings data:', error);
  }
});
</script>