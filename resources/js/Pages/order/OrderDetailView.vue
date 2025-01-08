<template>

    <Head title="Invoice" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoice</h2>
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-wrap">

                    <!-- Left Column: Invoice Content -->
                    <div id="invoice-content" class="w-full md:w-2/3 p-4">
                        <button @click="printInvoice" class="bg-blue-500 text-white px-4 py-2 mb-2 rounded">
                            Print Invoice
                        </button>

                        <div class="invoice-container p-8 bg-white rounded-lg shadow-lg">

                            <!-- Invoice Header -->
                            <div v-if="order" class="flex justify-between mb-8">
                                <div>
                                    <h1 class="text-2xl font-semibold">Order ID #{{ order.id }}</h1>
                                    <div class="flex items-center text-gray-500">
                                        <!-- Icon -->
                                        <i class="pi pi-calendar-clock"
                                            style="font-size: 1rem; margin-right: 0.5rem;"></i>
                                        <!-- Date -->
                                        <p class="text-gray-500 mb-0">Date: {{
                                            formatDate(order.order_status?.created_at) ||
                                            'N/A' }}</p>
                                    </div>
                                    <div class="flex items-center text-gray-500">
                                        <!-- Icon -->
                                        <i class="pi pi-shop" style="font-size: 1rem; margin-right: 0.5rem;"></i>
                                        <!-- Date -->
                                        <p class="text-gray-500 mb-0">Shop Name Is Here</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <h2 class="text-lg font-semibold">{{ order.customer?.name || 'Unknown' }}</h2>
                                    <p class="text-gray-500">{{ order.customer?.email || 'N/A' }}</p>
                                    <p class="text-gray-500">{{ order.order_status?.title || 'N/A' }}</p>
                                    <p class="text-gray-500">{{ order.delivery?.order_payment_type || 'N/A' }}</p>
                                    <p class="text-gray-500">{{ order.delivery?.order_payment_status || 'N/A' }}</p>
                                    <Tag :value="order.delivery?.order_payment_status || 'N/A'" />

                                </div>
                            </div>
                            <div v-else>
                                <p>Loading...</p>
                            </div>

                            <!-- Order Details Table -->
                            <div v-if="order?.order_details?.length" class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Order Details</h3>
                                <DataTable :value="order.order_details || []" :autoLayout="true"
                                    tableStyle="width: 100%;">

                                    <Column field="product_name" header="Product Name"></Column>
                                    <Column field="quantity" header="Quantity"></Column>
                                    <Column field="product_price" header="Price"></Column>
                                </DataTable>


                            </div>


                            <!-- Summary Section -->
                            <div v-if="order" class="text-right mt-8">
                                <p class="text-lg"><strong>Subtotal:</strong> ${{ order.subtotal }}</p>
                                <p class="text-lg"><strong>Delivery Cost:</strong> ${{ order.delivery?.delivery_cost ||
                                    '0.00'
                                    }}</p>
                                <p class="text-lg"><strong>Tips:</strong> ${{ order.delivery?.tips || '0.00' }}</p>
                                <hr class="my-4">
                                <p class="text-xl font-semibold"><strong>Grand Total:</strong> ${{ calculateGrandTotal
                                    }}</p>
                            </div>

                            <!-- Footer Note -->
                            <div v-if="order" class="mt-12 text-center text-gray-500 text-sm">
                                <p>Thank you for your purchase!</p>
                                <p>If you have any questions about this invoice, please contact us.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Status, Delivery Boy Assignment, and Address Info -->
                    <div class="w-full md:w-1/3 p-4 space-y-6">
                        <!-- Order Status Card -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Order Status</h3>
                            <select v-model="selectedStatus" class="w-full border rounded px-4 py-2 mb-4">
                                <option v-for="status in statuses" :key="status.id" :value="status.id">
                                    {{ status.title }}
                                </option>
                            </select>
                            <button @click="updateOrderStatus" class="w-full bg-blue-500 text-white py-2 rounded">
                                Save
                            </button>
                        </div>

                        <!-- Delivery Boy Selection Card -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Select Delivery Boy</h3>
                            <select v-model="selectedRider" class="w-full border rounded px-4 py-2 mb-4">
                                <option v-for="rider in riders" :key="rider.id" :value="rider.id">
                                    {{ rider.name }}
                                </option>
                            </select>
                            <button @click="assignDeliveryBoy" class="w-full bg-green-500 text-white py-2 rounded">
                                Assign
                            </button>
                        </div>

                        <!-- Delivery Information -->
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <div v-if="order?.delivery" class="mb-4">
                                <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                                <div>
                                    <p><strong>Pickup Address:</strong> {{ order.delivery.pickup_address || 'N/A' }}</p>
                                    <p><strong>Drop Address:</strong> {{ order.delivery.drop_address || 'N/A' }}</p>
                                    <p><strong>Contact:</strong> {{ order.delivery.drop_contact || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';



// Reactive variables
const order = ref(null);
const selectedStatus = ref(null);
const selectedRider = ref(null);
const loading = ref(false);

// Fetch orderId from props
const orderId = usePage().props.orderId || null;

// Fetch statuses and riders
const statuses = ref([]);
const riders = ref([]);

const formatDate = (dateString) => {
    if (!dateString) return '';

    const date = new Date(dateString);

    const options = {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    };

    return date.toLocaleString('en-GB', options).replace(',', '');
};

// Computed property for grand total
const calculateGrandTotal = computed(() => {
    if (!order.value) return 0;
    return (
        parseFloat(order.value.subtotal) +
        parseFloat(order.value.delivery?.delivery_cost || 0) +
        parseFloat(order.value.delivery?.tips || 0)
    );
});

// Lifecycle method to fetch data
onMounted(async () => {
    if (!orderId) {
        console.error('orderId is missing');
        return;
    }

    try {
        const [orderResponse, statusResponse, riderResponse] = await Promise.all([
            axios.get(`/api/order/${orderId}`),
            axios.get('/api/statuses'),
            axios.get('/api/riders')
        ]);

        order.value = orderResponse.data.order;
        statuses.value = statusResponse.data.statuses;
        riders.value = riderResponse.data.riders;
        selectedStatus.value = order.value.order_status.id;
        selectedRider.value = order.value.delivery?.rider_id || null;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Method to update order status
const updateOrderStatus = async () => {
    loading.value = true;
    try {
        await axios.post(`/api/order/status/${orderId}`, { status: selectedStatus.value });
        alert('Order status updated successfully!');
    } catch (error) {
        console.error('Error updating order status:', error);
    } finally {
        loading.value = false;
    }
};

// Method to assign delivery boy
const assignDeliveryBoy = async () => {
    loading.value = true;
    try {
        await axios.post(`/api/order/assign/${orderId}`, { rider_id: selectedRider.value });
        alert('Delivery boy assigned successfully!');
    } catch (error) {
        console.error('Error assigning delivery boy:', error);
    } finally {
        loading.value = false;
    }
};

// Method to print invoice
const printInvoice = () => {
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Invoice</title></head><body>');
    printWindow.document.write(document.getElementById('invoice-content').outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
};
</script>
