<template>
    <div class="container mx-auto p-4">
        <!-- Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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

                <!-- Card Footer -->
                <div class="px-6 py-3 bg-gray-50 rounded-b-xl border-t">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        View details
                        <i class="pi pi-arrow-right ml-2"></i>
                    </a>
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
                5: 'bg-pink-100'
            };
            return backgrounds[id] || 'bg-gray-100';
        },
        getIconColor(id) {
            const colors = {
                1: 'text-blue-600',
                2: 'text-green-600',
                3: 'text-purple-600',
                4: 'text-orange-600',
                5: 'text-pink-600'
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
                const { customer_count, vendor_count, order_count, rider_count, product_count } = response.data;

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
