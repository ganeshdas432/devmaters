<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-6">Payment Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Payment Method Toggles -->
            <div class="space-y-6 p-6 bg-gray-50 rounded-lg">
                <div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Cash Payment</h4>
                            <p class="text-sm text-gray-600">Allow customers to pay with cash</p>
                        </div>
                        <InputSwitch v-model="paymentSettings.cash_payment_enabled" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-medium">Digital Payment</h4>
                            <p class="text-sm text-gray-600">Allow customers to pay online via Razorpay</p>
                        </div>
                        <InputSwitch v-model="paymentSettings.digital_payment_enabled" />
                    </div>
                </div>
            </div>

            <!-- Razorpay Settings -->
            <div v-if="paymentSettings.digital_payment_enabled" class="space-y-4 p-6 bg-gray-50 rounded-lg">
                <h4 class="text-lg font-medium">Razorpay Configuration</h4>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay Key</label>
                    <InputText v-model="paymentSettings.razorpay_key" type="password" class="w-full" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay Secret</label>
                    <InputText v-model="paymentSettings.razorpay_secret" type="password" class="w-full" />
                </div>

                <div class="text-sm text-gray-600">
                    <p>Get your API keys from the Razorpay Dashboard:</p>
                    <a href="https://dashboard.razorpay.com/app/keys" target="_blank" class="text-blue-600 hover:text-blue-800">
                        Razorpay Dashboard →
                    </a>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-6">
            <Button label="Save Payment Settings" icon="pi pi-save" :loading="savingPaymentSettings" @click="savePaymentSettings" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";

// Payment Settings
const paymentSettings = ref({
    cash_payment_enabled: true,
    digital_payment_enabled: false,
    razorpay_key: '',
    razorpay_secret: ''
});
const savingPaymentSettings = ref(false);

// Fetch payment settings
const fetchPaymentSettings = async () => {
    try {
        const response = await axios.get('/api/payment-settings');
        paymentSettings.value = response.data;
    } catch (error) {
        console.error('Error fetching payment settings:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load payment settings',
            life: 3000
        });
    }
};

// Save payment settings
const savePaymentSettings = async () => {
    savingPaymentSettings.value = true;
    try {
        await axios.post('/api/payment-settings', paymentSettings.value);
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Payment settings saved successfully',
            life: 3000
        });
    } catch (error) {
        console.error('Error saving payment settings:', error);
        let errorMsg = 'Failed to save payment settings';
        if (error.response?.data?.errors) {
            errorMsg = Object.values(error.response.data.errors).join(', ');
        }
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: errorMsg,
            life: 3000
        });
    } finally {
        savingPaymentSettings.value = false;
    }
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchPaymentSettings();
});
</script>
