<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from '@inertiajs/vue3';
import Menubar from 'primevue/menubar';
import Carousel from "primevue/carousel";
import Card from "primevue/card";
import Button from 'primevue/button';
import ScrollTop from 'primevue/scrolltop';
import Timeline from 'primevue/timeline';
import Badge from 'primevue/badge';
import axios from 'axios';
import Image from 'primevue/image';
import Divider from 'primevue/divider';
import Rating from 'primevue/rating';
import Tag from 'primevue/tag';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
});

const company = ref({});
const services = ref([
    {
        title: 'Food Delivery',
        description: 'Get your favorite food delivered to your doorstep',
        icon: 'pi pi-shopping-bag',
        image: '/storage/assets/images/fd.png',
        color: 'bg-red-100'
    },
    {
        title: 'Grocery Delivery',
        description: 'Fresh groceries delivered to you',
        icon: 'pi pi-shopping-cart',
        image: '/storage/assets/images/gr.png',
        color: 'bg-blue-100'
    },
    {
        title: 'Porter Service',
        description: 'Professional assistance for your moving needs',
        icon: 'pi pi-box',
        image: '/storage/assets/images/pt.png',
        color: 'bg-green-100'
    },
    {
        title: 'Ride Booking',
        description: 'Safe and reliable rides at your service',
        icon: 'pi pi-car',
        image: '/storage/assets/images/rd.png',
        color: 'bg-yellow-100'
    }
]);

const carouselItems = ref([
    {
        title: "Your One-Stop Delivery Solution",
        subtitle: "Fast & Reliable",
        description: "Experience seamless delivery services for food, groceries, and more. Available 24/7 for your convenience.",
        image: "https://shipsy.io/wp-content/uploads/2021/07/Blog-64.jpg",
        buttonText: "Get Started",
        buttonIcon: "pi pi-arrow-right"
    },
    {
        title: "Food Delivery Made Easy",
        subtitle: "Quick & Fresh",
        description: "From restaurants to your doorstep, enjoy your favorite meals while they're still hot.",
        image: "https://d3l9a8mvoa6cl8.cloudfront.net/wp-content/uploads/sites/3/2020/04/03173944/online-food-delivery-industry-min.jpg",
        buttonText: "Order Now",
        buttonIcon: "pi pi-shopping-cart"
    }
]);

// Image URLs constant
const IMAGES = {
    features: {
        main: '/storage/assets/images/features/delivery-app.jpg',
        app: 'https://www.softsuave.com/blog/wp-content/uploads/2020/07/Food-Delivery-App-Development-Services-997x1024.png',
        tracking: '/storage/assets/images/features/live-tracking.jpg',
        payment: '/storage/assets/images/features/secure-payment.jpg'
    },
    testimonials: {
        customer1: 'https://media.istockphoto.com/id/1682296067/photo/happy-studio-portrait-or-professional-man-real-estate-agent-or-asian-businessman-smile-for.jpg?s=612x612&w=0&k=20&c=9zbG2-9fl741fbTWw5fNgcEEe4ll-JegrGlQQ6m54rg=',
        customer2: 'https://media.istockphoto.com/id/1386479313/photo/happy-millennial-afro-american-business-woman-posing-isolated-on-white.jpg?s=612x612&w=0&k=20&c=8ssXDNTp1XAPan8Bg6mJRwG7EXHshFO5o0v9SIj96nY=',
        customer3: 'https://media.istockphoto.com/id/1300512215/photo/headshot-portrait-of-smiling-ethnic-businessman-in-office.jpg?s=612x612&w=0&k=20&c=QjebAlXBgee05B3rcLDAtOaMtmdLjtZ5Yg9IJoiy-VY=',
        customer4: 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-profiles/avatar-1.webp'
    },
    services: {
        food: '/storage/assets/images/services/food-delivery.jpg',
        grocery: '/storage/assets/images/services/grocery-delivery.jpg',
        porter: '/storage/assets/images/services/porter-service.jpg',
        ride: '/storage/assets/images/services/ride-booking.jpg'
    }
};

const features = ref([
    {
        icon: 'pi pi-clock',
        title: '24/7 Service',
        description: 'Round the clock support for all your needs',
    },
    {
        icon: 'pi pi-shield',
        title: 'Secure Payments',
        description: 'Safe and secure payment options',
    },
    {
        icon: 'pi pi-map-marker',
        title: 'Live Tracking',
        description: 'Real-time tracking of your orders',
    }
]);

