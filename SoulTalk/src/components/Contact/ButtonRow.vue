<script setup>
import router from '@/router/index.js'

const emit = defineEmits(['submit'])
</script>

<template>
  <v-row
    justify="center"
    class="mt-6"
  >
    <v-col
      cols="auto"
      class="text-center"
    >
      <v-btn
        :loading="submitting"
        :disabled="submitting"
        class="custom-btn mb-3"
        :block="true"
        @click="emit('submit')"
      >
        Send
      </v-btn>
      <div
        class="text-subtitle-1 font-weight-medium mb-3"
        style="color: white;"
      >
        OR
      </div>
      <v-btn
        :disabled="submitting"
        class="custom-btn"
        :block="true"
        @click="router.push('/appointment')"
      >
        Get an appointment
      </v-btn>
    </v-col>
  </v-row>
</template>

<style scoped>

</style>
