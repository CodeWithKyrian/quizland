<template>
  <q-page class="pane row" padding>
    <div class="pane-left col-12 col-md-8 q-px-md">
      <div class="pane-header flex justify-between q-py-md">
        <div class="flex items-center">
          <div class="text-h6 q-pr-sm">Dashboard</div>
        </div>
        <q-chip removable outline square size="md" color="positive" text-color="white">
          New test is live.
          <router-link class="q-mx-sm text-green" to="/tests">Solve it</router-link>
        </q-chip>
      </div>
      <div class="pane-body">
        <q-card class="bg-yellow-10 text-white">
          <q-card-section horizontal>
            <q-card-section>
              <div class="q-pa-sm">
                <div class="text-h6">Hi, {{ auth.user.name }}</div>
              </div>
              <div class="q-pa-sm">
                <div class="text-subtitle2">
                  You have 4 tests to complete. You already completed 80% of your
                  available tests. Your current Performance is Excellent
                </div>
              </div>
              <div class="q-pa-sm">
                <q-btn color="white" text-color="yellow-10" label="Continue Learning" />
              </div>
            </q-card-section>
            <q-img class="co" src="/img/student.svg" />
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div class="pane-right col-12 col-md-4 q-px-md">
      <div class="pane-header"></div>
      <div class="pane-body">
        <q-card>
          <q-card-section horizontal>
            <q-card-section class="col-6">
              <div>Overall Score</div>
              <div class="text-yellow-10 text-h4">49</div>
            </q-card-section>
            <q-separator vertical />
            <q-card-section class="col-6">
              <div>Accuracy (%)</div>
              <div class="text-yellow-10 text-h4">68</div>
            </q-card-section>
          </q-card-section>
          <q-separator />
          <q-card-section horizontal>
            <q-card-section class="col-6">
              <div>Attempted</div>
              <div class="text-yellow-10 text-h4">43</div>
            </q-card-section>
            <q-separator vertical />
            <q-card-section class="col-6">
              <div>Performance (%)</div>
              <div class="text-yellow-10 text-h4">12</div>
            </q-card-section>
          </q-card-section>
        </q-card>
      </div>
    </div></q-page
  >
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from 'stores/auth-store';

const auth = useAuthStore();

onMounted(() => {
  let pane = document.querySelector('.pane');
  let paneLeftHeader = pane.querySelector('.pane-left .pane-header');
  let paneLeftBody = pane.querySelector('.pane-left .pane-body');
  let paneRightBody = pane.querySelector('.pane-right .pane-body');

  const paneResizeObserver = new ResizeObserver(() => {
    let paneMinHeight = parseInt(pane.style.minHeight.slice(0, -2));
    pane.style.height = `${paneMinHeight}px`;

    paneLeftBody.style.height = `${paneMinHeight - paneLeftHeader.offsetHeight - 35}px`;

    paneRightBody.style.height = `${paneMinHeight - 35}px`;
  });

  paneResizeObserver.observe(pane);
});
</script>

<style>
.pane-header {
  min-height: 68px;
}
</style>
