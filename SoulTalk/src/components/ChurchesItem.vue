<script setup>
const props = defineProps({
  church: Object,
  map: Object,
  markers: []
})


function locate(church) {
  props.map.setView([church.lat, church.lng], 17);
  const m = props.markers.find(m => {
    const p = m.getLatLng();
    return p.lat === church.lat && p.lng === church.lng;
  });
  if (m) m.openPopup();
}
</script>

<template>
  <v-card-text class="church-card-text">
    <div>
      <div class="text-h6">
        {{ props.church.name }}
      </div>
      <div class="text-caption">
        {{ props.church.distance.toFixed(2) }} km
      </div>
    </div>
    <v-btn
      color="deep-purple accent-4"
      rounded
      @click="locate(props.church)"
    >
      Locate
    </v-btn>
  </v-card-text>
</template>

<style scoped>

</style>
