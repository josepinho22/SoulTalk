<script setup>
import {ref} from 'vue';
import candleImg from '@/assets/candle.png';

const adviceList = [
  {
    title: 'I feel lost',
    content: '“Your word is a lamp to my feet and a light to my path.” — Psalm 119:105'
  },
  {
    title: 'I need rest',
    content: '“Come to me, all you who are weary and burdened, and I will give you rest.” — Matthew 11:28'
  },
  {
    title: 'I carry guilt',
    content: '“If we confess our sins, He is faithful and just to forgive us our sins and to cleanse us from all unrighteousness.” — 1 John 1:9'
  },
  {
    title: 'I want clarity',
    content: '“If any of you lacks wisdom, let him ask of God, who gives generously to all without reproach, and it will be given him.” — James 1:5'
  },
];

const openIndex = ref(null);

function toggleBox(index) {
  openIndex.value = openIndex.value === index ? null : index;
}

function beforeEnter(el) {
  el.style.height = '0'
  el.style.opacity = '0'
}

function enter(el, done) {
  el.style.transition = 'height 0.4s cubic-bezier(.4,0,.2,1), opacity 0.4s cubic-bezier(.4,0,.2,1)'
  el.style.height = el.scrollHeight + 'px'
  el.style.opacity = '1'
  el.addEventListener('transitionend', function handler(e) {
    if (e.propertyName === 'height') {
      el.removeEventListener('transitionend', handler)
      el.style.height = 'auto'
      done()
    }
  })
}

function afterEnter(el) {
  el.style.height = 'auto'
}

function beforeLeave(el) {
  el.style.height = el.scrollHeight + 'px'
  el.style.opacity = '1'
}

function leave(el, done) {
  el.style.transition = 'height 0.4s cubic-bezier(.4,0,.2,1), opacity 0.4s cubic-bezier(.4,0,.2,1)'
  void el.offsetHeight
  el.style.height = '0'
  el.style.opacity = '0'
  el.addEventListener('transitionend', function handler(e) {
    if (e.propertyName === 'height') {
      el.removeEventListener('transitionend', handler)
      done()
    }
  })
}
</script>

<template>
  <section class="hero-section">
    <div class="overlay"/>

    <!-- TEXT INSIDE THE BLUR -->
    <div class="bottom-blur">
      <v-container>
        <v-row
          justify="center"
          align="center"
        >
          <v-col
            cols="12"
            md="8"
            class="text-white d-flex flex-column align-center justify-center"
          >
            <h1
              class="verse font-weight-bold mb-4"
              style="font-size: clamp(20px, 4vw, 36px);"
            >
              Come to me, all you who are weary and burdened, and I will give you rest.
            </h1>
            <h2
              class="subtitle"
              style="font-size: clamp(18px, 2.5vw, 28px);"
            >
              — Matthew 11:28
            </h2>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </section>

  <div class="advices-boxes-section">
    <div class="question-title">
      What's in your heart?
    </div>
    <div class="advice-options">
      <div
        v-for="(advice, i) in adviceList"
        :key="advice.title"
        class="advice-box"
        @click="toggleBox(i)"
        :aria-expanded="openIndex === i"
        style="flex-direction: column; transition: box-shadow 0.2s;"
      >
        <div class="advice-row"
             style="display: flex; width: 100%; justify-content: space-between; align-items: center;">
          <span>{{ advice.title }}</span>
          <span class="dropdown-arrow" :class="{ open: openIndex === i }">&#9660;</span>
        </div>
        <transition
          @before-enter="beforeEnter"
          @enter="enter"
          @after-enter="afterEnter"
          @before-leave="beforeLeave"
          @leave="leave"
        >
          <div v-if="openIndex === i" class="advice-collapse-wrapper">
            <div class="advice-content">
              {{ advice.content }}
            </div>
          </div>
        </transition>
      </div>
    </div>
  </div>
  <div class="advices-footer">
    <a href="https://www.ikea.com/ch/en/p/fenomen-unscented-pillar-candle-white-70528404/" target="_blank"
       rel="noopener">
      <img :src="candleImg" alt="Candle" class="footer-candle"/>
    </a>
    <span class="footer-text">Even in the silence, God hears your heart</span>
  </div>
</template>

