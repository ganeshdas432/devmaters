<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <Toast />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Company Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold mr-4">Settings</h4>
                </div>
                <div class="card">
                    <Tabs value="0">
                        <TabList>
                            <Tab value="0">General Settings</Tab>
                            <Tab value="1">Order Settings</Tab>
                            <Tab value="2">Refund Settings</Tab>
                            <Tab value="3">Stores</Tab>
                            <Tab value="4">Customers</Tab>
                            <Tab value="5">Pages</Tab>
                            <Tab value="6">Payments</Tab>
                            <Tab value="7">Zone</Tab>
                            <Tab value="8">Notification</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold mb-4">General Settings</h3>
                                    <form @submit.prevent="submitForm" class="w-full">
                                        <div class="grid grid-cols-1 gap-4 mb-4">
                                            <div>
                                                <label for="title" class="block text-sm font-medium mb-1">Company
                                                    Name</label>
                                                <InputText id="title" v-model="Form.title"
                                                    class="w-full p-2 border rounded" />
                                            </div>
                                            <div>
                                                <label for="mobile" class="block text-sm font-medium mb-1">Company
                                                    Mobile
                                                    No</label>
                                                <InputText id="mobile" v-model="Form.mobile"
                                                    class="w-full p-2 border rounded" />
                                            </div>
                                            <div>
                                                <label for="email" class="block text-sm font-medium mb-1">Company
                                                    Email</label>
                                                <InputText id="email" v-model="Form.email"
                                                    class="w-full p-2 border rounded" />
                                            </div>
                                            <div>
                                                <label for="address" class="block text-sm font-medium mb-1">Company
                                                    Address</label>
                                                <InputText id="address" v-model="Form.address"
                                                    class="w-full p-2 border rounded" />
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <!-- Display Existing Logo -->
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Square Logo</label>
                                                    <img v-if="company.company_logo_square"
                                                        :src="'storage/' + company.company_logo_square"
                                                        alt="Company Square Logo" class="w-32 h-32 object-cover" />
                                                </div>
                                                <div class="card flex justify-center">
                                                    <FileUpload mode="basic" name="company_logo_square" accept="image/*"
                                                        :maxFileSize="1000000" :auto="false"
                                                        chooseLabel="Upload Square Logo"
                                                        @select="(event) => onFileSelect('company_logo_square', event)" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Rectangular
                                                        Logo</label>
                                                    <img v-if="company.company_logo_rectangular"
                                                        :src="'storage/' + company.company_logo_rectangular"
                                                        alt="Company Banner" class="w-100 h-32 object-cover" />
                                                </div>
                                                <div class="card flex justify-center">
                                                    <FileUpload mode="basic" name="company_logo_rectangular"
                                                        accept="image/*" :maxFileSize="1000000" :auto="false"
                                                        chooseLabel="Upload Rectangular Logo"
                                                        @select="(event) => onFileSelect('company_logo_rectangular', event)" />
                                                </div>
                                            </div>
                                        </div>

                                        <Button type="submit" class="btn btn-primary w-full">Update Company
                                            Details</Button>
                                    </form>
                                </div>
                            </TabPanel>
                            <TabPanel value="1">
                                <!-- Order Settings tab content will go here -->
                            </TabPanel>
                            <TabPanel value="2">
                                <!-- Refund Settings tab content will go here -->
                            </TabPanel>
                            <TabPanel value="3">
                                <!-- Stores tab content will go here -->
                            </TabPanel>
                            <TabPanel value="4">
                                <!-- Customers tab content will go here -->
                            </TabPanel>
                            <TabPanel value="5">
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold mb-6">Pages</h3>

                                    <!-- Pages DataTable -->
                                    <DataTable :value="pages" :loading="loadingPages" stripedRows showGridlines
                                        class="p-datatable-sm">
                                        <Column field="title" header="Title" sortable></Column>
                                        <Column field="description" header="Description">
                                            <template #body="{ data }">
                                                <div class="max-w-md truncate">{{ data.description }}</div>
                                            </template>
                                        </Column>
                                        <Column field="status" header="Status" sortable>
                                            <template #body="{ data }">
                                                <Tag :value="data.status"
                                                    :severity="data.status === 'active' ? 'success' : 'danger'" />
                                            </template>
                                        </Column>
                                        <Column header="Actions">
                                            <template #body="{ data }">
                                                <Button icon="pi pi-pencil" text rounded severity="info"
                                                    @click="editPage(data)" />
                                            </template>
                                        </Column>
                                    </DataTable>

                                    <!-- Edit Page Dialog -->
                                    <Dialog v-model:visible="showEditPageDialog" :modal="true"
                                        :style="{ width: '50vw' }" header="Edit Page">
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
                                                <Dropdown v-model="pageForm.status" :options="['active', 'inactive']"
                                                    class="w-full" />
                                            </div>
                                        </div>

                                        <template #footer>
                                            <div class="flex justify-end gap-2">
                                                <Button label="Cancel" severity="secondary"
                                                    @click="showEditPageDialog = false" />
                                                <Button label="Update" severity="success" :loading="savingPage"
                                                    @click="savePage" />
                                            </div>
                                        </template>
                                    </Dialog>
                                </div>
                            </TabPanel>
                            <TabPanel value="6">
                                <div class="p-4">
                                    <h3 class="text-xl font-semibold mb-6">Payment Settings</h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Payment Method Toggles -->
                                        <div class="space-y-6 p-6 bg-gray-50 rounded-lg">
                                            <div>
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <h4 class="text-lg font-medium">Cash Payment</h4>
                                                        <p class="text-sm text-gray-600">Allow customers to pay with
                                                            cash</p>
                                                    </div>
                                                    <InputSwitch v-model="paymentSettings.cash_payment_enabled" />
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <h4 class="text-lg font-medium">Digital Payment</h4>
                                                        <p class="text-sm text-gray-600">Allow customers to pay online
                                                            via
                                                            Razorpay</p>
                                                    </div>
                                                    <InputSwitch v-model="paymentSettings.digital_payment_enabled" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Razorpay Settings -->
                                        <div v-if="paymentSettings.digital_payment_enabled"
                                            class="space-y-4 p-6 bg-gray-50 rounded-lg">
                                            <h4 class="text-lg font-medium">Razorpay Configuration</h4>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay
                                                    Key</label>
                                                <InputText v-model="paymentSettings.razorpay_key" type="password"
                                                    class="w-full" />
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Razorpay
                                                    Secret</label>
                                                <InputText v-model="paymentSettings.razorpay_secret" type="password"
                                                    class="w-full" />
                                            </div>

                                            <div class="text-sm text-gray-600">
                                                <p>Get your API keys from the Razorpay Dashboard:</p>
                                                <a href="https://dashboard.razorpay.com/app/keys" target="_blank"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Razorpay Dashboard →
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="mt-6">
                                        <Button label="Save Payment Settings" icon="pi pi-save"
                                            :loading="savingPaymentSettings" @click="savePaymentSettings" />
                                    </div>
                                </div>
                            </TabPanel>
                            <TabPanel value="7">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-xl font-semibold">Delivery Zones</h3>
                                        <Button @click="showAddZoneDialog = true" icon="pi pi-plus" label="Add New Zone"
                                            severity="success" />
                                    </div>

                                    <!-- Zones DataTable -->
                                    <DataTable :value="zones" :loading="loading" stripedRows showGridlines
                                        class="p-datatable-sm">

                                        <Column field="name" header="Zone Name" sortable></Column>
                                        <Column field="delivery_charge_per_km" header="Charge per KM" sortable>
                                            <template #body="{ data }">
                                                ₹{{ data.delivery_charge_per_km }}
                                            </template>
                                        </Column>
                                        <Column field="base_delivery_charge" header="Base Charge" sortable>
                                            <template #body="{ data }">
                                                ₹{{ data.base_delivery_charge }}
                                            </template>
                                        </Column>
                                        <Column field="minimum_delivery_charge" header="Min. Charge" sortable>
                                            <template #body="{ data }">
                                                ₹{{ data.minimum_delivery_charge }}
                                            </template>
                                        </Column>
                                        <Column field="status" header="Status" sortable>
                                            <template #body="{ data }">
                                                <Tag :value="data.status" :severity="data.status_badge" />
                                            </template>
                                        </Column>
                                        <Column header="Actions">
                                            <template #body="{ data }">
                                                <div class="flex gap-2">
                                                    <Button icon="pi pi-pencil" text rounded severity="info"
                                                        @click="editZone(data)" />
                                                    <Button icon="pi pi-trash" text rounded severity="danger"
                                                        @click="confirmDeleteZone(data)" />
                                                </div>
                                            </template>
                                        </Column>
                                    </DataTable>

                                    <!-- Add/Edit Zone Dialog -->
                                    <Dialog v-model:visible="showAddZoneDialog" :modal="true" :style="{ width: '80vw' }"
                                        :header="editingZone ? 'Edit Zone' : 'Add New Zone'">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Form Fields -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Zone Name</label>
                                                    <InputText v-model="zoneForm.name" class="w-full" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Delivery Charge per KM
                                                        (₹)</label>
                                                    <InputNumber v-model="zoneForm.delivery_charge_per_km"
                                                        mode="decimal" :minFractionDigits="2" class="w-full" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Base Delivery Charge
                                                        (₹)</label>
                                                    <InputNumber v-model="zoneForm.base_delivery_charge" mode="decimal"
                                                        :minFractionDigits="2" class="w-full" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Minimum Delivery
                                                        Charge
                                                        (₹)</label>
                                                    <InputNumber v-model="zoneForm.minimum_delivery_charge"
                                                        mode="decimal" :minFractionDigits="2" class="w-full" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Description</label>
                                                    <Textarea v-model="zoneForm.description" rows="3" class="w-full" />
                                                </div>
                                                <div v-if="editingZone">
                                                    <label class="block text-sm font-medium mb-1">Status</label>
                                                    <Dropdown v-model="zoneForm.status"
                                                        :options="['active', 'inactive']" class="w-full" />
                                                </div>
                                            </div>

                                            <!-- Map for Drawing Zone -->
                                            <div class="h-[500px] relative">
                                                <div ref="mapRef" class="w-full h-full rounded-lg"></div>
                                                <div class="absolute top-4 right-4 bg-white p-2 rounded shadow-md">
                                                    <Button v-if="!isDrawing" icon="pi pi-pencil" label="Draw Zone"
                                                        @click="startDrawing" />
                                                    <Button v-else icon="pi pi-times" label="Cancel" severity="danger"
                                                        @click="cancelDrawing" />
                                                </div>
                                            </div>
                                        </div>

                                        <template #footer>
                                            <div class="flex justify-end gap-2">
                                                <Button label="Cancel" severity="secondary"
                                                    @click="showAddZoneDialog = false" />
                                                <Button :label="editingZone ? 'Update' : 'Save'" severity="success"
                                                    @click="saveZone" />
                                            </div>
                                        </template>
                                    </Dialog>

                                    <!-- Delete Confirmation Dialog -->
                                    <Dialog v-model:visible="showDeleteDialog" modal header="Confirm Delete"
                                        :style="{ width: '450px' }">
                                        <div class="confirmation-content">
                                            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                                            <span>Are you sure you want to delete this zone?</span>
                                        </div>
                                        <template #footer>
                                            <Button label="No" severity="secondary" @click="showDeleteDialog = false" />
                                            <Button label="Yes" severity="danger" @click="deleteZone" />
                                        </template>
                                    </Dialog>
                                </div>
                            </TabPanel>
                            <TabPanel value="8">
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
                                                    <label class="block text-sm font-medium text-gray-700">API
                                                        Key</label>
                                                    <InputText v-model="notificationSettings.api_key" class="w-full"
                                                        placeholder="Enter Firebase API Key" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Project
                                                        ID</label>
                                                    <InputText v-model="notificationSettings.project_id" class="w-full"
                                                        placeholder="Enter Project ID" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Auth
                                                        Domain</label>
                                                    <InputText v-model="notificationSettings.auth_domain" class="w-full"
                                                        placeholder="Enter Auth Domain" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Storage
                                                        Bucket</label>
                                                    <InputText v-model="notificationSettings.storage_bucket"
                                                        class="w-full" placeholder="Enter Storage Bucket" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Messaging
                                                        Sender
                                                        ID</label>
                                                    <InputText v-model="notificationSettings.messaging_sender_id"
                                                        class="w-full" placeholder="Enter Messaging Sender ID" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">App
                                                        ID</label>
                                                    <InputText v-model="notificationSettings.app_id" class="w-full"
                                                        placeholder="Enter App ID" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Measurement
                                                        ID</label>
                                                    <InputText v-model="notificationSettings.measurement_id"
                                                        class="w-full" placeholder="Enter Measurement ID" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">Server
                                                        Key</label>
                                                    <InputText v-model="notificationSettings.server_key" type="password"
                                                        class="w-full" placeholder="Enter Server Key" />
                                                </div>

                                                <div class="text-sm text-gray-600">
                                                    <p>Get your Firebase configuration from the Firebase Console:</p>
                                                    <a href="https://console.firebase.google.com" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800">
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
                                                    <label class="block text-sm font-medium text-gray-700">SMTP
                                                        Host</label>
                                                    <InputText v-model="emailSettings.smtp_host" class="w-full"
                                                        placeholder="e.g., smtp.gmail.com" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">SMTP
                                                        Port</label>
                                                    <InputNumber v-model="emailSettings.smtp_port" class="w-full"
                                                        placeholder="e.g., 587" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">SMTP
                                                        Username</label>
                                                    <InputText v-model="emailSettings.smtp_username" class="w-full"
                                                        placeholder="Enter SMTP Username" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">SMTP
                                                        Password</label>
                                                    <Password v-model="emailSettings.smtp_password" class="w-full"
                                                        :feedback="false" :toggleMask="true" inputClass="w-full"
                                                        placeholder="Enter SMTP Password" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">From
                                                        Name</label>
                                                    <InputText v-model="emailSettings.from_name" class="w-full"
                                                        placeholder="e.g., Your Company Name" />
                                                </div>

                                                <div class="field">
                                                    <label class="block text-sm font-medium text-gray-700">From
                                                        Email</label>
                                                    <InputText v-model="emailSettings.from_email" class="w-full"
                                                        placeholder="e.g., noreply@yourcompany.com" />
                                                </div>

                                                <div class="field">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700">Encryption</label>
                                                    <Dropdown v-model="emailSettings.encryption"
                                                        :options="encryptionOptions" class="w-full"
                                                        placeholder="Select Encryption" optionLabel="label"
                                                        optionValue="value" />
                                                </div>

                                                <!-- Test Email Button -->
                                                <div class="mt-4 pt-4 border-t">
                                                    <Button label="Test Email Configuration" icon="pi pi-envelope"
                                                        severity="secondary" @click="showTestEmailDialog = true" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="mt-6 flex justify-end">
                                        <Button label="Save Settings" icon="pi pi-save" :loading="savingSettings"
                                            @click="saveNotificationAndEmailSettings" />
                                    </div>

                                    <!-- Test Email Dialog -->
                                    <Dialog v-model:visible="showTestEmailDialog" header="Test Email Configuration"
                                        :modal="true" :style="{ width: '500px' }">
                                        <div class="space-y-4">
                                            <div class="field">
                                                <label class="block text-sm font-medium text-gray-700">Test Email
                                                    Address</label>
                                                <InputText v-model="testEmailAddress" class="w-full"
                                                    placeholder="Enter email address to send test mail" />
                                                <small class="text-gray-500">A test email will be sent to this
                                                    address</small>
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
                                                <Button label="Cancel" icon="pi pi-times"
                                                    @click="showTestEmailDialog = false" text />
                                                <Button label="Send Test Email" icon="pi pi-send"
                                                    :loading="sendingTestEmail" @click="sendTestEmail"
                                                    severity="info" />
                                            </div>
                                        </template>
                                    </Dialog>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import NavLink from '@/Components/NavLink.vue';
