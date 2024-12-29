<script setup>
import { ref, onMounted } from "vue";

import { Head, Link } from '@inertiajs/vue3';
import Menubar from 'primevue/menubar';
import Carousel from "primevue/carousel";
import Card from "primevue/card";
import Image from "primevue/image";
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';
import Button from 'primevue/button';

import axios from 'axios';


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


const company = ref({});

// Fetch company details
const fetchCompanyDetails = async () => {
    try {
        const response = await axios.get('/api/details');
        company.value = response.data;
    } catch (error) {
        console.error('Error fetching company details:', error);
    }
};

// Lifecycle hook to fetch company details on component mount
onMounted(() => {
    fetchCompanyDetails();
});



const items = ref([
    {
        label: 'Home',
        route: 'home'
    },
    {
        label: 'About Us',
        route: 'about'
    },
    {
        label: 'Terms and Conditions',
        route: 'terms'
    },
    {
        label: 'Privacy Policies',
        route: 'privacy'
    },
    {
        label: 'Contact',
        route: 'contact'
    },

]);

const products = ref([
    {
        id: 1,
        name: 'Black Watch',
        price: 79.99,
        image: 'https://www.slideteam.net/wp/wp-content/uploads/2022/09/Banner_design_268b.png',
        inventoryStatus: 'INSTOCK',
    },
    {
        id: 2,
        name: 'Blue Band',
        price: 39.99,
        image: 'https://www.railkafe.com/assets/top-banner-image-new-DIf96RMj.webp',
        inventoryStatus: 'LOWSTOCK',
    },

]);

const responsiveOptions = ref([
    {
        breakpoint: '1400px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '1199px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '575px',
        numVisible: 1,
        numScroll: 1
    }
]);

const getSeverity = (status) => {
    switch (status) {
        case 'INSTOCK':
            return 'success';
        case 'LOWSTOCK':
            return 'warn';
        case 'OUTOFSTOCK':
            return 'danger';
        default:
            return null;
    }
};
</script>

