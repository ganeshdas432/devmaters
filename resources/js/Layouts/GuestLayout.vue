<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const company = ref({
    company_name: 'Loading...',
    company_logo_square: null,
    company_logo_rectangular: null,
    company_description: 'Loading...'
});

const loading = ref(true);

const fetchCompanyDetails = async () => {
    try {
        const response = await axios.get('/api/details');
        company.value = {
            ...response.data,
            company_description: 'Manage your deliveries, track orders, and grow your business with our comprehensive admin panel.'
        };
    } catch (error) {
        console.error('Error fetching company details:', error);
        company.value = {
            company_name: 'Delivery Admin',
            company_description: 'Manage your deliveries, track orders, and grow your business with our comprehensive admin panel.',
            company_logo_rectangular: '/default-logo.png'
        };
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCompanyDetails();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Main Container -->
        <div class="flex min-h-screen">
            <!-- Left Section -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-emerald-600">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-emerald-600 opacity-90">
                    <div class="absolute inset-0"
                        style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center px-12 lg:px-24">
                    <!-- Logo -->
                    <div class="mb-8">
                        <img v-if="company.company_logo_rectangular"
                            :src="'storage/' + company.company_logo_rectangular" :alt="company.company_name"
                            class="h-12 w-auto" />
                        <h1 v-else class="text-3xl font-bold text-white">{{ company.company_name }}</h1>
                    </div>

                    <!-- Welcome Text -->
                    <h2 class="text-4xl font-bold text-white mb-6">
                        Welcome to Admin Panel
                    </h2>
                    <p class="text-lg text-white/90 max-w-md">
                        {{ company.company_description }}
                    </p>

                    <!-- Features List -->
                    <div class="mt-12 space-y-4">
                        <div class="flex items-center text-white/90">
                            <i class="pi pi-check-circle mr-3 text-emerald-300"></i>
                            <span>Real-time order tracking</span>
                        </div>
                        <div class="flex items-center text-white/90">
                            <i class="pi pi-check-circle mr-3 text-emerald-300"></i>
                            <span>Comprehensive analytics</span>
                        </div>
                        <div class="flex items-center text-white/90">
                            <i class="pi pi-check-circle mr-3 text-emerald-300"></i>
                            <span>Efficient delivery management</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex flex-col justify-center w-full lg:w-1/2 px-6 lg:px-12">
                <div class="w-full max-w-md mx-auto">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <img v-if="company.company_logo_rectangular"
                            :src="'storage/' + company.company_logo_rectangular" :alt="company.company_name"
                            class="h-12 mx-auto" />
                        <h1 v-else class="text-2xl font-bold text-gray-900">{{ company.company_name }}</h1>
                    </div>

                    <!-- Form Container -->
                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <slot />
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center text-sm text-gray-500">
                        <p>&copy; {{ new Date().getFullYear() }} {{ company.company_name }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Fade in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}
</style>
