<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-4">Refund Settings</h3>
        <form @submit.prevent="submitRefundSettingsForm" class="w-full">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label for="max_refund_days" class="block text-sm font-medium mb-1">Max Refund Days</label>
                    <InputText id="max_refund_days" v-model="refundSettingsForm.max_refund_days" class="w-full p-2 border rounded" />
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-medium mb-1">Active</label>
                    <InputSwitch id="is_active" v-model="refundSettingsForm.is_active" />
                </div>
            </div>
            <Button type="submit" class="btn btn-primary w-full">Update Refund Settings</Button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputSwitch from 'primevue/inputswitch';
import { useToast } from "primevue/usetoast";

// Refund Settings
const refundSettingsForm = ref({
    max_refund_days: '',
    is_active: true,
});

// Fetch refund settings
const fetchRefundSettings = async () => {
    try {
        const response = await axios.get('/api/refund-settings');
        refundSettingsForm.value = response.data;
    } catch (error) {
        console.error('Error fetching refund settings:', error);
    }
};

// Submit refund settings form
const submitRefundSettingsForm = async () => {
    try {
        await axios.put('/api/refund-settings', refundSettingsForm.value);
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Refund settings updated successfully',
            life: 3000
        });
    } catch (error) {
        console.error('Error updating refund settings:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update refund settings',
            life: 3000
        });
    }
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchRefundSettings();
});
</script>
