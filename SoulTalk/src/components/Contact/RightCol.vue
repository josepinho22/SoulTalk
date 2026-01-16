<script setup>
defineProps(['form', 'rules'])
</script>

<template>
  <v-col
    cols="12"
    md="6"
  >
    <label class="custom-label">Your message</label>
    <v-textarea
      v-model="form.message"
      :rules="rules.message"
      class="custom-input message-field"
      counter="500"
      maxlength="500"
      persistent-placeholder
      placeholder="Type your message"
      required
      rows="8"
      variant="solo"
    />
  </v-col>
</template>

<style scoped>

</style>
