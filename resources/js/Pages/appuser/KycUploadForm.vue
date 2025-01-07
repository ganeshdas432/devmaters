<template>

    <Head title="KYC Upload Form" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">KYC Upload Form</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm">
                            <div class="mb-4">
                                <label for="document_type" class="block text-sm font-medium text-gray-700">Document
                                    Type</label>
                                <select id="document_type" v-model="form.document_type" class="mt-1 block w-full"
                                    required>
                                    <option value="aadhar">Aadhar</option>
                                    <option value="pan">PAN</option>
                                    <option value="driving_licence">Driving Licence</option>
                                    <option value="trade_licence">Trade Licence</option>
                                    <option value="fssai_licence">FSSAI Licence</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="document_number" class="block text-sm font-medium text-gray-700">Document
                                    Number</label>
                                <input type="text" id="document_number" v-model="form.document_number"
                                    class="mt-1 block w-full" required />
                            </div>
                            <div class="mb-4">
                                <label for="front_image" class="block text-sm font-medium text-gray-700">Front
                                    Image</label>
                                <input type="file" id="front_image" @change="handleFileUpload('front_image', $event)"
                                    class="mt-1 block w-full" required />
                            </div>
                            <div class="mb-4">
                                <label for="back_image" class="block text-sm font-medium text-gray-700">Back
                                    Image</label>
                                <input type="file" id="back_image" @change="handleFileUpload('back_image', $event)"
                                    class="mt-1 block w-full" required />
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" v-model="form.status" class="mt-1 block w-full" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection
                                    Reason</label>
                                <input type="text" id="rejection_reason" v-model="form.rejection_reason"
                                    class="mt-1 block w-full" />
                            </div>
                            <div class="flex justify-end">
                                <Button type="submit" label="Submit" class="ml-4" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";
import axios from 'axios';

const { props } = usePage();
const kycType = ref(props.kycType);
const userId = ref(props.userId);

const toast = useToast();

const form = ref({
    document_type: '',
    document_number: '',
    front_image: null,
    back_image: null,
    status: 'pending',
    rejection_reason: ''
});

const handleFileUpload = (field, event) => {
    form.value[field] = event.target.files[0];
};

const submitForm = async () => {
    toast.add({ severity: 'success', summary: 'Success', detail: 'KYC document uploaded successfully', life: 3000 });

    const formData = new FormData();
    formData.append('user_id', userId.value);
    formData.append('kyc_type', kycType.value);
    formData.append('document_type', form.value.document_type);
    formData.append('document_number', form.value.document_number);
    formData.append('front_image', form.value.front_image);
    formData.append('back_image', form.value.back_image);
    formData.append('status', form.value.status);
    formData.append('rejection_reason', form.value.rejection_reason);

    axios.post('/api/kyc/upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(function (response) {
            console.log('KYC document uploaded:', response.data);
            toast.add({ severity: 'success', summary: 'Success', detail: 'KYC document uploaded successfully', life: 3000 });
            resetForm();
        })
        .catch(function (error) {
            console.error('Error uploading KYC document:', error);
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to upload KYC document', life: 3000 });
        });
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
