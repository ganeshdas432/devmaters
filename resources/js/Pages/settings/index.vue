<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <Toast />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Company Details</h2>
        </template>

        <div class="py-4">
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
                            <Tab value="9">Vehicles</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
                                <GeneralSettings />
                            </TabPanel>
                            <TabPanel value="1">
                                <OrderSettings />
                            </TabPanel>
                            <TabPanel value="2">
                                <RefundSettings />
                            </TabPanel>
                            <TabPanel value="3">
                                <!-- Stores tab content will go here -->
                            </TabPanel>
                            <TabPanel value="4">
                                <!-- Customers tab content will go here -->
                            </TabPanel>
                            <TabPanel value="5">
                                <PagesSettings />
                            </TabPanel>
                            <TabPanel value="6">
                                <PaymentSettings />
                            </TabPanel>
                            <TabPanel value="7">
                                <ZoneSettings />
                            </TabPanel>
                            <TabPanel value="8">
                                <NotificationSettings />
                            </TabPanel>
                            <TabPanel value="9">
                                <VehicleSettings />
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import GeneralSettings from './GeneralSettings/GeneralSettings.vue';
import OrderSettings from './OrderSettings/OrderSettings.vue';
import RefundSettings from './RefundSettings/RefundSettings.vue';
import PagesSettings from './PagesSettings/PagesSettings.vue';
import PaymentSettings from './PaymentSettings/PaymentSettings.vue';
import ZoneSettings from './ZoneSettings/ZoneSettings.vue';
import NotificationSettings from './NotificationSettings/NotificationSettings.vue';
import VehicleSettings from './VehicleSettings/VehicleSettings.vue';
</script>