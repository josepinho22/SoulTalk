<template>
    <v-main ref="mainContainer" class="bg">
      <v-container r fluid class="pa-0 pt-8 pb-24">
        <v-row dense>
          <v-col
            v-for="(msg, index) in messages.contents"
            :key="index"
            cols="12"
            :class="[
              'pl-16 pr-16 mt-4 d-flex flex-column',
              msg.role === 'user' ? 'align-end text-right' : 'align-start text-left'
            ]"
          >
            <v-sheet class="chat-label pa-0" elevation="0">{{ msg.role === 'user' ? 'Me' : 'Father' }}</v-sheet>
            <v-card
              class="half-width pa-4 chat-bubble"
              :class="msg.role === 'user' ? 'right-chat' : 'left-chat'"
              elevation="8"
            >
              {{ msg.parts[0].text }}
            </v-card>
          </v-col>
          <v-col cols="12" class="chat-spacer" />
        </v-row>
      </v-container>

      <!-- Zone d'envoi -->
      <v-container class="input-bar d-flex align-center justify-space-between px-6 py-4" fluid>
        <v-text-field
          v-model="newMessage"
          placeholder="Confess yourself..."
          hide-details
          variant="solo"
          flat
          class="input-field"
          @keyup.enter="sendMessage"
        />
        <v-btn class="send-btn" @click="sendMessage">Send</v-btn>
      </v-container>
    </v-main>
  </template>

<script setup>
import { ref, nextTick } from 'vue'
import apiService from '@/services/apiService'

// Liste des messages du chat
const messages = ref({
  contents: []
})
// Champ de saisie du message
const newMessage = ref('')

const mainContainer = ref(null)

// Fonction qui scroll vers le bas
function scrollToBottom() {
  nextTick(() => {
    const el = mainContainer.value?.$el || mainContainer.value
    if (el) {
      el.scrollTop = el.scrollHeight
    }
  })
}

// Fonction d’envoi de message
async function sendMessage() {
  if (newMessage.value) {
    messages.value.contents.push({
      role: 'user',
      parts: [{ text: newMessage.value }]
    })

    const newJson = await apiService.ask(messages.value.contents)
    messages.value.contents.push({
      role: newJson.role,
      parts: [{ text: newJson.text }]
    })

    newMessage.value = ''
    scrollToBottom()
  }
}
</script>
