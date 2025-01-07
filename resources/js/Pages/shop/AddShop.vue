<template>

  <Head title="Add Shop" />
  <Toast />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Shop</h2>
        <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center"
          :href="route('shop.list')">
          <i class="pi pi-arrow-left mr-2"></i>
          Back to Shops
        </a>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Progress Steps -->
          <div class="border-b border-gray-200">
            <div class="p-4">
              <Steps :model="steps" :activeIndex="currentStep" />
            </div>
          </div>

          <div class="p-8">
            <!-- Shop Information Section -->
            <div v-show="currentStep === 0">
              <h3 class="text-lg font-semibold mb-6">Shop Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-1">Shop Name</label>
                    <InputText id="shop_name" v-model="shopForm.shop_name" class="w-full"
                      :class="{ 'p-invalid': errors.shop_name }" />
                    <small class="text-red-500" v-if="errors.shop_name">{{ errors.shop_name }}</small>
                  </div>

                  <div>
                    <label for="shop_type" class="block text-sm font-medium text-gray-700 mb-1">Shop Type</label>
                    <Dropdown id="shop_type" v-model="shopForm.shop_type" :options="shoptype" optionLabel="name"
                      optionValue="code" placeholder="Select Shop Type" class="w-full"
                      :class="{ 'p-invalid': errors.shop_type }" />
                    <small class="text-red-500" v-if="errors.shop_type">{{ errors.shop_type }}</small>
                  </div>

                  <div>
                    <label for="zone" class="block text-sm font-medium text-gray-700 mb-1">Delivery Zone</label>
                    <Dropdown id="zone" v-model="shopForm.zone_id" :options="zones" optionLabel="name" optionValue="id"
                      placeholder="Select Zone" class="w-full" :class="{ 'p-invalid': errors.zone_id }"
                      @change="onZoneChange" />
                    <small class="text-red-500" v-if="errors.zone_id">{{ errors.zone_id }}</small>
                  </div>

                  <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Shop Mobile</label>
                    <InputText id="mobile" v-model="shopForm.mobile" class="w-full"
                      :class="{ 'p-invalid': errors.mobile }" />
                    <small class="text-red-500" v-if="errors.mobile">{{ errors.mobile }}</small>
                  </div>
                </div>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                      <FileUpload mode="basic" name="shop_image" accept="image/*" :maxFileSize="1000000"
                        chooseLabel="Choose Image" class="p-button-outlined" @select="onFileSelect" />
                      <small class="text-gray-500 block mt-2">Max size: 1MB. Supported formats: JPG, PNG</small>
                    </div>
                  </div>

                  <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <Textarea id="address" v-model="shopForm.address" rows="3" class="w-full"
                      :class="{ 'p-invalid': errors.address }" />
                    <small class="text-red-500" v-if="errors.address">{{ errors.address }}</small>
                  </div>

                  <!-- Map for Location Selection -->
                  <div class="h-[300px] relative">
                    <div ref="mapRef" class="w-full h-full rounded-lg"></div>
                    <div v-if="selectedZone" class="absolute top-4 right-4 bg-white p-2 rounded shadow-md">
                      <span class="text-sm font-medium">Selected Zone: {{ selectedZone.name }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                  <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                  <InputText id="latitude" v-model="shopForm.latitude" class="w-full" readonly
                    :class="{ 'p-invalid': errors.latitude }" />
                  <small class="text-red-500" v-if="errors.latitude">{{ errors.latitude }}</small>
                </div>
                <div>
                  <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                  <InputText id="longitude" v-model="shopForm.longitude" class="w-full" readonly
                    :class="{ 'p-invalid': errors.longitude }" />
                  <small class="text-red-500" v-if="errors.longitude">{{ errors.longitude }}</small>
                </div>
              </div>
            </div>

            <!-- Vendor Information Section -->
            <div v-show="currentStep === 1">
              <h3 class="text-lg font-semibold mb-6">Vendor Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vendor Name</label>
                  <InputText v-model="name" id="name" required class="w-full" :class="{ 'p-invalid': errors.name }" />
                  <small class="text-red-500" v-if="errors.name">{{ errors.name }}</small>
                </div>

                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <InputText v-model="email" id="email" type="email" required class="w-full"
                    :class="{ 'p-invalid': errors.email }" />
                  <small class="text-red-500" v-if="errors.email">{{ errors.email }}</small>
                </div>

                <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                  <Password v-model="password" id="password" required toggleMask class="w-full"
                    :class="{ 'p-invalid': errors.password }" />
                  <small class="text-red-500" v-if="errors.password">{{ errors.password }}</small>
                </div>

                <div>
                  <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                    Password</label>
                  <Password v-model="confirmPassword" id="confirmPassword" required toggleMask class="w-full"
                    :class="{ 'p-invalid': errors.confirmPassword }" />
                  <small class="text-red-500" v-if="errors.confirmPassword">{{ errors.confirmPassword }}</small>
                </div>

                <div>
                  <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                  <InputText v-model="mobile" id="mobile" required class="w-full"
                    :class="{ 'p-invalid': errors.mobile }" />
                  <small class="text-red-500" v-if="errors.mobile">{{ errors.mobile }}</small>
                </div>

                <div>
                  <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                  <InputNumber v-model="age" id="age" class="w-full" :class="{ 'p-invalid': errors.age }" />
                  <small class="text-red-500" v-if="errors.age">{{ errors.age }}</small>
                </div>

                <div>
                  <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                  <Dropdown v-model="gender" :options="genderOptions" optionLabel="label" optionValue="value"
                    id="gender" required class="w-full" :class="{ 'p-invalid': errors.gender }" />
                  <small class="text-red-500" v-if="errors.gender">{{ errors.gender }}</small>
                </div>

                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                  <Dropdown v-model="status" :options="statusOptions" optionLabel="label" optionValue="value"
                    id="status" required class="w-full" :class="{ 'p-invalid': errors.status }" />
                  <small class="text-red-500" v-if="errors.status">{{ errors.status }}</small>
                </div>
              </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8 pt-6 border-t">
              <Button type="button" label="Back" icon="pi pi-arrow-left" class="p-button-secondary" @click="prevStep"
                v-if="currentStep > 0" />
              <div class="ml-auto">
                <Button type="button" label="Next" icon="pi pi-arrow-right" iconPos="right" @click="nextStep"
                  v-if="currentStep < steps.length - 1" class="mr-2" />
                <Button type="submit" label="Submit" icon="pi pi-check" severity="success" :loading="isSubmitting"
                  @click="submitForm" v-if="currentStep === steps.length - 1" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import Steps from 'primevue/steps';
import Password from 'primevue/password';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import { useToast } from "primevue/usetoast";

const toast = useToast();
const currentStep = ref(0);
const errors = ref({});
const isSubmitting = ref(false);

const steps = [
  { label: 'Shop Information', icon: 'pi pi-home' },
  { label: 'Vendor Details', icon: 'pi pi-user' }
];

// Initialize the shop form data using useForm
const shopForm = useForm({
  shop_name: '',
  address: '',
  mobile: '',
  latitude: '',
  longitude: '',
  shop_type: '',
  zone_id: '',
  image: null
});

const shoptype = ref([
  { name: 'Food Shop', code: 1 },
  { name: 'Grocery Shop', code: 2 },
]);

// Handle file selection
const onFileSelect = (event) => {
  shopForm.image = event.files[0]; // Store the selected file
};

// Form data and submission state
const name = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const age = ref(null);
const gender = ref("male");
const mobile = ref(""); // This is now used for user mobile, not shop
const status = ref("active");
const role = ref("vendor");
const vendorId = ref("");
const errorMessage = ref("");

// Gender and Status options
const genderOptions = [
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' }
];

const statusOptions = [
  { label: 'Active', value: '1' },
  { label: 'Inactive', value: '0' }
];

const submitForm = async () => {
  errorMessage.value = "";
  isSubmitting.value = true;

  if (password.value !== confirmPassword.value) {
    errorMessage.value = "Passwords do not match.";
    isSubmitting.value = false;
    return;
  }

  try {
    // First register the vendor
    const vendorResponse = await axios.post("/api/register", {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirmPassword.value,
      age: age.value,
      gender: gender.value,
      mobile: mobile.value,
      status: status.value,
      role: role.value,
      zone_id: shopForm.zone_id
    });

    console.log("Vendor Registration Response:", vendorResponse);
    const vendorId = vendorResponse.data.user.id;

    // Prepare shop form data
    const formData = new FormData();
    formData.append('shop_name', shopForm.shop_name);
    formData.append('vendor_id', vendorId);
    formData.append('address', shopForm.address);
    formData.append('mobile', shopForm.mobile);
    formData.append('latitude', shopForm.latitude);
    formData.append('longitude', shopForm.longitude);
    formData.append('shop_type', shopForm.shop_type);
    formData.append('zone_id', shopForm.zone_id);
    formData.append('status', 1); // Active by default

    // Append the image file if selected
    if (shopForm.image) {
      formData.append('image', shopForm.image);
    }

    // Submit the shop data
    const shopResponse = await axios.post(route('shop.store'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    console.log("Shop Creation Response:", shopResponse);

    // Show success message
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Shop and vendor created successfully',
      life: 3000
    });

    // Reset all forms
    resetForms();

    // Redirect to shop list
    window.location.href = route('shoplist');

  } catch (error) {
    console.error("Error:", error);
    let errorMsg = "An unexpected error occurred.";

    if (error.response && error.response.data.errors) {
      errorMsg = Object.values(error.response.data.errors).join(", ");
    } else if (error.response && error.response.data.message) {
      errorMsg = error.response.data.message;
    }

    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: errorMsg,
      life: 5000
    });

    errorMessage.value = errorMsg;
  } finally {
    isSubmitting.value = false;
  }
};

