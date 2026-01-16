<script setup>
defineProps(['form', 'rules'])
</script>

<template>
  <v-col
    cols="12"
    md="6"
  >
    <label class="custom-label">Name</label>
    <v-text-field
      v-model="form.name"
      :rules="rules.name"
      class="custom-input"
      counter="30"
      maxlength="30"
      persistent-placeholder
      placeholder="Your name"
      required
      variant="solo"
    />

    <label class="custom-label">Email address</label>
    <v-text-field
      v-model="form.email"
      :rules="rules.email"
      class="custom-input"
      counter="40"
      maxlength="40"
      persistent-placeholder
      placeholder="Your email"
      required
      variant="solo"
    />

    <label class="custom-label">Subject of your message</label>
    <v-text-field
      v-model="form.subject"
      :rules="rules.subject"
      class="custom-input"
      counter="100"
      maxlength="100"
      persistent-placeholder
      placeholder="Subject"
      required
      variant="solo"
    />
  </v-col>
</template>

<style scoped>

</style>
