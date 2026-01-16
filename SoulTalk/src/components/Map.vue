<script setup>

import { onMounted, ref } from 'vue';
import ChurchesItem from '@/components/ChurchesItem.vue'
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

let map;
let userMarker;
let markers = [];
const churches = ref([]);
const radius = ref(1000);
const radiusOptions = [
  { label: '1 km', value: 1000 },
  { label: '2 km', value: 2000 },
  { label: '5 km', value: 5000 }
];
let lastPosition = null;


function distanceManager(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) *
    Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

async function fetchNearbyChurches(lat, lng) {
  const query = `
    [out:json][timeout:25];
    (
      node["amenity"="place_of_worship"](around:${radius.value},${lat},${lng});
      way["amenity"="place_of_worship"](around:${radius.value},${lat},${lng});
    );
    out center;
  `;
  const url = 'https://overpass-api.de/api/interpreter';
  const response = await window.fetch(url, {
    method: 'POST',
    body: query,
    headers: { 'Content-Type': 'text/plain' }
  });
  const data = await response.json();
  const result = data.elements.map(el => {
    const coords = el.type === 'way' && el.center ? el.center : { lat: el.lat, lon: el.lon };
    const name = el.tags && el.tags.name ? el.tags.name : 'Unnamed church';
    const distance = distanceManager(lat, lng, coords.lat, coords.lon);
    return { name, lat: coords.lat, lng: coords.lon, distance };
  });
  return result;
}

async function updateChurches(lat, lng) {
  const nearby = await fetchNearbyChurches(lat, lng);
  nearby.sort((a, b) => a.distance - b.distance);
  churches.value = nearby;

  markers.forEach(m => map.removeLayer(m));
  markers = [];

  nearby.forEach(church => {
    const marker = L.marker([church.lat, church.lng])
      .addTo(map)
      .bindPopup(`<b>${church.name}</b><br>${church.distance.toFixed(2)} km`);
    markers.push(marker);
  });
}

function setUserPosition(lat, lng) {
  if (userMarker) {
    map.removeLayer(userMarker);
  }
  userMarker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup('You are here')
    .openPopup();
  map.setView([lat, lng], 15);
}

function useMyLocation() {
  if (lastPosition) {
    updateChurches(lastPosition.lat, lastPosition.lng);
    return;
  }

  window.navigator.geolocation.getCurrentPosition(
    async position => {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      lastPosition = { lat, lng };
      setUserPosition(lat, lng);
      await updateChurches(lat, lng);
    },
    () => {
      window.alert('Unable to retrieve your location.');
    }
  );
}



onMounted(() => {
  map = L.map('map').setView([46.803, 7.150], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  map.on('click', async e => {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;
    lastPosition = { lat, lng };
    setUserPosition(lat, lng);
    await updateChurches(lat, lng);
  });
});
</script>

<template>
  <v-container>
    <v-row>
      <v-col
        cols="12"
        md="5"
      >
        <h1 class="title">
          Find a church near you
        </h1>

        <div class="search-controls">
          <div class="search-radius-label">
            Search radius:
          </div>
          <v-select
            v-model="radius"
            :items="radiusOptions"
            item-title="label"
            item-value="value"
            density="compact"
            bg-color="deep-purple lighten-5"
            color="deep-purple accent-4"
            hide-details
            class="radius-select"
          />
        </div>

        <v-btn
          block
          color="deep-purple accent-4"
          class="mb-4"
          height="50"
          @click="useMyLocation"
        >
          {{ lastPosition ? 'Update churches' : 'Use my location' }}
        </v-btn>

        <div class="churches-list">
          <v-card
            v-for="(church, index) in churches"
            :key="index"
            class="mb-2 church-card"
            color="#333"
            rounded="lg"
          >
            <ChurchesItem :church="church" :map="map" :markers="markers" />
          </v-card>
          <div
            v-if="churches.length === 0"
            class="no-churches"
          >
            No churches found yet. Use the button above to search.
          </div>
        </div>
      </v-col>

      <v-col
        cols="12"
        md="7"
      >
        <div id="map" />
      </v-col>
    </v-row>
  </v-container>
</template>
