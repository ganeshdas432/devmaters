<template>
    <Head title="Delivery Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Delivery Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Main Content Grid with Map and Info Cards -->
                <div v-if="delivery" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Delivery Information Cards on the Left -->
                    <div class="space-y-6">
                        <!-- Pickup & Drop Information -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Pickup & Drop Information</h3>
                            <p><strong>Pickup Address:</strong> {{ delivery.pickup_address }}</p>
                            <p><strong>Drop Address:</strong> {{ delivery.drop_address }}</p>
                            <p><strong>Pickup Contact:</strong> {{ delivery.pickup_contact }}</p>
                            <p><strong>Drop Contact:</strong> {{ delivery.drop_contact }}</p>
                            <p><strong>Distance:</strong> {{ delivery.distance }}</p>
                        </div>

                        <!-- Delivery Information -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                            <p><strong>Delivery Type:</strong> {{ delivery.delivery_type }}</p>
                            <p><strong>Delivery Details:</strong> {{ delivery.delivery_details }}</p>
                            <p><strong>Order Value:</strong> {{ delivery.order_value }}</p>
                            <p><strong>Order Weight:</strong> {{ delivery.order_weight }}</p>
                            <p><strong>Order Payment Status:</strong> {{ delivery.order_payment_status }}</p>
                            <p><strong>Order Payment Type:</strong> {{ delivery.order_payment_type }}</p>
                            <p><strong>Delivery Cost:</strong> {{ delivery.delivery_cost }}</p>
                            <p><strong>Tips:</strong> {{ delivery.tips }}</p>
                            <p><strong>Rider Earning:</strong> {{ delivery.rider_earning }}</p>
                        </div>

                        <!-- Rider Information -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Rider Information</h3>
                            <p><strong>Name:</strong> {{ delivery.rider.name }}</p>
                            <p><strong>Email:</strong> {{ delivery.rider.email }}</p>
                            <p><strong>Mobile:</strong> {{ delivery.rider.mobile }}</p>
                        </div>

                        <!-- Customer Information -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                            <p><strong>Name:</strong> {{ delivery.customer.name }}</p>
                            <p><strong>Email:</strong> {{ delivery.customer.email }}</p>
                            <p><strong>Mobile:</strong> {{ delivery.customer.mobile }}</p>
                        </div>
                    </div>

                    <!-- Map Section on the Right -->
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Route Map</h3>
                        <DeliveryMap
                            :pickupLatitude="parseFloat(delivery.pickup_latitude)"
                            :pickupLongitude="parseFloat(delivery.pickup_longitude)"
                            :dropLatitude="parseFloat(delivery.drop_latitude)"
                            :dropLongitude="parseFloat(delivery.drop_longitude)"
                        />
                    </div>
                </div>

                <!-- Loading message if delivery data is missing -->
                <div v-else class="text-center">
                    <p>Loading delivery details...</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeliveryMap from '@/Components/DeliveryMap.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';

const delivery = ref(null);
const deliveryId = usePage().props.deliveryId || null;

onMounted(async () => {
    if (!deliveryId) {
        console.error('deliveryId is missing');
        return;
    }

    try {
        const response = await axios.get(`/api/delivery/${deliveryId}`);
        delivery.value = response.data.delivery;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