const resetForms = () => {
  // Reset vendor form
  name.value = "";
  email.value = "";
  password.value = "";
  confirmPassword.value = "";
  age.value = null;
  mobile.value = "";
  status.value = "active";
  gender.value = "male";

  // Reset shop form
  shopForm.reset();

  // Clear map markers and polygons
  if (marker.value) {
    marker.value.setMap(null);
    marker.value = null;
  }
  if (zonePolygon.value) {
    zonePolygon.value.setMap(null);
    zonePolygon.value = null;
  }
  selectedZone.value = null;
};

const nextStep = () => {
  if (currentStep.value < steps.length - 1) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

// Add these new refs
const mapRef = ref(null);
const map = ref(null);
const marker = ref(null);
const zones = ref([]);
const selectedZone = ref(null);
const zonePolygon = ref(null);

// Fetch zones when component mounts
const fetchZones = async () => {
  try {
    const response = await axios.get('/api/zones');
    zones.value = response.data.zones;
  } catch (error) {
    console.error('Error fetching zones:', error);
  }
};

// Initialize map
const initMap = () => {
  if (!mapRef.value) return;

  // Create map instance with default center at Deoghar, Jharkhand
  map.value = new google.maps.Map(mapRef.value, {
    center: { lat: 24.4814, lng: 86.6947 },
    zoom: 13,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    streetViewControl: false,
    mapTypeControl: true,
    zoomControl: true,
  });

  // Add click listener to map
  google.maps.event.addListener(map.value, 'click', (event) => {
    const clickedLocation = event.latLng;

    if (!selectedZone.value) {
      toast.add({
        severity: 'warn',
        summary: 'No Zone Selected',
        detail: 'Please select a delivery zone first',
        life: 3000
      });
      return;
    }

    // Convert coordinates to proper format
    const point = {
      lat: clickedLocation.lat(),
      lng: clickedLocation.lng()
    };

    // Check if point is in polygon
    const polygonCoords = selectedZone.value.coordinates.map(coord =>
      new google.maps.LatLng(coord.lat, coord.lng)
    );

    const polygon = new google.maps.Polygon({
      paths: polygonCoords
    });

    if (google.maps.geometry.poly.containsLocation(clickedLocation, polygon)) {
      placeMarker(clickedLocation);
      updateCoordinates(clickedLocation);
    } else {
      toast.add({
        severity: 'warn',
        summary: 'Invalid Location',
        detail: 'Please select a location within the selected zone',
        life: 3000
      });
    }
  });
};

// Place marker on map
const placeMarker = (location) => {
  // Remove existing marker if any
  if (marker.value) {
    marker.value.setMap(null);
  }

  // Create new marker
  marker.value = new google.maps.Marker({
    position: location,
    map: map.value,
    draggable: true,
    animation: google.maps.Animation.DROP
  });

  // Update coordinates immediately
  updateCoordinates(location);

  // Add drag end listener
  google.maps.event.addListener(marker.value, 'dragend', (event) => {
    const newPosition = event.latLng;

    // Check if new position is within zone
    const polygonCoords = selectedZone.value.coordinates.map(coord =>
      new google.maps.LatLng(coord.lat, coord.lng)
    );

    const polygon = new google.maps.Polygon({
      paths: polygonCoords
    });

    if (google.maps.geometry.poly.containsLocation(newPosition, polygon)) {
      updateCoordinates(newPosition);
    } else {
      toast.add({
        severity: 'warn',
        summary: 'Invalid Location',
        detail: 'Please keep the marker within the selected zone',
        life: 3000
      });
      marker.value.setPosition(location);
    }
  });
};

// Update form coordinates
const updateCoordinates = (location) => {
  if (location) {
    shopForm.latitude = location.lat().toFixed(6);
    shopForm.longitude = location.lng().toFixed(6);
  }
};

// Handle zone selection
const onZoneChange = () => {
  const zone = zones.value.find(z => z.id === shopForm.zone_id);
  selectedZone.value = zone;

  if (zone && zone.coordinates) {
    // Clear existing polygon
    if (zonePolygon.value) {
      zonePolygon.value.setMap(null);
    }

    // Clear existing marker
    if (marker.value) {
      marker.value.setMap(null);
      marker.value = null;
    }

    // Reset coordinates
    shopForm.latitude = '';
    shopForm.longitude = '';

    // Convert coordinates to LatLng objects
    const polygonCoords = zone.coordinates.map(coord =>
      new google.maps.LatLng(coord.lat, coord.lng)
    );

    // Draw new polygon
    zonePolygon.value = new google.maps.Polygon({
      paths: polygonCoords,
      strokeColor: '#42A5F5',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#42A5F5',
      fillOpacity: 0.35,
      clickable: false
    });

    zonePolygon.value.setMap(map.value);

    // Fit map bounds to zone
    const bounds = new google.maps.LatLngBounds();
    polygonCoords.forEach(coord => bounds.extend(coord));
    map.value.fitBounds(bounds);

    // Add a small padding to the bounds
    map.value.setZoom(map.value.getZoom() - 0.5);
  }
};

// Initialize map and fetch zones when component mounts
onMounted(() => {
  fetchZones();
  nextTick(() => {
    initMap();
  });
});

</script>

<style scoped>
:deep(.p-steps) {
  @apply bg-gray-50;
}

:deep(.p-steps .p-steps-item.p-highlight .p-steps-number) {
  @apply bg-emerald-500;
}

:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-password input),
:deep(.p-textarea) {
  @apply w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500;
}

:deep(.p-fileupload-choose) {
  @apply w-full;
}

:deep(.p-button) {
  @apply transition-all duration-200;
}

:deep(.p-button:not(:disabled):hover) {
  @apply transform scale-105;
}

.error-message {
  @apply text-red-500 text-sm mt-1;
}
</style>
