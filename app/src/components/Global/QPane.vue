<template>
  <div :class="panePositionClass">
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';

const props = defineProps({
  left: Boolean,
  right: Boolean,
});

const panePositionClass = computed(() => {
  if (props.left) {
    return 'pane-left col-12 col-md-8 q-px-md';
  } else if (props.right) {
    return 'pane-right col-12 col-md-4 q-px-md';
  } else {
    return '';
  }
});
</script>

<style>
</style>
