<template>
  <router-view/>
</template>

<script setup lang="ts">
import {onBeforeMount, ref, watch} from 'vue';
import {useAuth} from './composables/auth';
import {useQuasar} from 'quasar';

const {init} = useAuth();
const $q = useQuasar();

const getThemeFromLocalStorage = () => {
  // if the user already changed the theme, use it
  const item = window.localStorage.getItem('dark');
  if (item) {
    return JSON.parse(item);
  }

  // else return their preferences
  return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
};

const dark = ref(getThemeFromLocalStorage());

watch(
    () => $q.dark.isActive,
    (val) => {
      window.localStorage.setItem('dark', JSON.stringify(val));
    }
);

onBeforeMount(() => {
  init();

  $q.dark.set(dark.value);
});
</script>
