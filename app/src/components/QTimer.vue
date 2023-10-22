<template>
  <q-card class="bg-primary text-white" flat bordered>
    <q-card-section class="q-pa-sm flex items-center">
      <q-icon class="q-pr-sm" size="xs" name="timer" />
      <span class="text-subtitle text-bold"
      >{{ timer.hour }}:{{ timer.minute }}:{{ timer.second }}</span
      >
    </q-card-section>
  </q-card>
</template>

<script setup>
import { timer } from 'src/composables/test';
</script>
