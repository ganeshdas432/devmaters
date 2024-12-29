<template>
    <div id="map" class="w-full h-96"></div>
</template>

<script setup>
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet-routing-machine';
import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';
import { onMounted, ref } from 'vue';

// Import marker icons manually
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

const props = defineProps({
    pickupLatitude: Number,
    pickupLongitude: Number,
    dropLatitude: Number,
    dropLongitude: Number
});

const map = ref(null);

onMounted(() => {
    // Set default icons path manually
    L.Icon.Default.mergeOptions({
        iconUrl: markerIcon,
        iconRetinaUrl: markerIcon2x,
        shadowUrl: markerShadow
    });

    if (props.pickupLatitude && props.pickupLongitude && props.dropLatitude && props.dropLongitude) {
        initializeMap();
    }
});

function initializeMap() {
    // Initialize the map centered on the pickup location
    map.value = L.map('map').setView([props.pickupLatitude, props.pickupLongitude], 13);

    // Add base layer using OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map.value);

    // Add markers for pickup and drop locations
    const pickupMarker = L.marker([props.pickupLatitude, props.pickupLongitude]).addTo(map.value);
    pickupMarker.bindPopup("Pickup Location").openPopup();

    const dropMarker = L.marker([props.dropLatitude, props.dropLongitude]).addTo(map.value);
    dropMarker.bindPopup("Drop Location");

    // Display route between pickup and drop locations
    L.Routing.control({
        waypoints: [
            L.latLng(props.pickupLatitude, props.pickupLongitude),
            L.latLng(props.dropLatitude, props.dropLongitude)
        ],
        routeWhileDragging: true,
        createMarker: () => null // Disable extra routing markers
    }).addTo(map.value);
}

</script>

<style scoped>
#map {
    width: 100%;
    height: 400px;
}
</style>
