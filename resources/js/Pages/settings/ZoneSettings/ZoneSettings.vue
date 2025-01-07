<template>
    <div class="p-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Delivery Zones</h3>
            <Button @click="showAddZoneDialog = true" icon="pi pi-plus" label="Add New Zone" severity="success" />
        </div>

        <!-- Zones DataTable -->
        <DataTable :value="zones" :loading="loading" stripedRows showGridlines class="p-datatable-sm">
            <Column field="name" header="Zone Name" sortable></Column>
            <Column field="delivery_charge_per_km" header="Charge per KM" sortable>
                <template #body="{ data }">
                    ₹{{ data.delivery_charge_per_km }}
                </template>
            </Column>
            <Column field="base_delivery_charge" header="Base Charge" sortable>
                <template #body="{ data }">
                    ₹{{ data.base_delivery_charge }}
                </template>
            </Column>
            <Column field="minimum_delivery_charge" header="Min. Charge" sortable>
                <template #body="{ data }">
                    ₹{{ data.minimum_delivery_charge }}
                </template>
            </Column>
            <Column field="status" header="Status" sortable>
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="data.status_badge" />
                </template>
            </Column>
            <Column header="Actions">
                <template #body="{ data }">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pencil" text rounded severity="info" @click="editZone(data)" />
                        <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteZone(data)" />
                    </div>
                </template>
            </Column>
        </DataTable>

        <!-- Add/Edit Zone Dialog -->
        <Dialog v-model:visible="showAddZoneDialog" :modal="true" :style="{ width: '80vw' }" :header="editingZone ? 'Edit Zone' : 'Add New Zone'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Zone Name</label>
                        <InputText v-model="zoneForm.name" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Delivery Charge per KM (₹)</label>
                        <InputNumber v-model="zoneForm.delivery_charge_per_km" mode="decimal" :minFractionDigits="2" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Base Delivery Charge (₹)</label>
                        <InputNumber v-model="zoneForm.base_delivery_charge" mode="decimal" :minFractionDigits="2" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Minimum Delivery Charge (₹)</label>
                        <InputNumber v-model="zoneForm.minimum_delivery_charge" mode="decimal" :minFractionDigits="2" class="w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <Textarea v-model="zoneForm.description" rows="3" class="w-full" />
                    </div>
                    <div v-if="editingZone">
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <Dropdown v-model="zoneForm.status" :options="['active', 'inactive']" class="w-full" />
                    </div>
                </div>

                <!-- Map for Drawing Zone -->
                <div class="h-[500px] relative">
                    <div ref="mapRef" class="w-full h-full rounded-lg"></div>
                    <div class="absolute top-4 right-4 bg-white p-2 rounded shadow-md">
                        <Button v-if="!isDrawing" icon="pi pi-pencil" label="Draw Zone" @click="startDrawing" />
                        <Button v-else icon="pi pi-times" label="Cancel" severity="danger" @click="cancelDrawing" />
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Cancel" severity="secondary" @click="showAddZoneDialog = false" />
                    <Button :label="editingZone ? 'Update' : 'Save'" severity="success" @click="saveZone" />
                </div>
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:visible="showDeleteDialog" modal header="Confirm Delete" :style="{ width: '450px' }">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Are you sure you want to delete this zone?</span>
            </div>
            <template #footer>
                <Button label="No" severity="secondary" @click="showDeleteDialog = false" />
                <Button label="Yes" severity="danger" @click="deleteZone" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";

// Zone management state
const zones = ref([]);
const loading = ref(false);
const showAddZoneDialog = ref(false);
const showDeleteDialog = ref(false);
const editingZone = ref(null);
const map = ref(null);
const mapRef = ref(null);
const drawingManager = ref(null);
const currentPolygon = ref(null);
const isDrawing = ref(false);

const mapCenter = ref({ lat: 24.6557, lng: 87.9548 });

const zoneForm = ref({
    name: '',
    delivery_charge_per_km: 0,
    base_delivery_charge: 0,
    minimum_delivery_charge: 0,
    description: '',
    coordinates: [],
    status: 'active'
});

// Fetch zones
const fetchZones = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/zones');
        zones.value = response.data.zones;
    } catch (error) {
        console.error('Error fetching zones:', error);
    } finally {
        loading.value = false;
    }
};