import FileUpload from 'primevue/fileupload';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import InputSwitch from 'primevue/inputswitch';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import Password from 'primevue/password';

// Form data setup
const Form = useForm({
    title: '',
    mobile: '',
    email: '',
    address: '',
    company_logo_square: null,
    company_logo_rectangular: null,
});

// Store company details
const company = ref({});

// Fetch company details
const fetchCompanyDetails = async () => {
    try {
        const response = await axios.get('/api/details');
        company.value = response.data;
        Form.title = response.data.company_name;
        Form.mobile = response.data.company_mobile;
        Form.email = response.data.company_email;
        Form.address = response.data.company_address;
        Form.company_logo_square = response.data.company_logo_square;
        Form.company_logo_rectangular = response.data.company_logo_rectangular;
    } catch (error) {
        console.error('Error fetching company details:', error);
    }
};

// Submit form data
const submitForm = () => {
    const formData = new FormData();
    formData.append('title', Form.title);
    formData.append('mobile', Form.mobile);
    formData.append('email', Form.email);
    formData.append('address', Form.address);

    if (Form.company_logo_square) {
        formData.append('company_logo_square', Form.company_logo_square);
    }

    if (Form.company_logo_rectangular) {
        formData.append('company_logo_rectangular', Form.company_logo_rectangular);
    }

    Form.post('/api/details/update', {
        data: formData,
        forceFormData: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Company details updated successfully',
                life: 3000
            });
            fetchCompanyDetails(); // Refresh the data
        },
        onError: (errors) => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: Object.values(errors).join('\n'),
                life: 3000
            });
        },
        preserveScroll: true
    });
};

