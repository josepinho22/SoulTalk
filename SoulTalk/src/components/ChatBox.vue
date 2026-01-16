<template>
  <div class="chat-wrapper">
    <v-scale-transition>
      <v-card
        v-if="dialog"
        class="chat-box d-flex flex-column"
        loading
      >
        <v-container id="message-list" class="overflow-auto">
          <v-row>
            <v-col class="font-weight-bold text-h6" cols="12">Chat</v-col>
          </v-row>
          <v-row v-for="(item, index) in data.contents" :key="item.id" class="py-2">
            <v-col v-if="item.role === 'user' && item.parts?.[0]?.text" class="text-right" cols="12">
              <div class="request pa-2 rounded-lg elevation-5">
                {{ item.parts[0].text }}
              </div>
              <div class="caption">Me</div>
            </v-col>
            <v-col v-else-if="item.parts?.[0]?.text" cols="12">
              <div class="response pa-2 rounded-lg elevation-5">
                {{ item.parts[0].text }}
              </div>
              <div class="caption">Father</div>
            </v-col>
          </v-row>
        </v-container>
        <v-container class="pa-2">
          <v-row align="center" justify="end">
            <v-col cols="8">
              <v-text-field
                id="chat-box-textfield"
                v-model="inputText"
                class="bg-white"
                density="comfortable"
                hide-details="auto"
                label="Confess yourself..."
                rounded="lg"
                variant="outlined"
                @keyup.enter="send"
              />
            </v-col>
            <v-col class="d-flex align-center" cols="4">
              <v-btn
                id="chat-box-submit"
                class="white--text text-none px-6 rounded-lg"
                color="deep-purple"
                @click="send"
              >
                Send
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-scale-transition>
    <v-btn
      id="chat-box"
      class="rounded-circle mb-1 ml-1"
      @click.stop="showDialog"
    >
      <v-icon
        color="white"
        icon="mdi-message"
        size="large"
      ></v-icon>
    </v-btn>
  </div>
</template>

<script lang="ts" setup>
import { nextTick, onMounted, onUnmounted, ref } from 'vue'
import apiService from '@/services/apiService'

const data = ref({
  contents: []
})
const inputText = ref('')
const dialog = ref(false)

function showDialog() {
  dialog.value = true
  document.getElementById('chat-box')?.classList.add('d-none')
}

function handleClickOutside(e: MouseEvent) {
  const target = e.target as HTMLElement
  if (!target.closest('.chat-wrapper') && dialog.value) {
    dialog.value = false
    document.getElementById('chat-box')?.classList.remove('d-none')
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

async function send() {
  if (inputText.value) {
    data.value.contents.push({
      role: 'user',
      parts: [{ text: inputText.value }]
    })

    const newJson = await apiService.ask(data.value.contents)
    data.value.contents.push({
      role: newJson.role,
      parts: [{ text: newJson.text }]
    })

    inputText.value = ''

    await nextTick(() => {
      const container = document.querySelector('.chat-box .v-container')
      if (container) {
        container.scrollTop = container.scrollHeight
      }
    })
  }
}
</script>

