<template>
    <div class="p-4">
        <h3 class="text-xl font-semibold mb-6">Notification & Email Settings</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Firebase Configuration -->
            <div class="space-y-4 p-6 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium">Firebase Configuration</h4>
                    <InputSwitch v-model="notificationSettings.firebase_enabled" />
                </div>

                <div v-if="notificationSettings.firebase_enabled" class="space-y-4">
                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">API Key</label>
                        <InputText v-model="notificationSettings.api_key" class="w-full" placeholder="Enter Firebase API Key" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Project ID</label>
                        <InputText v-model="notificationSettings.project_id" class="w-full" placeholder="Enter Project ID" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Auth Domain</label>
                        <InputText v-model="notificationSettings.auth_domain" class="w-full" placeholder="Enter Auth Domain" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Storage Bucket</label>
                        <InputText v-model="notificationSettings.storage_bucket" class="w-full" placeholder="Enter Storage Bucket" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Messaging Sender ID</label>
                        <InputText v-model="notificationSettings.messaging_sender_id" class="w-full" placeholder="Enter Messaging Sender ID" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">App ID</label>
                        <InputText v-model="notificationSettings.app_id" class="w-full" placeholder="Enter App ID" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Measurement ID</label>
                        <InputText v-model="notificationSettings.measurement_id" class="w-full" placeholder="Enter Measurement ID" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Server Key</label>
                        <InputText v-model="notificationSettings.server_key" type="password" class="w-full" placeholder="Enter Server Key" />
                    </div>

                    <div class="text-sm text-gray-600">
                        <p>Get your Firebase configuration from the Firebase Console:</p>
                        <a href="https://console.firebase.google.com" target="_blank" class="text-blue-600 hover:text-blue-800">
                            Firebase Console →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Email Configuration -->
            <div class="space-y-4 p-6 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-medium">Email Configuration</h4>
                    <InputSwitch v-model="emailSettings.smtp_enabled" />
                </div>

                <div v-if="emailSettings.smtp_enabled" class="space-y-4">
                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                        <InputText v-model="emailSettings.smtp_host" class="w-full" placeholder="e.g., smtp.gmail.com" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                        <InputNumber v-model="emailSettings.smtp_port" class="w-full" placeholder="e.g., 587" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                        <InputText v-model="emailSettings.smtp_username" class="w-full" placeholder="Enter SMTP Username" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                        <Password v-model="emailSettings.smtp_password" class="w-full" :feedback="false" :toggleMask="true" inputClass="w-full" placeholder="Enter SMTP Password" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">From Name</label>
                        <InputText v-model="emailSettings.from_name" class="w-full" placeholder="e.g., Your Company Name" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">From Email</label>
                        <InputText v-model="emailSettings.from_email" class="w-full" placeholder="e.g., noreply@yourcompany.com" />
                    </div>

                    <div class="field">
                        <label class="block text-sm font-medium text-gray-700">Encryption</label>
                        <Dropdown v-model="emailSettings.encryption" :options="encryptionOptions" class="w-full" placeholder="Select Encryption" optionLabel="label" optionValue="value" />
                    </div>

                    <!-- Test Email Button -->
                    <div class="mt-4 pt-4 border-t">
                        <Button label="Test Email Configuration" icon="pi pi-envelope" severity="secondary" @click="showTestEmailDialog = true" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
            <Button label="Save Settings" icon="pi pi-save" :loading="savingSettings" @click="saveNotificationAndEmailSettings" />
        </div>

        <!-- Test Email Dialog -->
        <Dialog v-model:visible="showTestEmailDialog" header="Test Email Configuration" :modal="true" :style="{ width: '500px' }">
            <div class="space-y-4">
                <div class="field">
                    <label class="block text-sm font-medium text-gray-700">Test Email Address</label>
                    <InputText v-model="testEmailAddress" class="w-full" placeholder="Enter email address to send test mail" />
                    <small class="text-gray-500">A test email will be sent to this address</small>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg text-sm">
                    <p class="font-medium mb-2">This will:</p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Verify SMTP connection</li>
                        <li>Test authentication</li>
                        <li>Send a test email</li>
                        <li>Check delivery status</li>
                    </ul>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancel" icon="pi pi-times" @click="showTestEmailDialog = false" text />
                    <Button label="Send Test Email" icon="pi pi-send" :loading="sendingTestEmail" @click="sendTestEmail" severity="info" />
                </div>
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Password from 'primevue/password';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { useToast } from "primevue/usetoast";