const timelineEvents = ref([
    {
        status: 'Place Order',
        icon: 'pi pi-shopping-cart',
        color: '#4CAF50'
    },
    {
        status: 'Confirmation',
        icon: 'pi pi-check-circle',
        color: '#2196F3'
    },
    {
        status: 'On The Way',
        icon: 'pi pi-send',
        color: '#FF9800'
    },
    {
        status: 'Delivered',
        icon: 'pi pi-check',
        color: '#4CAF50'
    }
]);

// Enhanced menu items
const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home',
        route: 'home'
    },
    {
        label: 'Services',
        icon: 'pi pi-list',
        items: [
            { label: 'Food Delivery', icon: 'pi pi-shopping-bag' },
            { label: 'Grocery', icon: 'pi pi-shopping-cart' },
            { label: 'Ride Booking', icon: 'pi pi-car' },
            { label: 'Porter Service', icon: 'pi pi-box' }
        ]
    },
    {
        label: 'About',
        icon: 'pi pi-info-circle',
        route: 'about'
    },
    {
        label: 'Contact',
        icon: 'pi pi-envelope',
        route: 'contact'
    }
]);

// Statistics
const stats = ref([
    { value: '50K+', label: 'Happy Customers', icon: 'pi pi-users' },
    { value: '100+', label: 'Partner Stores', icon: 'pi pi-shopping-bag' },
    { value: '24/7', label: 'Customer Support', icon: 'pi pi-phone' },
    { value: '98%', label: 'Satisfaction Rate', icon: 'pi pi-check-circle' }
]);

// Testimonials
const testimonials = ref([
    {
        name: 'John Doe',
        role: 'Regular Customer',
        comment: 'Best delivery service I\'ve ever used. Fast and reliable!',
        rating: 5,
        image: IMAGES.testimonials.customer1
    },
    {
        name: 'Jane Smith',
        role: 'Business Owner',
        comment: 'Their porter service has made my logistics so much easier. Highly recommended!',
        rating: 5,
        image: IMAGES.testimonials.customer2
    },
    {
        name: 'Mike Wilson',
        role: 'Food Lover',
        comment: 'Great food delivery service. Always on time and food arrives hot.',
        rating: 4,
        image: IMAGES.testimonials.customer3
    },
    {
        name: 'Sarah Johnson',
        role: 'Working Professional',
        comment: 'The ride booking service is excellent. Clean cars and professional drivers.',
        rating: 5,
        image: IMAGES.testimonials.customer4
    }
]);

// Fetch company details
const fetchCompanyDetails = async () => {
    try {
        const response = await axios.get('/api/details');
        company.value = response.data;
    } catch (error) {
        console.error('Error fetching company details:', error);
    }
};

const carousel = ref(null);
const currentSlide = ref(0);

// Add this to handle slide changes
const onSlideChange = (index) => {
    currentSlide.value = index;
};

onMounted(() => {
    fetchCompanyDetails();

    // Start autoplay
    const autoplayInterval = setInterval(() => {
        if (carousel.value) {
            const nextIndex = (currentSlide.value + 1) % carouselItems.value.length;
            carousel.value.goTo(nextIndex);
            currentSlide.value = nextIndex;
        }
    }, 5000);

    // Clean up on component unmount
    return () => clearInterval(autoplayInterval);

    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in-section').forEach((element) => {
        observer.observe(element);
    });
});
</script>

