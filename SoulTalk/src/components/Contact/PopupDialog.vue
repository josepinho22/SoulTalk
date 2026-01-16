<script setup>
import {computed} from "vue";

const props = defineProps({
  showSuccessDialog: Boolean,  // Représente v-model
  name: String           // Pour afficher le nom dans le message
});

const emit = defineEmits(['update:showSuccessDialog']);

// Crée un lien réactif en lecture/écriture avec la prop
const show = computed({
  get: () => props.showSuccessDialog,
  set: val => emit('update:showSuccessDialog', val)
});
</script>

<template>
  <v-dialog
    v-model="show"
    width="400"
    transition="dialog-bottom-transition"
  >
    <v-card class="pa-4 text-center">
      <v-card-title class="text-h6 font-weight-bold">
        Message Sent
      </v-card-title>
      <v-card-text>
        Thank you <strong>{{ name }}</strong> for contacting us.<br />
        You will receive a reply shortly.
      </v-card-text>
      <v-card-actions class="justify-center mt-2">
        <v-btn class="custom-btn" @click="show = false">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