<template>

    <Head title="Welcome" />


    <div class="card">
        <Menubar :model="items">
            <template #start>
                <img v-if="company.company_logo_square" :src="'storage/' + company.company_logo_square"
                    alt="Company Square Logo" class="w-10 h-10 object-cover" />
            </template>
            <template #item="{ item }">
                <!-- Internal Links -->
                <a v-if="item.route" v-ripple
                    class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                    :href="route(item.route)" :target="item.target">
                    <span class="ml-2">{{ item.label }}</span>
                    <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
                </a>

                <!-- External Links -->
                <a v-else v-ripple
                    class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                    :href="item.url" :target="item.target">
                    <span class="ml-2">{{ item.label }}</span>
                    <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
                </a>
            </template>
            <template #end>
                <div v-if="canLogin" class="sm:top-0 sm:right-0 p-6 text-end">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    Dashboard</Link>

                    <template v-else>
                        <Link :href="route('login')"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Log in</Link>

                        <Link v-if="canRegister" :href="route('register')"
                            class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Register</Link>
                    </template>
                </div>
            </template>
        </Menubar>
    </div>


    <Carousel :value="products" :numVisible="1" :numScroll="1" :responsiveOptions="responsiveOptions" circular
        :autoplayInterval="3000">
        <template #item="slotProps">
            <div class=" ">
                <div class="mb-4 mt-4">
                    <div class="relative mx-auto">
                        <img :src="slotProps.data.image" :alt="slotProps.data.name" class="w-full rounded"
                            style="height: 620px; object-fit: fill;" />
                        <Tag :value="slotProps.data.inventoryStatus"
                            :severity="getSeverity(slotProps.data.inventoryStatus)" class="absolute"
                            style="left:5px; top:5px" />
                    </div>
                </div>

            </div>
        </template>
    </Carousel>




    <div class="card-grid m-4">
        <Card style="width: 100%; overflow: hidden" class="bg-red">
            <template #header>
                <img alt="user header" src="/storage/assets/images/fd.png" style="height: 150px;object-fit: cover;" />
            </template>
            <template #title>Food Delivery</template>
            <template #content>
                <p class="m-0">
                    "Craving something delicious? Let us bring it to your door!
                    Fresh flavors, fast delivery – a treat for every taste.
                    Order now and enjoy a meal without the hassle.
                    Your favorite food is just a tap away!"
                </p>
            </template>
            <template #footer>
                <div class="flex gap-4 mt-1">
                    <Button label="Cancel" severity="secondary" outlined class="w-full" />
                    <Button label="Save" class="w-full" />
                </div>
            </template>
        </Card>

        <Card style="width: 100%; overflow: hidden" class="bg-blue">
            <template #header>
                <img alt="user header" src="/storage/assets/images/gr.png" style="height: 150px;object-fit: cover;" />
            </template>
            <template #title>Grocery Delivery</template>
            <template #content>
                <p class="m-0">
                    "Fresh groceries, delivered straight to your door!
                    From pantry staples to farm-fresh produce, we’ve got it all.
                    Shop online and skip the checkout lines.
                    Convenient, fast, and always reliable – your groceries are just a click away!"
                </p>
            </template>
            <template #footer>
                <div class="flex gap-4 mt-1">
                    <Button label="Cancel" severity="secondary" outlined class="w-full" />
                    <Button label="Save" class="w-full" />
                </div>
            </template>
        </Card>

        <Card style="width: 100%; overflow: hidden" class="bg-green">
            <template #header>
                <img alt="user header" src="/storage/assets/images/pt.png" style="height: 150px;object-fit: cover;" />
            </template>
            <template #title>Porter Service</template>
            <template #content>
                <p class="m-0">
                    "Need a helping hand? Our porter service is here for you!
                    From luggage to heavy loads, we’ll handle it with care.
                    Efficient, reliable, and always on time.
                    Let us lighten your load – book our porter service today!"
                </p>
            </template>
            <template #footer>
                <div class="flex gap-4 mt-1">
                    <Button label="Cancel" severity="secondary" outlined class="w-full" />
                    <Button label="Save" class="w-full" />
                </div>
            </template>
        </Card>

        <Card style="width: 100%; overflow: hidden" class="bg-yellow">
            <template #header>
                <img alt="user header" src="/storage/assets/images/rd.png" style="height: 150px;object-fit: cover;" />
            </template>
            <template #title>Ride Booking</template>
            <template #content>
                <p class="m-0">
                    "Need a ride? We’ve got you covered!
                    Quick, safe, and hassle-free transportation at your fingertips.
                    Book your ride now and hit the road in minutes.
                    Wherever you're headed, we’ll get you there with ease!"
                </p>
            </template>
            <template #footer>
                <div class="flex gap-4 mt-1">
                    <Button label="Cancel" severity="secondary" outlined class="w-full" />
                    <Button label="Save" class="w-full" />
                </div>
            </template>
        </Card>
    </div>



    <div class="bg-surface-0 dark:bg-surface-950 px-6 py-20 md:px-12 lg:px-20 text-center">
        <div class="mb-4 font-bold text-3xl">
            <span class="text-surface-900 dark:text-surface-0">One Product, </span>
            <span class="text-primary-600 dark:text-primary-400">Many Solutions</span>
        </div>
        <div class="text-surface-700 dark:text-surface-0/70 mb-[3rem]">Ac turpis egestas maecenas pharetra convallis
            posuere
            morbi leo urna.</div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-x-3.5 gap-y-6">
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-desktop text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">Built for Developers</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Duis aute
                    irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
            </div>
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-lock text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">End-to-End Encryption</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Risus nec
                    feugiat in fermentum posuere urna nec. Posuere sollicitudin aliquam ultrices sagittis.</p>
            </div>
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-check-circle text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">Easy to Use</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Ornare
                    suspendisse sed nisi lacus sed viverra tellus. Neque volutpat ac tincidunt vitae semper.</p>
            </div>
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-globe text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">Fast & Global Support</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Fermentum
                    et
                    sollicitudin ac orci phasellus egestas tellus rutrum tellus.</p>
            </div>
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-github text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">Open Source</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Nec
                    tincidunt
                    praesent semper feugiat. Sed adipiscing diam donec adipiscing tristique risus nec feugiat.</p>
            </div>
            <div class="w-full p-4">
                <span
                    class="w-16 h-16 mb-6 rounded-lg flex items-center justify-center mx-auto bg-surface-0 dark:bg-surface-800 shadow-[0px_2px_6px_0px_rgba(0,0,0,0.12),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_4px_10px_0px_rgba(0,0,0,0.03)]">
                    <i class="pi pi-shield text-2xl lg:text-3xl text-primary-500 dark:text-primary-400" />
                </span>
                <div class="text-surface-900 dark:text-surface-0 text-xl mb-2 font-medium">Trusted Security</div>
                <p class="text-surface-700 dark:text-surface-0/70 leading-normal max-w-sm mx-auto text-center">Mattis
                    rhoncus urna neque viverra justo nec ultrices. Id cursus metus aliquam eleifend.</p>
            </div>
        </div>
    </div>


    <div class="mb-36">
    </div>


    <div class="card m-4  p-0 flex-container">
        <div class="text-content p-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl text-left rtl:text-right">
                What we do ?
            </h2>
            <p>
                we are dedicated to making your life easier by offering a comprehensive range of services designed to
                meet
                all your needs. From convenient grocery delivery and reliable food services to professional porter
                assistance and seamless ride booking, our mission is to provide exceptional solutions that save you time
                and
                effort.
            </p>
        </div>
        <div class="image-content flex items-center justify-center">
            <picture
                class="image card-image flex sm:rounded-3xl mb-5 md:mb-0 overflow-hidden transitionFix items-center justify-center"
                style="width: 400px;"><img src="https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg"
                    alt="About_us_updt_e1e14ed645" loading="lazy"></picture>
        </div>
    </div>

    <div class="mb-36">
    </div>


    <div class="card m-4 p-0 flex-container">

        <div class="image-content flex items-center justify-center">
            <picture style="width: 400px;"
                class="image card-image flex sm:rounded-3xl mb-5 md:mb-0 overflow-hidden transitionFix items-center justify-center">
                <img src="https://careem-public-web-media.imgix.net/Engineering_at_careem_5df00df225.webp"
                    alt="Engineering_at_careem_5df00df225" loading="lazy">
            </picture>
        </div>
        <div class="text-content p-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl text-left rtl:text-right">
                Why our app better ?
            </h2>
            <div class="font-medium text-base leading-6 mb-6">
                <p>your all-in-one solution for convenience! Designed with you in mind, our app brings a world of
                    services
                    right to your fingertips. Whether you need groceries delivered, a quick ride, delicious food, or
                    porter
                    assistance, our user-friendly interface makes it easy to access everything you need in just a few
                    taps.
                </p>
            </div>

        </div>



    </div>

    <div class="mb-36">
    </div>

    <div class="bg-surface-0 dark:bg-surface-950 px-6 py-20 md:px-12 lg:px-20">
        <div class="text-surface-700 dark:text-surface-100 text-center">
            <div class="text-primary font-bold mb-4"><i class="pi pi-discord" />&nbsp;POWERED BY DISCORD</div>
            <div class="text-surface-900 dark:text-surface-0 font-bold text-5xl mb-4">Join Our Design Community</div>
            <div class="text-surface-700 dark:text-surface-100 text-2xl mb-8">Lorem ipsum dolor sit, amet consectetur
                adipisicing elit. Velit numquam eligendi quos.</div>
            <Button label="Join Now" icon="pi pi-discord" raised rounded
                class="font-bold px-8 py-4 whitespace-nowrap" />
        </div>
    </div>

    <div class="flex items-center justify-center  text-right">
        All Rights Resurved @2024

    </div>