<template>
    <div class="landing-page">
        <!-- Navbar -->
        <nav class="fixed w-full z-50 transition-all duration-300" :class="{ 'bg-white/90 shadow-md': scrolled }">
            <Menubar :model="items" class="border-none backdrop-blur-md">
                <template #start>
                    <div class="flex items-center gap-2">
                        <img v-if="company.company_logo_square" :src="'storage/' + company.company_logo_square"
                            alt="Company Logo" class="w-10 h-10 object-cover" />
                        <span class="font-semibold text-xl text-gray-800">{{ company.company_name }}</span>
                    </div>
                </template>
                <template #end>
                    <div v-if="canLogin" class="flex items-center gap-4">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="font-medium text-gray-600 hover:text-gray-900">
                        Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="font-medium text-gray-600 hover:text-gray-900">
                            Log in
                            </Link>
                            <Link v-if="canRegister" :href="route('register')"
                                class="font-medium text-white bg-emerald-500 hover:bg-emerald-600 px-4 py-2 rounded-lg">
                            Register
                            </Link>
                        </template>
                    </div>
                </template>
            </Menubar>
        </nav>

        <!-- Hero Section with Fullscreen Carousel -->
        <section class="relative h-screen">
            <Carousel :value="carouselItems" :numVisible="1" :numScroll="1" :autoplayInterval="4000" :circular="true"
                :showNavigators="false" class="hero-carousel h-full" :pt="{
                    root: 'h-full',
                    content: 'h-full',
                    container: 'h-full',
                    item: 'h-full'
                }">
                <template #item="slotProps">
                    <div class="relative h-full w-full">
                        <div class="absolute inset-0 bg-black/40 z-10"></div>
                        <img :src="slotProps.data.image" class="w-full h-full object-cover animate-scale"
                            :alt="slotProps.data.title" />
                        <div class="absolute inset-0 z-20 flex items-center">
                            <div class="container mx-auto px-4">
                                <div class="max-w-4xl animate-slideUp">
                                    <span
                                        class="inline-block px-4 py-2 bg-emerald-500/90 text-white rounded-lg text-sm font-medium mb-4">
                                        {{ slotProps.data.subtitle }}
                                    </span>
                                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                                        {{ slotProps.data.title }}
                                    </h1>
                                    <p class="text-xl text-white/90 mb-8 max-w-2xl">
                                        {{ slotProps.data.description }}
                                    </p>
                                    <div class="flex flex-wrap gap-4">
                                        <Button :label="slotProps.data.buttonText" :icon="slotProps.data.buttonIcon"
                                            iconPos="right" class="bg-emerald-500 hover:bg-emerald-600 border-none"
                                            size="large" rounded />
                                        <Button label="Learn More"
                                            class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white border-white"
                                            size="large" rounded outlined />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Carousel>

            <!-- Custom Indicators -->
            <div class="absolute bottom-8 left-0 right-0 z-30 flex justify-center gap-2">
                <button v-for="(item, index) in carouselItems" :key="index" @click="carousel?.goTo(index)"
                    class="w-12 h-1.5 rounded-full transition-all duration-300" :class="[
                        currentSlide === index
                            ? 'bg-white'
                            : 'bg-white/40 hover:bg-white/60'
                    ]"></button>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 bg-emerald-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div v-for="stat in stats" :key="stat.label"
                        class="text-center p-6 rounded-xl bg-white shadow-sm hover-scale">
                        <i :class="[stat.icon, 'text-4xl text-emerald-500 mb-4']"></i>
                        <div class="stat-number text-3xl font-bold text-gray-900 mb-2">
                            {{ stat.value }}
                        </div>
                        <div class="text-gray-600">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-3xl mx-auto mb-16 fade-in-section">
                    <h2 class="text-4xl font-bold mb-4">Our Services</h2>
                    <p class="text-gray-600">Experience the convenience of our comprehensive delivery solutions</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="service in services" :key="service.title"
                        class="service-card bg-white rounded-xl shadow-sm hover-lift">
                        <div class="relative">
                            <img :src="service.image" :alt="service.title"
                                class="w-full h-48 object-cover rounded-t-xl" />
                            <div :class="[service.color, 'absolute top-4 left-4 p-3 rounded-full']">
                                <i :class="[service.icon, 'text-2xl']"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ service.title }}</h3>
                            <p class="text-gray-600">{{ service.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section with Animation -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1">
                        <h2 class="text-4xl font-bold mb-6">Why Choose Us?</h2>
                        <div class="space-y-8">
                            <div v-for="feature in features" :key="feature.title"
                                class="flex items-start gap-4 feature-item">
                                <div
                                    class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                    <i :class="[feature.icon, 'text-2xl text-emerald-600']"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold mb-2">{{ feature.title }}</h3>
                                    <p class="text-gray-600 mb-3">{{ feature.description }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2">
                        <div class="relative floating">
                            <img :src="IMAGES.features.app" alt="App Interface"
                                class="rounded-xl shadow-lg w-full hover-scale" />
                            <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-lg shadow-xl pulse">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <i class="pi pi-star-fill text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">4.9/5</div>
                                        <div class="text-sm text-gray-500">Customer Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-16 fade-in-section">
                    What Our Customers Say
                </h2>
                <Carousel :value="testimonials" :numVisible="3" :numScroll="1">
                    <template #item="slotProps">
                        <div class="p-4">
                            <div class="hover-lift bg-white p-6 rounded-xl shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <img :src="slotProps.data.image" :alt="slotProps.data.name"
                                        class="w-16 h-16 rounded-full object-cover border-4 border-emerald-50" />
                                    <div>
                                        <div class="font-semibold text-lg">{{ slotProps.data.name }}</div>
                                        <div class="text-sm text-gray-500">{{ slotProps.data.role }}</div>
                                    </div>
                                </div>
                                <Rating v-model="slotProps.data.rating" readonly :cancel="false" class="mb-3" />
                                <p class="text-gray-600 italic">
                                    "{{ slotProps.data.comment }}"
                                </p>
                            </div>
                        </div>
                    </template>
                </Carousel>
            </div>
        </section>

        <!-- Enhanced Footer -->
        <footer class="bg-gray-900 text-white pt-20 pb-6">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">About Us</h4>
                        <p class="text-gray-400">Your one-stop solution for all delivery needs</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Services</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><i class="pi pi-phone mr-2"></i> +1234567890</li>
                            <li><i class="pi pi-envelope mr-2"></i> info@example.com</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white"><i class="pi pi-facebook"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="pi pi-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="pi pi-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                    <p>© {{ new Date().getFullYear() }} All Rights Reserved</p>
                </div>
            </div>
        </footer>

        <ScrollTop />
    </div>
</template>

<style scoped>
.hero-carousel {
    @apply overflow-hidden;
}

.hero-carousel :deep(.p-carousel-container) {
    @apply h-full;
}

.animate-scale {
    animation: scale 8s linear infinite;
}

.animate-slideUp {
    animation: slideUp 1s ease-out forwards;
}

@keyframes scale {
    from {
        transform: scale(1);
    }

    to {
        transform: scale(1.1);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom carousel indicators */
.hero-carousel :deep(.p-carousel-indicators) {
    @apply hidden;
}

/* Ensure full height for all carousel elements */
.hero-carousel :deep(.p-carousel-items-container) {
    @apply h-full;
}

.hero-carousel :deep(.p-carousel-items-content) {
    @apply h-full;
}

.hero-carousel :deep(.p-carousel-item) {
    @apply h-full;
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Timeline customization */
:deep(.p-timeline-event-opposite) {
    display: none;
}

:deep(.p-timeline-event-content) {
    padding: 0 1rem;
}

.feature-item {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
}

.feature-item:nth-child(1) {
    animation-delay: 0.2s;
}

.feature-item:nth-child(2) {
    animation-delay: 0.4s;
}

.feature-item:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Add smooth transitions */
.landing-page {
    @apply antialiased;
}

/* Enhanced carousel styling */
.hero-carousel :deep(.p-carousel-indicators) {
    @apply bottom-8;
}

.hero-carousel :deep(.p-carousel-indicator button) {
    @apply w-3 h-3 rounded-full transition-all duration-300 bg-white/50 hover:bg-white;
}

.hero-carousel :deep(.p-carousel-indicator.p-highlight button) {
    @apply w-12 bg-white;
}

/* Enhanced testimonial cards */
.testimonial-card {
    @apply transform transition-all duration-300;
}

.testimonial-card:hover {
    @apply -translate-y-1;
}

/* Enhanced feature images */
.feature-image {
    @apply transition-all duration-300 filter;
}

.feature-image:hover {
    @apply brightness-105;
}

/* Fade In animations for different sections */
.fade-in-section {
    opacity: 0;
    transform: translateY(20px);
    visibility: hidden;
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    will-change: opacity, visibility;
}

.fade-in-section.is-visible {
    opacity: 1;
    transform: none;
    visibility: visible;
}

/* Stagger animations for service cards */
.service-card {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.6s ease-out forwards;
}

.service-card:nth-child(1) {
    animation-delay: 0.2s;
}

.service-card:nth-child(2) {
    animation-delay: 0.4s;
}

.service-card:nth-child(3) {
    animation-delay: 0.6s;
}

.service-card:nth-child(4) {
    animation-delay: 0.8s;
}

/* Stats counter animation */
.stat-number {
    animation: countUp 2s ease-out forwards;
    display: inline-block;
}

/* Floating animation for features */
.floating {
    animation: floating 3s ease-in-out infinite;
}

/* Pulse animation for rating badge */
.pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Define animations */
@keyframes floating {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0px);
    }
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }

    50% {
        opacity: .9;
        transform: scale(1.05);
    }
}

@keyframes countUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Hover effects */
.hover-lift {
    transition: transform 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
}

.hover-scale {
    transition: transform 0.2s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}
</style>