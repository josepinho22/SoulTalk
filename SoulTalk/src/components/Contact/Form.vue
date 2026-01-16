<script setup>
import { ref, reactive } from 'vue';
import LeftCol from "@/components/Contact/LeftCol.vue";
import RightCol from "@/components/Contact/RightCol.vue";
import ButtonRow from "@/components/Contact/ButtonRow.vue";
import PopupDialog from "@/components/Contact/PopupDialog.vue";

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: '',
});
const formRef = ref(null);
const formIsValid = ref(false);
const showSuccessDialog = ref(false);
const submittedName = ref('');
const submitting = ref(false);

const noSpecialChars = v => !/[<>]/.test(v) || 'Invalid characters not allowed';

const rules = {
  name: [
    v => !!v || 'Name is required',
    v => v.length <= 30 || 'Name must be less than 30 characters',
    noSpecialChars
  ],
  email: [
    v => !!v || 'Email is required',
    v => /.+@.+\..+/.test(v) || 'Email must be valid',
    v => v.length <= 40 || 'Email must be less than 40 characters',
    noSpecialChars
  ],
  subject: [
    v => !!v || 'Subject is required',
    v => v.length <= 100 || 'Subject must be less than 100 characters',
    noSpecialChars
  ],
  message: [
    v => !!v || 'Message is required',
    v => v.length <= 500 || 'Message must be less than 500 characters',
    noSpecialChars
  ],
};

async function submit() {
  if (!formIsValid.value || submitting.value) return;

  submitting.value = true;
  submittedName.value = form.name;
  showSuccessDialog.value = true;
  reset();

  setTimeout(() => {
    showSuccessDialog.value = false;
    submitting.value = false;
  }, 5000);
}

function reset() {
  formRef.value.reset();
}
</script>

<template>
  <v-form
    ref="formRef"
    v-model="formIsValid"
  >
    <v-row>
      <!-- Colonne gauche -->
      <LeftCol :form="form" :rules="rules" />

      <!-- Colonne droite -->
      <RightCol :form="form" :rules="rules" />
    </v-row>

    <!-- Boutons -->
    <ButtonRow @submit="submit"/>
  </v-form>

  <!-- dialog -->
 <PopupDialog v-model:showSuccessDialog="showSuccessDialog" :name="form.name"/>
</template>