</template>

<style scoped>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}


.card-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* 4 columns for larger screens */
    gap: 1rem;
    /* Add space between cards */
}

@media (max-width: 768px) {
    .card-grid {
        grid-template-columns: 1fr;
        /* Single column on smaller screens */
    }
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* 4 columns for larger screens */
    gap: 1rem;
    /* Add space between cards */
}

.flex-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    /* Spacing between text and image */
}

.text-content {
    flex: 1;
}

.image-content {
    flex: 1;
}

.bg-red {
    background-color: #ff9f98;
    /* Red */
    color: rgb(24, 23, 23);
}

.bg-blue {
    background-color: #b0d8f8;
    /* Blue */
    color: rgb(30, 27, 27);
}

.bg-green {
    background-color: #b3fab6;
    /* Green */
    color: rgb(39, 36, 36);
}

.bg-yellow {
    background-color: #fff9bf;
    /* Yellow */
    color: rgb(43, 38, 38);
}

@media (max-width: 768px) {
    .card-grid {
        grid-template-columns: 1fr;
        /* Single column on smaller screens */
    }

    .flex-container {
        flex-direction: column;
        /* Stack text and image vertically on mobile */
    }

    .image-content {
        margin-top: 1rem;
        /* Add some space between text and image on mobile */
    }
}
</style>