// Notification and Email Settings
const notificationSettings = ref({
    firebase_enabled: false,
    api_key: '',
    project_id: '',
    auth_domain: '',
    storage_bucket: '',
    messaging_sender_id: '',
    app_id: '',
    measurement_id: '',
    server_key: ''
});

const emailSettings = ref({
    smtp_enabled: false,
    smtp_host: '',
    smtp_port: null,
    smtp_username: '',
    smtp_password: '',
    from_name: '',
    from_email: '',
    encryption: { label: 'TLS', value: 'tls' }
});

const encryptionOptions = [
    { label: 'TLS', value: 'tls' },
    { label: 'SSL', value: 'ssl' },
    { label: 'None', value: 'none' }
];

const savingSettings = ref(false);

// Fetch notification and email settings
const fetchNotificationAndEmailSettings = async () => {
    try {
        const [fcmResponse, emailResponse] = await Promise.all([
            axios.get('/api/fcm-config'),
            axios.get('/api/email-config')
        ]);

        notificationSettings.value = {
            firebase_enabled: fcmResponse.data.enabled ?? false,
            api_key: fcmResponse.data.api_key ?? '',
            project_id: fcmResponse.data.project_id ?? '',
            auth_domain: fcmResponse.data.auth_domain ?? '',
            storage_bucket: fcmResponse.data.storage_bucket ?? '',
            messaging_sender_id: fcmResponse.data.messaging_sender_id ?? '',
            app_id: fcmResponse.data.app_id ?? '',
            measurement_id: fcmResponse.data.measurement_id ?? '',
            server_key: fcmResponse.data.server_key ?? ''
        };

        emailSettings.value = {
            smtp_enabled: emailResponse.data.smtp_enabled ?? false,
            smtp_host: emailResponse.data.smtp_host ?? '',
            smtp_port: emailResponse.data.smtp_port ?? null,
            smtp_username: emailResponse.data.smtp_username ?? '',
            smtp_password: emailResponse.data.smtp_password ?? '',
            from_name: emailResponse.data.from_name ?? '',
            from_email: emailResponse.data.from_email ?? '',
            encryption: emailResponse.data.encryption ?? 'tls'
        };
    } catch (error) {
        console.error('Error fetching settings:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load notification and email settings',
            life: 3000
        });
    }
};

// Save notification and email settings
const saveNotificationAndEmailSettings = async () => {
    savingSettings.value = true;
    try {
        // Prepare email settings data
        const emailData = { ...emailSettings.value };
        // Send the encryption value directly
        emailData.encryption = emailSettings.value.encryption;

        await Promise.all([
            axios.post('/api/fcm-config', notificationSettings.value),
            axios.post('/api/email-config', emailData)
        ]);

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Settings saved successfully',
            life: 3000
        });
    } catch (error) {
        console.error('Error saving settings:', error);
        let errorMsg = 'Failed to save settings';
        if (error.response?.data?.errors) {
            errorMsg = Object.values(error.response.data.errors).join(', ');
        }
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: errorMsg,
            life: 5000
        });
    } finally {
        savingSettings.value = false;
    }
};

// Test Email State
const showTestEmailDialog = ref(false);
const testEmailAddress = ref('');
const sendingTestEmail = ref(false);

// Send test email function
const sendTestEmail = async () => {
    if (!testEmailAddress.value) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Please enter an email address',
            life: 3000
        });
        return;
    }

    sendingTestEmail.value = true;
    try {
        await axios.post('/api/email-config/test', {
            email: testEmailAddress.value
        });

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Test email sent successfully! Please check your inbox.',
            life: 5000
        });
        showTestEmailDialog.value = false;
        testEmailAddress.value = '';
    } catch (error) {
        console.error('Error sending test email:', error);
        let errorMsg = 'Failed to send test email';
        if (error.response?.data?.message) {
            errorMsg = error.response.data.message;
        }
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: errorMsg,
            life: 5000
        });
    } finally {
        sendingTestEmail.value = false;
    }
};

// Add toast service
const toast = useToast();

onMounted(() => {
    fetchNotificationAndEmailSettings();
});
</script>
