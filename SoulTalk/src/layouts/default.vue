<template>
  <v-app :id="pageClass">
    <NavBar />
    <v-main>
      <router-view />
      <slot />
    </v-main>
    <ChatBox v-if="pageClass !== 'page-confessional'" />
  </v-app>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import NavBar from '@/components/NavBar.vue'
import ChatBox from '@/components/ChatBox.vue'

const route = useRoute()
const pageClass = computed(() => {
  const name = route.name?.toString().toLowerCase().replace('/', '') || 'home'
  return `page-${name.replace(/\s+/g, '-').replace(/\//g, '')}`
})
</script>
