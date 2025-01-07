<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-6">Pages</h3>
        <DataTable :value="pages" :loading="loadingPages" stripedRows showGridlines class="p-datatable-sm">
            <Column field="title" header="Title" sortable></Column>
            <Column field="description" header="Description">
                <template #body="{ data }">
                    <div class="max-w-md truncate">{{ data.description }}</div>
                </template>
            </Column>
            <Column field="status" header="Status" sortable>
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="data.status === 'active' ? 'success' : 'danger'" />
                </template>
            </Column>
            <Column header="Actions">
                <template #body="{ data }">
                    <Button icon="pi pi-pencil" text rounded severity="info" @click="editPage(data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="showEditPageDialog" :modal="true" :style="{ width: '50vw' }" header="Edit Page">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <InputText v-model="pageForm.title" class="w-full" disabled />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <Textarea v-model="pageForm.description" rows="5" class="w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <Dropdown v-model="pageForm.status" :options="['active', 'inactive']" class="w-full" />
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancel" severity="secondary" @click="showEditPageDialog = false" />
                    <Button label="Update" severity="success" :loading="savingPage" @click="savePage" />
                </div>
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";

// Pages Management
const pages = ref([]);
const loadingPages = ref(false);
const showEditPageDialog = ref(false);
const savingPage = ref(false);
const pageForm = ref({
    id: null,
    title: '',
    description: '',
    status: 'active'
});

// Fetch pages
const fetchPages = async () => {
    loadingPages.value = true;
    try {
        const response = await axios.get('/api/pages');
        pages.value = response.data.pages;
    } catch (error) {
        console.error('Error fetching pages:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load pages',
            life: 3000
        });
    } finally {
        loadingPages.value = false;
    }
};

// Edit page
const editPage = (page) => {
    pageForm.value = { ...page };
    showEditPageDialog.value = true;
};

// Save page
const savePage = async () => {
    savingPage.value = true;
    try {
        await axios.put(`/api/pages/${pageForm.value.id}`, pageForm.value);
        showEditPageDialog.value = false;
        fetchPages();
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Page updated successfully',
            life: 3000
        });
    } catch (error) {
        console.error('Error updating page:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update page',
            life: 3000
        });
    } finally {
        savingPage.value = false;
    }
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchPages();
});
</script>