// Handle file selection for uploads
const onFileSelect = (type, event) => {
    Form[type] = event.files[0];
};

// Zone management state
const zones = ref([]);
const loading = ref(false);
const showAddZoneDialog = ref(false);
const showDeleteDialog = ref(false);
const editingZone = ref(null);
const map = ref(null);
const mapRef = ref(null);
const drawingManager = ref(null);
const currentPolygon = ref(null);
const isDrawing = ref(false);

const mapCenter = ref({ lat: 24.6557, lng: 87.9548 });

const zoneForm = ref({
    name: '',
    delivery_charge_per_km: 0,
    base_delivery_charge: 0,
    minimum_delivery_charge: 0,
    description: '',
    coordinates: [],
    status: 'active'
});

// Fetch zones
const fetchZones = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/zones');
        zones.value = response.data.zones;
    } catch (error) {
        console.error('Error fetching zones:', error);
    } finally {
        loading.value = false;
    }
};

// Initialize map
const initMap = () => {
    if (!mapRef.value) return;

    // Create map instance
    map.value = new google.maps.Map(mapRef.value, {
        center: mapCenter.value,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
    });

    // Create drawing manager
    drawingManager.value = new google.maps.drawing.DrawingManager({
        drawingMode: null,
        drawingControl: false,
        polygonOptions: {
            fillColor: '#42A5F5',
            fillOpacity: 0.3,
            strokeWeight: 2,
            strokeColor: '#1976D2',
            clickable: true,
            editable: true,
            zIndex: 1
        }
    });

    drawingManager.value.setMap(map.value);

    // Listen for polygon completion
    google.maps.event.addListener(drawingManager.value, 'polygoncomplete', (polygon) => {
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
        }
        currentPolygon.value = polygon;
        isDrawing.value = false;

        // Get coordinates
        const coordinates = polygon.getPath().getArray().map(coord => ({
            lat: coord.lat(),
            lng: coord.lng()
        }));
        zoneForm.value.coordinates = coordinates;

        // Listen for path changes
        google.maps.event.addListener(polygon.getPath(), 'set_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'insert_at', updateCoordinates);

        function updateCoordinates() {
            const newCoordinates = polygon.getPath().getArray().map(coord => ({
                lat: coord.lat(),
                lng: coord.lng()
            }));
            zoneForm.value.coordinates = newCoordinates;
        }
    });
};

