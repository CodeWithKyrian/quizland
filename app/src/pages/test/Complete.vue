<template>
  <q-page class="flex column items-center justify-center" padding>
    <div
        class="q-py-md"
    >Congratulations on completing the test. You will be redirected to the correction page soon...
    </div>
    <q-btn color="yellow-10" label="Redirect Now" @click="redirect"/>
  </q-page>
</template>

<script setup>
import {onMounted} from 'vue';
import {useRoute, useRouter} from 'vue-router';

const route = useRoute()
const router = useRouter()

const redirect = () => {
  router.push({name: 'test.results', params: {id: route.params.id}})
  clearTimeout()
}

onMounted(() => {
  setTimeout(redirect, 4000);
})
</script>
