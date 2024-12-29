<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <Drawer v-model:visible="showingSidebar" header="Menu">
      <Sidebar />
    </Drawer>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-100">
      <!-- Navbar -->
      <nav class="bg-white border-b border-gray-200 shadow">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <!-- Left Section -->
            <div class="flex items-center space-x-6">
              <!-- Hamburger Button for Small Screens -->
              <button @click="showingSidebar = !showingSidebar"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden"
                aria-label="Toggle Sidebar">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path :class="{ hidden: showingSidebar, 'inline-flex': !showingSidebar }" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{ hidden: !showingSidebar, 'inline-flex': showingSidebar }" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <!-- Logo -->
              <div class="flex items-center">
                <Link :href="route('dashboard')" class="flex items-center text-lg font-semibold text-gray-800">
                <img v-if="company.company_logo_square" :src="'storage/' + company.company_logo_square"
                  alt="Company Logo" class="w-10 h-10 rounded-full mr-3" />
                <span>{{ company.company_name || 'Loading...' }}</span>
                </Link>
              </div>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-6">
              <!-- Profile Dropdown -->
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button type="button"
                    class="flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $page.props.auth.user.name }}
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                      fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                    </svg>
                  </button>
                </template>
                <template #content>
                  <DropdownLink :href="route('profile.edit')"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                      class="w-5 h-5 mr-3 text-indigo-500">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A15.978 15.978 0 0112 18c3.866 0 7.407-1.386 10.121-3.804M12 18V6M5.121 17.804L12 6m0 0l6.879 11.804" />
                    </svg>
                    Profile
                  </DropdownLink>

                  <!-- Logout Button -->
                  <DropdownLink as="button" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                    @click.prevent="logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                      class="w-5 h-5 mr-3 text-red-500">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-12v1m-6 6h.01" />
                    </svg>
                    Log Out
                  </DropdownLink>

                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Content -->
      <main class="p-4">
        <div class="flex flex-row">
          <!-- Static Sidebar for Desktop -->
          <div class="hidden lg:block basis-1/6">
            <Sidebar class="m-4" />
          </div>

          <!-- Main Content -->
          <div class="flex-1">
            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Sidebar from './Sidebar.vue';
import Dropdown from '@/Components/Dropdown.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';  // Import usePage to access $page
import Drawer from 'primevue/drawer';
import axios from 'axios';

const showingSidebar = ref(false);
const company = ref({});

// Accessing $page properties using usePage
const { props } = usePage();
const csrfToken = props.csrf_token;  // Extract CSRF token from $page.props

// Fetch company details on mount
const fetchCompanyDetails = async () => {
  try {
    const response = await axios.get('/api/details');
    company.value = response.data || {
      company_name: 'Default Company',
      company_logo_square: ''
    };
  } catch (error) {
    console.error('Error fetching company details:', error);
  }
};

// Handle logout using axios
const logout = async () => {
  try {
    // Sending the POST request to logout
    await axios.post(route('logout'), {
      _token: csrfToken,  // Include the CSRF token in the request body
    });

    // After logout, you may want to redirect the user or perform other actions.
    window.location.href = '/login';  // Redirecting to the login page after logout.
  } catch (error) {
    console.error('Error logging out:', error);
  }
};

onMounted(() => {
  fetchCompanyDetails();
});
</script>