const startDrawing = () => {
    if (drawingManager.value) {
        drawingManager.value.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        isDrawing.value = true;
    }
};

const cancelDrawing = () => {
    if (drawingManager.value) {
        drawingManager.value.setDrawingMode(null);
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
            currentPolygon.value = null;
        }
        isDrawing.value = false;
        zoneForm.value.coordinates = [];
    }
};

const editZone = (zone) => {
    editingZone.value = zone;
    zoneForm.value = { ...zone };
    showAddZoneDialog.value = true;

    // Wait for map to be ready
    nextTick(() => {
        if (zone.coordinates && map.value) {
            // Clear existing polygon
            if (currentPolygon.value) {
                currentPolygon.value.setMap(null);
            }

            // Create new polygon with existing coordinates
            currentPolygon.value = new google.maps.Polygon({
                paths: zone.coordinates,
                fillColor: '#42A5F5',
                fillOpacity: 0.3,
                strokeWeight: 2,
                strokeColor: '#1976D2',
                editable: true
            });

            currentPolygon.value.setMap(map.value);

            // Fit map bounds to polygon
            const bounds = new google.maps.LatLngBounds();
            zone.coordinates.forEach(coord => {
                bounds.extend(new google.maps.LatLng(coord.lat, coord.lng));
            });
            map.value.fitBounds(bounds);

            // Listen for path changes
            google.maps.event.addListener(currentPolygon.value.getPath(), 'set_at', () => {
                const newCoordinates = currentPolygon.value.getPath().getArray().map(coord => ({
                    lat: coord.lat(),
                    lng: coord.lng()
                }));
                zoneForm.value.coordinates = newCoordinates;
            });
        }
    });
};

