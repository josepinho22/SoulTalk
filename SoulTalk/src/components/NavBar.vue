<template>
  <v-app-bar
    flat
    color="transparent"
    height="72"
    class="white--text"
    style="position: absolute; top: 0; left: 0; z-index: 10;"
  >
    <div class="navbar-inner">
      <!-- Hamburger icon (left) -->
      <v-app-bar-nav-icon
        class="d-md-none"
        @click="drawer = !drawer"
      />

      <!-- Centered brand -->
      <div class="brand-center">
        <v-btn
          text
          class="mx-3 font-heading"
          to="/"
        >
          SoulTalk
        </v-btn>
      </div>

      <!-- Desktop nav buttons (right) -->
      <div class="d-none d-md-flex">
        <v-btn
          text
          class="mx-3 font-heading"
          to="/confessional"
        >
          Confessional
        </v-btn>
        <v-btn
          text
          class="mx-3 font-heading"
          to="/advices"
        >
          Advices
        </v-btn>
        <v-btn
          text
          class="mx-3 font-heading"
          to="/churches"
        >
          Churches
        </v-btn>
        <v-btn
          text
          class="mx-3 font-heading"
          to="/contact"
        >
          Contact
        </v-btn>
      </div>
    </div>
  </v-app-bar>

  <!-- Navigation drawer (mobile only) -->
  <v-navigation-drawer
    v-model="drawer"
    temporary
    right
    class="d-md-none gradient-drawer"
  >
    <v-list>
      <v-list-item to="/confessional">
        Confessional
      </v-list-item>
      <v-list-item to="/advices">
        Advices
      </v-list-item>
      <v-list-item to="/churches">
        Churches
      </v-list-item>
      <v-list-item to="/contact">
        Contact
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const drawer = ref(false)
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Grenze+Gotisch:wght@100;200;300;400;500;600;700&display=swap');
.v-btn.font-heading > .v-btn__content {
  font-family: 'Grenze Gotisch', serif !important;
  font-weight: 400 !important;
  font-size: 20px !important;
  text-transform: uppercase !important;
  color: #FFFFFF !important;
}
</style>