// Initialize map
const initMap = () => {
    if (!mapRef.value) return;

    // Create map instance
    map.value = new google.maps.Map(mapRef.value, {
        center: mapCenter.value,
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
    });

    // Create drawing manager
    drawingManager.value = new google.maps.drawing.DrawingManager({
        drawingMode: null,
        drawingControl: false,
        polygonOptions: {
            fillColor: '#42A5F5',
            fillOpacity: 0.3,
            strokeWeight: 2,
            strokeColor: '#1976D2',
            clickable: true,
            editable: true,
            zIndex: 1
        }
    });

    drawingManager.value.setMap(map.value);

    // Listen for polygon completion
    google.maps.event.addListener(drawingManager.value, 'polygoncomplete', (polygon) => {
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
        }
        currentPolygon.value = polygon;
        isDrawing.value = false;

        // Get coordinates
        const coordinates = polygon.getPath().getArray().map(coord => ({
            lat: coord.lat(),
            lng: coord.lng()
        }));
        zoneForm.value.coordinates = coordinates;

        // Listen for path changes
        google.maps.event.addListener(polygon.getPath(), 'set_at', updateCoordinates);
        google.maps.event.addListener(polygon.getPath(), 'insert_at', updateCoordinates);

        function updateCoordinates() {
            const newCoordinates = polygon.getPath().getArray().map(coord => ({
                lat: coord.lat(),
                lng: coord.lng()
            }));
            zoneForm.value.coordinates = newCoordinates;
        }
    });
};

const startDrawing = () => {
    if (drawingManager.value) {
        drawingManager.value.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
        isDrawing.value = true;
    }
};

const cancelDrawing = () => {
    if (drawingManager.value) {
        drawingManager.value.setDrawingMode(null);
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
            currentPolygon.value = null;
        }
        isDrawing.value = false;
        zoneForm.value.coordinates = [];
    }
};

const editZone = (zone) => {
    editingZone.value = zone;
    zoneForm.value = { ...zone };
    showAddZoneDialog.value = true;

    // Wait for map to be ready
    nextTick(() => {
        if (zone.coordinates && map.value) {
            // Clear existing polygon
            if (currentPolygon.value) {
                currentPolygon.value.setMap(null);
            }

            // Create new polygon with existing coordinates
            currentPolygon.value = new google.maps.Polygon({
                paths: zone.coordinates,
                fillColor: '#42A5F5',
                fillOpacity: 0.3,
                strokeWeight: 2,
                strokeColor: '#1976D2',
                editable: true
            });

            currentPolygon.value.setMap(map.value);

            // Fit map bounds to polygon
            const bounds = new google.maps.LatLngBounds();
            zone.coordinates.forEach(coord => {
                bounds.extend(new google.maps.LatLng(coord.lat(), coord.lng()));
            });
            map.value.fitBounds(bounds);

            // Listen for path changes
            google.maps.event.addListener(currentPolygon.value.getPath(), 'set_at', () => {
                const newCoordinates = currentPolygon.value.getPath().getArray().map(coord => ({
                    lat: coord.lat(),
                    lng: coord.lng()
                }));
                zoneForm.value.coordinates = newCoordinates;
            });
        }
    });
};

// Watch for dialog visibility to initialize map
watch(showAddZoneDialog, (newValue) => {
    if (newValue) {
        nextTick(() => {
            initMap();
        });
    } else {
        // Clean up when dialog closes
        if (currentPolygon.value) {
            currentPolygon.value.setMap(null);
            currentPolygon.value = null;
        }
        if (drawingManager.value) {
            drawingManager.value.setMap(null);
        }
    }
});

const confirmDeleteZone = (zone) => {
    editingZone.value = zone;
    showDeleteDialog.value = true;
};

const deleteZone = async () => {
    try {
        await axios.delete(`/api/zones/${editingZone.value.id}`);
        showDeleteDialog.value = false;
        fetchZones();
    } catch (error) {
        console.error('Error deleting zone:', error);
    }
};

const saveZone = async () => {
    try {
        if (editingZone.value) {
            await axios.put(`/api/zones/${editingZone.value.id}`, zoneForm.value);
        } else {
            await axios.post('/api/zones', zoneForm.value);
        }
        showAddZoneDialog.value = false;
        fetchZones();
        resetForm();
    } catch (error) {
        console.error('Error saving zone:', error);
    }
};

const resetForm = () => {
    zoneForm.value = {
        name: '',
        delivery_charge_per_km: 0,
        base_delivery_charge: 0,
        minimum_delivery_charge: 0,
        description: '',
        coordinates: [],
        status: 'active'
    };
    editingZone.value = null;
    if (currentPolygon.value) {
        currentPolygon.value.setMap(null);
        currentPolygon.value = null;
    }
};

onMounted(() => {
    fetchZones();
});
</script>