// Watch for dialog visibility to initialize map
watch(showAddZoneDialog, (newValue) => {
    if (newValue) {
        nextTick(() => {
            initMap();
        });
    } else {
        // Clean up when dialog closes
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
            currentPolygon.value = null;
        }
        if (drawingManager.value) {
            drawingManager.value.setMap(null);
        }
    }
});

const confirmDeleteZone = (zone) => {
    editingZone.value = zone;
    showDeleteDialog.value = true;
};

const deleteZone = async () => {
    try {
        await axios.delete(`/api/zones/${editingZone.value.id}`);
        showDeleteDialog.value = false;
        fetchZones();
    } catch (error) {
        console.error('Error deleting zone:', error);
    }
};

const saveZone = async () => {
    try {
        if (editingZone.value) {
            await axios.put(`/api/zones/${editingZone.value.id}`, zoneForm.value);
        } else {
            await axios.post('/api/zones', zoneForm.value);
        }
        showAddZoneDialog.value = false;
        fetchZones();
        resetForm();
    } catch (error) {
        console.error('Error saving zone:', error);
    }
};

const resetForm = () => {
    zoneForm.value = {
        name: '',
        delivery_charge_per_km: 0,
        base_delivery_charge: 0,
        minimum_delivery_charge: 0,
        description: '',
        coordinates: [],
        status: 'active'
    };
    editingZone.value = null;
    if (currentPolygon.value) {
        currentPolygon.value.setMap(null);
        currentPolygon.value = null;
    }
};

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

// Notification Settings
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

// Email Settings
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

onMounted(() => {
    fetchCompanyDetails();
    fetchZones();
    fetchPaymentSettings();
    fetchPages();
    fetchNotificationAndEmailSettings();
});
</script>