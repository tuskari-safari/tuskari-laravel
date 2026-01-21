<template>
    <div v-if="hasValidToken" ref="mapContainer" class="map-container" />
    <div v-else class="map-placeholder">
        <i class="fas fa-map-marked-alt"></i>
        <p>Map unavailable</p>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import mapboxgl from 'mapbox-gl'
import 'mapbox-gl/dist/mapbox-gl.css'

const props = defineProps({
    location: {
        type: Object,
        required: true
    },
     zoom: {
        type: Number,
        default: 10
    }
})

const mapContainer = ref(null)
let map

const mapboxToken = import.meta.env.VITE_MAPBOX_KEY
const hasValidToken = computed(() => !!mapboxToken)

if (mapboxToken) {
    mapboxgl.accessToken = mapboxToken
}

const addMarker = ({ lng, lat, name, description }) => {
    const popupContent = `
        <div class="custom-popup">
            <div class="popup-header">
                <i class="fas fa-map-marker-alt"></i>
                <strong>${name}</strong>
            </div>
            <div class="popup-content">
                ${description || 'Safari location'}
            </div>
        </div>`

    const popup = new mapboxgl.Popup({
        offset: 30,
        closeButton: true,
        closeOnClick: false,
    }).setHTML(popupContent)

    // Add marker (popup will only show on click)
    new mapboxgl.Marker({ color: '#dc3545' })
        .setLngLat([lng, lat])
        .setPopup(popup) // attach popup
        .addTo(map)
}

onMounted(() => {
    if (!hasValidToken.value) return

    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: 'mapbox://styles/mapbox/satellite-streets-v12',
        center: [props.location.lng, props.location.lat],
        zoom: props.zoom,
        attributionControl: false,
    })

    // Add navigation controls
    map.addControl(new mapboxgl.NavigationControl(), 'top-right')
    map.addControl(new mapboxgl.FullscreenControl(), 'top-right')
    map.addControl(new mapboxgl.ScaleControl({
        maxWidth: 100,
        unit: 'metric'
    }), 'bottom-left')

    // Add marker and center map
    map.on('load', () => {
        addMarker(props.location)

        map.flyTo({
            center: [props.location.lng, props.location.lat],
            zoom: props.zoom,
            speed: 1.2
        })
    })
})
</script>

<style>
.map-container {
    width: 100%;
    height: 600px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.map-placeholder {
    width: 100%;
    height: 600px;
    border-radius: 15px;
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.map-placeholder i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.6;
}

.map-placeholder p {
    font-size: 16px;
    margin: 0;
    opacity: 0.8;
}

/* Custom popup styling */
.custom-popup {
    font-family: "Kumbh Sans", sans-serif;
    min-width: 200px;
}

.popup-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px 8px 16px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    margin: -10px -10px 0 -10px;
}

.popup-header i {
    color: #28a745;
    font-size: 14px;
}

.popup-content {
    padding: 12px 16px;
    color: #495057;
    line-height: 1.5;
    background: white;
    border-radius: 0 0 8px 8px;
    margin: 0 -10px -10px -10px;
}

/* Mapbox control styling */
.mapboxgl-ctrl-group {
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    background: white !important;
}

.mapboxgl-ctrl button {
    border-radius: 6px !important;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%) !important;
    color: white !important;
    border: none !important;
    transition: all 0.3s ease !important;
}

.mapboxgl-ctrl button:hover {
    background: linear-gradient(135deg, #4a5a4a 0%, #2f3a2f 100%) !important;
    transform: scale(1.05) !important;
}

.mapboxgl-ctrl button .mapboxgl-ctrl-icon {
    background-color: white !important;
}

/* Popup close button styling */
.mapboxgl-popup-close-button {
    background: #dc3545 !important;
    color: white !important;
    border-radius: 50% !important;
    width: 24px !important;
    height: 24px !important;
    font-size: 16px !important;
    font-weight: bold !important;
    transition: all 0.3s ease !important;
    border: none !important;
    right: 8px !important;
    top: 8px !important;
}

.mapboxgl-popup-close-button:hover {
    background: #c82333 !important;
    transform: scale(1.1) !important;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
}

/* Hide Mapbox attribution */
.mapboxgl-ctrl-attrib {
    display: none !important;
}
</style>
