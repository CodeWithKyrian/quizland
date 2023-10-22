<template>
  <q-page class="pane row" padding>
    <div class="pane-left col-12 col-md-8 q-px-md">
      <div class="pane-header flex justify-between q-py-md">
        <div class="flex items-center">
          <div class="text-h6 q-pr-sm">{{ result.test.subject}} Results - {{result.test.title}}</div>
        </div>
        <q-card class="bg-primary text-white" flat :bordered="!$q.dark.isActive">
          <q-card-section class="q-pa-sm flex items-center">
            <q-icon class="q-pr-sm" size="xs" name="timer"/>
            <span class="text-subtitle text-bold">{{ result.score}}%</span>
          </q-card-section>
        </q-card>
      </div>
      <div class="pane-body">
        <q-scroll-area style="height: 100%">
          <div class="row q-py-md">
            <div v-for="resultValue in result.values" :key="resultValue.question" class="col-12 q-py-sm">
              <q-card class="shadow-md" flat :bordered="!$q.dark.isActive">
                <q-card-section>
                  <div class="text-body2 text-bold">
                    <q-icon
                        :name="resultValue.correct ? 'task_alt' : 'highlight_off'"
                        :color="resultValue.correct  ? 'green' : 'red'"
                    />
                    {{ resultValue.question }}
                  </div>
                  <div class="q-pa-md">
                    <div v-for="(option, index) in resultValue.options" :key="index">
                      <q-item
                          dense
                          :class="{ 'text-grey-5': $q.dark.isActive, 'text-grey-9': !$q.dark.isActive, }">
                        <q-item-section avatar>
                          <q-checkbox
                              v-model="resultValue.selections"
                              size="sm"
                              dense
                              :checked-icon="option === resultValue.answer ? 'task_alt' : 'highlight_off'"
                              unchecked-icon="panorama_fish_eye"
                              :val="option"
                              :color="option === resultValue.answer  ? 'green' : 'red'"
                              disable
                          />
                        </q-item-section>
                        <q-item-section>
                          <q-item-label class="text-subtitle2 text-weight-regular">
                            {{ option }}
                          </q-item-label>
                        </q-item-section>
                      </q-item>
                    </div>
                  </div>
                </q-card-section>
              </q-card>
            </div>
          </div>
        </q-scroll-area>
      </div>
    </div>
    <div class="pane-right col-12 col-md-4 q-px-md">
      <q-card class="pane-body shadow-md" flat :bordered="!$q.dark.isActive">
        <q-item>
          <q-item-section avatar>
            <q-avatar size="xl">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png" alt=""/>
            </q-avatar>
          </q-item-section>

          <q-item-section>
            <q-item-label>Obikwelu Kyrian</q-item-label>
            <div class="q-mt-sm">
              <q-item-label caption>
                Attempted:
                <strong>{{result.attempts}}/{{  }}</strong>
              </q-item-label>
            </div>
          </q-item-section>
        </q-item>

        <q-separator/>

        <q-card-section class="body q-pa-md">
          <q-scroll-area class="full-height">
            <div class="row">
              <div
                  v-for="(resultValue, index) in result.values" :key="resultValue.question" class="col-md-2 col-3 q-pa-sm">
                <q-btn
                    unelevated
                    :text-color="resultValue.correct ? 'white' : $q.dark.isActive ? 'grey-3' : 'grey-8'"
                    :color=" resultValue.correct ? 'primary' : $q.dark.isActive ? 'dark-7' : 'grey-3'"
                    class="full-width text-subtitle2"
                    :label="index + 1" dense
                    @click="$refs.carousel.goTo(index + 1)">
                </q-btn>
              </div>
            </div>
          </q-scroll-area>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from 'vue-router';
import {api} from 'boot/axios';


const {params} = useRoute()

const result = ref({
  test: {}
})

onMounted(async () => {
  const response = await api.get(`/api/tests/${params.id}/results`)
  result.value = response.data
})



onMounted(() => {
  let pane = document.querySelector('.pane');
  let paneLeftHeader = pane.querySelector('.pane-left .pane-header');
  let paneLeftBody = pane.querySelector('.pane-left .pane-body');
  let paneRightBody = pane.querySelector('.pane-right .pane-body');

  const paneResizeObserver = new ResizeObserver(() => {
    let paneMinHeight = parseInt(pane.style.minHeight.slice(0, -2));
    paneLeftBody.style.height = `${paneMinHeight - paneLeftHeader.offsetHeight - 35}px`;
    paneRightBody.style.height = `${paneMinHeight - 35}px`;
  });

  paneResizeObserver.observe(pane);
});
</script>
