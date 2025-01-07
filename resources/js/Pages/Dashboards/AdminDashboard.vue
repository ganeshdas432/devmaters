<template>
    <div class="container mx-auto p-4">
        <!-- Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-5 gap-6">
            <div v-for="card in cards" :key="card.id"
                class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="p-6">
                    <!-- Card Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ card.title }}</h3>
                        <div :class="[
                            'w-12 h-12 rounded-full flex items-center justify-center',
                            getIconBackground(card.id)
                        ]">
                            <i :class="['pi text-2xl', card.icon, getIconColor(card.id)]"></i>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="flex items-end justify-between">
                        <div>
                            <span class="text-3xl font-bold text-gray-700">{{ card.content }}</span>
                            <p class="text-sm text-gray-500 mt-1">Total {{ card.title.toLowerCase() }}</p>
                        </div>
                        <div class="flex items-center text-sm">
                            <span :class="[
                                'px-2.5 py-0.5 rounded-full text-xs font-medium',
                                getTrendClass(card.trend)
                            ]">
                                <i class="pi pi-arrow-up mr-1" v-if="card.trend > 0"></i>
                                <i class="pi pi-arrow-down mr-1" v-if="card.trend < 0"></i>
                                {{ Math.abs(card.trend) }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-4 gap-6 mt-16">
            <div v-for="card in additionalCards" :key="card.id"
                class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                <div class="p-6">
                    <!-- Card Header -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <i :class="['pi text-2xl mr-2', card.icon, getIconColor(card.id)]"></i>
                            <h3 class="text-lg font-semibold text-gray-800">{{ card.title }}</h3>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-600">10</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Card from "primevue/card";
import Chart from "primevue/chart";
import axios from 'axios';

export default {
    components: {
        Card,
        Chart
    },
    data() {
        return {
            cards: [
                {
                    id: 1,
                    title: 'Products',
                    content: '0',
                    icon: 'pi-box',
                    trend: 12 // Positive trend
                },
                {
                    id: 2,
                    title: 'Orders',
                    content: '0',
                    icon: 'pi-list',
                    trend: -5 // Negative trend
                },
                {
                    id: 3,
                    title: 'Customers',
                    content: '0',
                    icon: 'pi-users',
                    trend: 8
                },
                {
                    id: 4,
                    title: 'Vendors',
                    content: '0',
                    icon: 'pi-briefcase',
                    trend: 15
                },
                {
                    id: 5,
                    title: 'Riders',
                    content: '0',
                    icon: 'pi-truck',
                    trend: 3
                }
            ],
            additionalCards: [
                {
                    id: 6,
                    title: 'Unsigned Orders',
                    content: '0',
                    icon: 'pi-exclamation-circle',
                    trend: 0
                },
                {
                    id: 7,
                    title: 'Delivery Boy Assigned',
                    content: '0',
                    icon: 'pi-user-plus',
                    trend: 0
                },
                {
                    id: 8,
                    title: 'Packing',
                    content: '0',
                    icon: 'pi-box',
                    trend: 0
                },
                {
                    id: 9,
                    title: 'Out for Delivery',
                    content: '0',
                    icon: 'pi-truck',
                    trend: 0
                },
                {
                    id: 10,
                    title: 'Delivered',
                    content: '0',
                    icon: 'pi-check-circle',
                    trend: 0
                },
                {
                    id: 11,
                    title: 'Canceled',
                    content: '0',
                    icon: 'pi-times-circle',
                    trend: 0
                },
                {
                    id: 12,
                    title: 'Refunded',
                    content: '0',
                    icon: 'pi-undo',
                    trend: 0
                },
                {
                    id: 13,
                    title: 'Payment Failed',
                    content: '0',
                    icon: 'pi-exclamation-triangle',
                    trend: 0
                }
            ],
            // ... rest of your data
        };
    },
    methods: {
        getIconBackground(id) {
            const backgrounds = {
                1: 'bg-blue-100',
                2: 'bg-green-100',
                3: 'bg-purple-100',
                4: 'bg-orange-100',
                5: 'bg-pink-100',
                6: 'bg-red-100',
                7: 'bg-yellow-100',
                8: 'bg-blue-100',
                9: 'bg-green-100',
                10: 'bg-purple-100',
                11: 'bg-orange-100',
                12: 'bg-pink-100',
                13: 'bg-red-100'
            };
            return backgrounds[id] || 'bg-gray-100';
        },
        getIconColor(id) {
            const colors = {
                1: 'text-blue-600',
                2: 'text-green-600',
                3: 'text-purple-600',
                4: 'text-orange-600',
                5: 'text-pink-600',
                6: 'text-red-600',
                7: 'text-yellow-600',
                8: 'text-blue-600',
                9: 'text-green-600',
                10: 'text-purple-600',
                11: 'text-orange-600',
                12: 'text-pink-600',
                13: 'text-red-600'
            };
            return colors[id] || 'text-gray-600';
        },
        getTrendClass(trend) {
            return trend > 0
                ? 'bg-green-100 text-green-800'
                : 'bg-red-100 text-red-800';
        },
        async fetchCounts() {
            try {
                const response = await axios.get('/api/counts');
                const { customer_count, vendor_count, order_count, rider_count, product_count, unsigned_orders_count, delivery_boy_assigned_count, packing_count, out_for_delivery_count, delivered_count, canceled_count, refunded_count, payment_failed_count } = response.data;

                // Update cards with fetched data
                this.cards = this.cards.map(card => {
                    let content = '0';
                    switch (card.id) {
                        case 1: content = product_count; break;
                        case 2: content = order_count; break;
                        case 3: content = customer_count; break;
                        case 4: content = vendor_count; break;
                        case 5: content = rider_count; break;
                    }
                    return { ...card, content };
                });

                // Update additional cards with fetched data
                this.additionalCards = this.additionalCards.map(card => {
                    let content = '0';
                    switch (card.id) {
                        case 6: content = unsigned_orders_count; break;
                        case 7: content = delivery_boy_assigned_count; break;
                        case 8: content = packing_count; break;
                        case 9: content = out_for_delivery_count; break;
                        case 10: content = delivered_count; break;
                        case 11: content = canceled_count; break;
                        case 12: content = refunded_count; break;
                        case 13: content = payment_failed_count; break;
                    }
                    return { ...card, content };
                });
            } catch (error) {
                console.error("Failed to fetch data:", error);
            }
        }
    },
    mounted() {
        this.fetchCounts();
    }
};
</script>

<style scoped>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Custom hover effect for icons */
.card:hover .pi {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}
</style>
