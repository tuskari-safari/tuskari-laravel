<template>
    <div class="relative">
        <div class="absolute top-2 left-1/2 transform -translate-x-1/2 bg-white px-4 py-2 rounded shadow text-sm font-medium z-10">
            {{ coordinates.place_name || "Select a location" }}
        </div>

        <!-- Map container -->
        <div ref="mapContainer" style="width: 100%; height: 300px; border-radius: 8px; overflow: hidden"></div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import mapboxgl from "mapbox-gl";
import MapboxGeocoder from "@mapbox/mapbox-gl-geocoder";

import "@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css";
import "mapbox-gl/dist/mapbox-gl.css";

const props = defineProps({
    lat: { type: Number, default: null },
    lng: { type: Number, default: null },
    placeName: { type: String, default: "" },
});

const emit = defineEmits(["update:coordinates"]);

const mapContainer = ref(null);
const map = ref(null);
const marker = ref(null);
const popup = ref(null);

const coordinates = ref({
    lat: props.lat,
    lng: props.lng,
    place_name: props.placeName,
});

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_KEY;

let customPlaces = [];

/* -----------------------------
   Load custom GeoJSON places
----------------------------- */
async function loadCustomPlaces() {
    try {
        const res = await fetch("/tuskari_safari_destinations.geojson");
        const data = await res.json();
        customPlaces = data.features || [];
    } catch (err) {
        console.error("Failed to load custom places:", err);
    }
}

/* -----------------------------
   Local Geocoder for parks/reserves
----------------------------- */
function forwardGeocoder(query) {
    const matchingFeatures = [];

    for (const feature of customPlaces) {
        if (!feature.properties) continue;

        const name =
            feature.properties.name ||
            feature.properties["Park / Reserve"] ||
            null;

        if (!name) continue;

        if (name.toLowerCase().includes(query.toLowerCase())) {
            matchingFeatures.push({
                type: "Feature",
                geometry: feature.geometry,
                place_name: `${name} (Local)`,
                center: feature.geometry.coordinates,
                place_type: ["custom"],
                properties: feature.properties,
            });
        }
    }

    return matchingFeatures;
}

/* -----------------------------
   Map + Search Setup
----------------------------- */
onMounted(async () => {
    await nextTick();
    await loadCustomPlaces();

    // Initialize map
    map.value = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/satellite-streets-v12",
        center: props.lat && props.lng ? [props.lng, props.lat] : [8.7832, 34.5085],
        zoom: 6,
    });

    map.value.on("load", () => {
        map.value.resize();
    });

    // Add marker if preloaded
    if (props.lat && props.lng) {
        addMarker(props.lng, props.lat, props.placeName);
    }

    /* -----------------------------
       Mapbox + Local Geocoder Search
    ----------------------------- */
    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl,
        marker: false,
        placeholder: "Search for a park, reserve, or place...",
        localGeocoder: forwardGeocoder, // include local search
        localGeocoderOnly: false,       // also keep Mapbox global search
        zoom: 10,
    });

    // 👇 Add search bar inside the map
    map.value.addControl(geocoder, "top-right");

    // When a result is selected
    geocoder.on("result", (e) => {
        const { center, place_name } = e.result;
        const [lng, lat] = center;

        addMarker(lng, lat, place_name);
        updateLocation(lng, lat, place_name);
        map.value.flyTo({ center: [lng, lat], zoom: 12 });
    });

    // Click anywhere to set location manually
    map.value.on("click", (e) => {
        const { lng, lat } = e.lngLat;
        addMarker(lng, lat);
        updateLocation(lng, lat);
    });
});

/* -----------------------------
   Add / Update Marker
----------------------------- */
function addMarker(lng, lat, place = null) {
    if (!marker.value) {
        marker.value = new mapboxgl.Marker({ draggable: true })
            .setLngLat([lng, lat])
            .addTo(map.value);

        marker.value.on("dragend", () => {
            const coords = marker.value.getLngLat();
            updateLocation(coords.lng, coords.lat);
        });
    } else {
        marker.value.setLngLat([lng, lat]);
    }
}

/* -----------------------------
   Reverse Geocode + Emit
----------------------------- */
async function updateLocation(lng, lat, place = null) {
    coordinates.value = { lat, lng, place_name: place };

    try {
        if (!place) {
            const res = await fetch(
                `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}`
            );
            const data = await res.json();
            place = data.features[0]?.place_name || "Unknown location";
        }

        coordinates.value.place_name = place;
        popup.value?.setText?.(place);
        emit("update:coordinates", coordinates.value);
    } catch (error) {
        console.error("Geocoding error:", error);
        coordinates.value.place_name = "Unable to fetch location";
        emit("update:coordinates", coordinates.value);
    }
}
</script>

<style scoped>
.mapboxgl-ctrl-geocoder {
    min-width: 260px;
    border-radius: 6px;
}

.mapboxgl-ctrl-top-right {
    top: 10px !important;
    right: 10px !important;
}
</style>
