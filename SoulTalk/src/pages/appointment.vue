<script setup>
import { ref, reactive, computed, watch } from 'vue';

const radius = ref(1000);
const radiusOptions = [
  { label: '1 km', value: 1000 },
  { label: '2 km', value: 2000 },
  { label: '5 km', value: 5000 }
];

const churches = ref([]);
const selectedChurch = ref(null);
const lastPosition = ref(null);

const form = reactive({
  email: '',
  month: '',
  day: '',
  time: ''
});

const formRef = ref(null);
const formIsValid = ref(false);
const submitting = ref(false);
const showSuccessDialog = ref(false);

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

const days = computed(() => {
  const index = months.indexOf(form.month);
  if (index < 0) return [];
  const year = new Date().getFullYear();
  const numDays = new Date(year, index + 1, 0).getDate();
  return Array.from({ length: numDays }, (_, i) => i + 1);
});

const times = ['08:00', '09:00', '10:00', '14:00', '15:00'];

const rules = {
  email: [
    v => !!v || 'Email is required',
    v => /.+@.+\..+/.test(v) || 'Email must be valid',
    v => v.length <= 40 || 'Max 40 characters'
  ],
  month: [v => !!v || 'Select a month'],
  day: [v => !!v || 'Select a day'],
  time: [v => !!v || 'Select a time']
};

function distanceManager(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(lat1 * Math.PI / 180) *
    Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) ** 2;
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
  const response = await fetch('https://overpass-api.de/api/interpreter', {
    method: 'POST',
    body: query,
    headers: { 'Content-Type': 'text/plain' }
  });
  const data = await response.json();
  return data.elements.map(el => {
    const coords = el.type === 'way' && el.center ? el.center : { lat: el.lat, lon: el.lon };
    const name = el.tags?.name || 'Unnamed church';
    const dist = distanceManager(lat, lng, coords.lat, coords.lon);
    return { name, lat: coords.lat, lng: coords.lon, distance: dist };
  }).sort((a, b) => a.distance - b.distance);
}

async function useMyLocation() {
  navigator.geolocation.getCurrentPosition(
    async pos => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      lastPosition.value = { lat, lng };
      churches.value = await fetchNearbyChurches(lat, lng);
    },
    () => alert('Unable to retrieve your location.')
  );
}

watch(radius, () => {
  if (lastPosition.value) {
    useMyLocation();
  }
});

function selectChurch(church) {
  selectedChurch.value = church;
}

function resetForm() {
  form.email = '';
  form.month = '';
  form.day = '';
  form.time = '';
  selectedChurch.value = null;
  formRef.value?.resetValidation();
}

function submit() {
  if (!formIsValid.value || !selectedChurch.value) return;
  submitting.value = true;
  showSuccessDialog.value = true;

  setTimeout(() => {
    showSuccessDialog.value = false;
    submitting.value = false;
    resetForm();
  }, 5000);
}
</script>

<template>
  <v-container id="page-appointment" class="py-10">
    <v-form ref="formRef" v-model="formIsValid">
      <v-row>
        <!-- Liste des églises -->
        <v-col cols="12" md="5">
          <h2 class="custom-label">Place of confession</h2>

          <div class="d-flex align-center mb-3">
            <span class="search-radius-label">Search radius:</span>
            <v-select
              v-model="radius"
              :items="radiusOptions"
              item-title="label"
              item-value="value"
              class="radius-select ml-2"
              hide-details
              variant="solo"
              density="compact"
            />
          </div>

          <v-btn class="custom-btn mb-4" block height="50" @click="useMyLocation">
            Use my location
          </v-btn>

          <div class="churches-list">
            <v-card
              v-for="(church, index) in churches"
              :key="index"
              class="mb-2 church-card"
              color="#333"
              rounded="lg"
            >
              <v-card-text class="church-card-text">
                <div>
                  <div class="text-h6">{{ church.name }}</div>
                  <div class="text-caption">{{ church.distance.toFixed(2) }} km</div>
                </div>
                <v-btn
                  :color="selectedChurch?.name === church.name ? 'deep-purple accent-4' : 'primary'"
                  @click="selectChurch(church)"
                  rounded
                >
                  {{ selectedChurch?.name === church.name ? 'Selected' : 'Select' }}
                </v-btn>
              </v-card-text>
            </v-card>
          </div>
        </v-col>

        <!-- Formulaire -->
        <v-col cols="12" md="7">
          <v-row>
            <v-col cols="12" md="4">
              <label class="custom-label">Month</label>
              <v-select
                v-model="form.month"
                :items="months"
                class="custom-input"
                variant="solo"
                :rules="rules.month"
                required
              />
            </v-col>
            <v-col cols="12" md="4">
              <label class="custom-label">Day</label>
              <v-select
                v-model="form.day"
                :items="days"
                class="custom-input"
                variant="solo"
                :rules="rules.day"
                required
              />
            </v-col>
            <v-col cols="12" md="4">
              <label class="custom-label">Time</label>
              <v-select
                v-model="form.time"
                :items="times"
                class="custom-input"
                variant="solo"
                :rules="rules.time"
                required
              />
            </v-col>

            <v-col cols="12">
              <label class="custom-label">Email address</label>
              <v-text-field
                v-model="form.email"
                placeholder="Your email"
                class="custom-input"
                variant="solo"
                :rules="rules.email"
                counter="40"
                maxlength="40"
                required
              />
            </v-col>

            <v-col cols="12" class="text-center">
              <v-btn class="custom-btn" @click="submit" :disabled="!selectedChurch || submitting">
                Send request
              </v-btn>
            </v-col>

            <v-col cols="12" class="mt-4">
              <blockquote class="quote">
                An email will be sent to you to confirm the appointment
              </blockquote>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>

    <!-- Popup -->
    <v-dialog v-model="showSuccessDialog" width="400" transition="dialog-bottom-transition">
      <v-card class="pa-4 text-center">
        <v-card-title class="text-h6 font-weight-bold">Request Sent</v-card-title>
        <v-card-text>
          Your appointment at<br />
          <strong>{{ selectedChurch?.name }}</strong><br />
          has been received. You will get a confirmation shortly.
        </v-card-text>
        <v-card-actions class="justify-center mt-2">
          <v-btn class="custom-btn" @click="showSuccessDialog = false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>
