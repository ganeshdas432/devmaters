<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from '@inertiajs/vue3';
import Menubar from 'primevue/menubar';
import axios from 'axios';

// Props
defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// Navigation menu
const items = ref([
    { label: 'Home', route: 'home' },
    { label: 'About Us', route: 'about' },
    { label: 'Terms and Conditions', route: 'terms' },
    { label: 'Privacy Policies', route: 'privacy' },
    { label: 'Contact', route: 'contact' }
]);



// Fetching data by title (e.g., "About Us")
const pageContent = ref(null);
const loading = ref(true);
const error = ref(null);

// Function to get data by title
const fetchPageContent = async (title) => {
    try {
        const response = await axios.get(`/api/pages/title/${title}`);
        pageContent.value = response.data;
    } catch (err) {
        error.value = "Page not found or error occurred.";
    } finally {
        loading.value = false;
    }
};

// Fetch "About Us" page data on component mount
onMounted(() => {
    fetchPageContent('Terms and Conditions');
});


</script>

<template>
    <Head title="Terms and Conditions" />
    
    <!-- Menubar component -->
    <div class="card">
        <Menubar :model="items">
            <template #start>
                <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-8">
                    <!-- SVG content for logo (unchanged) -->
                    <path d="M25.87 18.05L23.16 17.45L25.27 20.46V29.78L32.49 23.76V13.53L29.18 14.73L25.87 18.04V18.05ZM25.27 35.49L29.18 31.58V27.67L25.27 30.98V35.49ZM20.16 17.14H20.03H20.17H20.16ZM30.1 5.19L34.89 4.81L33.08 12.33L24.1 15.67L30.08 5.2L30.1 5.19ZM5.72 14.74L2.41 13.54V23.77L9.63 29.79V20.47L11.74 17.46L9.03 18.06L5.72 14.75V14.74ZM9.63 30.98L5.72 27.67V31.58L9.63 35.49V30.98ZM4.8 5.2L10.78 15.67L1.81 12.33L0 4.81L4.79 5.19L4.8 5.2ZM24.37 21.05V34.59L22.56 37.29L20.46 39.4H14.44L12.34 37.29L10.53 34.59V21.05L12.42 18.23L17.45 26.8L22.48 18.23L24.37 21.05ZM22.85 0L22.57 0.69L17.45 13.08L12.33 0.69L12.05 0H22.85Z" fill="var(--p-primary-color)" />
                    <path d="M30.69 4.21L24.37 4.81L22.57 0.69L22.86 0H26.48L30.69 4.21ZM23.75 5.67L22.66 3.08L18.05 14.24V17.14H19.7H20.03H20.16H20.2L24.1 15.7L30.11 5.19L23.75 5.67ZM4.21002 4.21L10.53 4.81L12.33 0.69L12.05 0H8.43002L4.22002 4.21H4.21002ZM21.9 17.4L20.6 18.2H14.3L13 17.4L12.4 18.2L12.42 18.23L17.45 26.8L22.48 18.23L22.5 18.2L21.9 17.4ZM4.79002 5.19L10.8 15.7L14.7 17.14H14.74H15.2H16.85V14.24L12.24 3.09L11.15 5.68L4.79002 5.2V5.19Z" fill="var(--p-text-color)" />
                </svg>
            </template>
            <template #item="{ item }">
                <a v-if="item.route" v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="route(item.route)">
                    <span class="ml-2">{{ item.label }}</span>
                </a>
                <a v-else v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="item.url">
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </template>
            <template #end>
                <div v-if="canLogin" class="sm:top-0 sm:right-0 p-6 text-end">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                            Register
                        </Link>
                    </template>
                </div>
            </template>
        </Menubar>
    </div>

    <!-- About Us content -->
    <div v-if="loading" class="text-center mt-10">Loading...</div>
    <div v-if="error" class="text-center mt-10 text-red-500">{{ error }}</div>
    <div v-if="pageContent && !loading" class="card m-4 p-0 flex-container">
        <div class="w-full lg:mx-auto lg:max-w-6xl">
            <div class="text-base mt-2 font-medium">
                <h1 class="text-3xl sm:text-4xl md:text-5xl text-left">{{ pageContent.title }}</h1>
                <div class="mb-8"></div>

                <!-- Render HTML content dynamically -->
                <div v-html="pageContent.content"></div> <!-- This will render the HTML content -->
            </div>
        </div>
    </div>

</template>

<style scoped>
/* Your existing styles here */
</style>
