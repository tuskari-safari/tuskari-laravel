<template>
  <div ref="geocoderContainer"></div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import mapboxgl from 'mapbox-gl'
import MapboxGeocoder from '@mapbox/mapbox-gl-geocoder'

import '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css'

const props = defineProps({
  modelValue: String
})
const emit = defineEmits(['update:location'])

const geocoderContainer = ref(null)
let geocoder = null
let customPlaces = [] // will hold your parks/reserves

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_KEY;

// 👉 Load GeoJSON file
async function loadCustomPlaces() {
  const res = await fetch('/tuskari_safari_destinations.geojson') // hosted in /public
  const data = await res.json()
  customPlaces = data.features
}

// 👉 Custom local geocoder
function forwardGeocoder(query) {
  const matchingFeatures = []

  for (const feature of customPlaces) {
    if (!feature.properties) continue

    // Try both "name" and "Park / Reserve"
    const placeName =
      feature.properties.name ||
      feature.properties["Park / Reserve"] ||
      null

    if (!placeName) continue

    if (placeName.toLowerCase().includes(query.toLowerCase())) {
      matchingFeatures.push({
        type: 'Feature',
        geometry: feature.geometry,
        place_name: placeName,
        center: feature.geometry.coordinates,
        place_type: ['custom'],
        properties: feature.properties
      })
    }
  }

  return matchingFeatures
}

onMounted(async () => {
  await nextTick()
  await loadCustomPlaces()

  geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl,
    placeholder: 'Search for a park or reserve',
    marker: false,
    localGeocoder: forwardGeocoder // 👈 add our custom dataset
  })

  geocoder.addTo(geocoderContainer.value)

  geocoder.on('result', (e) => {
    emit('update:location', {
      place_name: e.result.place_name,
      longitude: e.result.center[0],
      latitude: e.result.center[1],
      full: e.result
    })
  })

  if (props.modelValue) {
    geocoder.setInput(props.modelValue)
  }
})

watch(() => props.modelValue, (newVal) => {
  if (geocoder && newVal) {
    geocoder.setInput(newVal)
  }
})
</script>

<style scoped>
.mapboxgl-ctrl-geocoder {
  min-width: 100%;
}
</style>
 