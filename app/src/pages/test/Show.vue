<!-- eslint-disable vue/no-v-html -->
<template>
  <q-page class="pane row" padding>
    <div class="pane-left col-12 col-md-8 q-px-md">
      <div class="pane-header flex justify-between q-py-md">
        <div class="flex items-center">
          <div class="text-h6 q-pr-sm" style="text-transform: capitalize">
            {{ testStore.test.title }}
          </div>
          <q-separator vertical inset/>
          <div class="text-subtitle2 q-pl-sm">
            {{ testStore.test.questions_count }} Questions
          </div>
        </div>
        <q-timer></q-timer>
      </div>
      <div class="pane-body">
        <q-card class="full-height shadow-md" flat :bordered="!$q.dark.isActive">
          <q-carousel ref="carousel" v-model="slide" transition-next="slide-left"
                      transition-prev="slide-right" height="100%" animated>
            <q-carousel-slide
                v-for="(question, index) in testStore.questions" :key="question.id" :name="index + 1">
              <q-card class="question" flat>
                <q-card-section class="q-pa-xs-none q-pa-sm-sm">
                  <div class="text-body2 text-bold flex no-wrap">
                    <span class="q-pr-md">{{ index + 1 }}.</span>
                    <span class v-html="question.body"></span>
                  </div>
                  <div
                      class="q-px-xs-none q-px-sm-sm q-pt-md" style="padding-bottom: 64px">
                    <div
                        v-for="option in question.options" :key="option.id" class="q-py-xs">
                      <q-item dense tag="label"
                              :class="{'text-grey-5': $q.dark.isActive, 'text-grey-9': !$q.dark.isActive}">
                        <q-item-section avatar>
                          <q-radio
                              v-model="question.selected"
                              color="primary"
                              size="xs"
                              checked-icon="task_alt"
                              unchecked-icon="panorama_fish_eye"
                              :val="option.id"
                          />
                        </q-item-section>
                        <q-item-section>
                          <q-item-label class="text-subtitle2 text-weight-regular">
                            <span v-html="option.body"></span>
                          </q-item-label>
                        </q-item-section>
                      </q-item>
                    </div>
                  </div>
                </q-card-section>
              </q-card>
            </q-carousel-slide>

            <template #control>
              <q-carousel-control
                  position="bottom-right" :offset="[18, 14]"
                  class="flex justify-between full-width q-px-md">
                <q-btn
                    color="primary" icon="arrow_back" label="previous"
                    @click="$refs.carousel.previous()"
                />
                <q-btn
                    color="primary" icon-right="arrow_forward"
                    label="next" @click="$refs.carousel.next()"
                />
              </q-carousel-control>
            </template>
          </q-carousel>
        </q-card>
      </div>
    </div>
    <div class="pane-right col-12 col-md-4 q-px-md">
      <q-card class="pane-body shadow-md" flat :bordered="!$q.dark.isActive">
        <q-card-section class="head q-pa-md">
          <q-item class="no-padding q-mb-sm">
            <q-item-section avatar>
              <q-avatar size="xl">
                <img src="https://cdn.quasar.dev/img/boy-avatar.png" alt="avatar"/>
              </q-avatar>
            </q-item-section>

            <q-item-section>
              <q-item-label>Obikwelu Kyrian</q-item-label>
              <div class="q-mt-sm">
                <q-item-label caption>
                  Subject:
                  <strong>{{ testStore.test.subject }}</strong>
                </q-item-label>
                <q-item-label caption>
                  Attempted:
                  <strong>{{ attempted }}/{{ testStore.questions.length }}</strong>
                </q-item-label>
              </div>
            </q-item-section>
          </q-item>

          <q-separator/>
          <div class="text-h6">Index of Questions</div>
          <div class="row justify-center q-mt-md">
            <div class="flex col-6">
              <div class="bg-primary q-mr-sm rounded-borders" style="width: 20px; height: 20px"></div>
              <span>Answered</span>
            </div>
            <div class="flex col-6">
              <div class="bg-grey-4 q-mr-sm rounded-borders" style="width: 20px; height: 20px"></div>
              <span>Unanswered</span>
            </div>
          </div>
        </q-card-section>
        <q-card-section class="body q-pa-md">
          <q-scroll-area class="full-height">
            <div class="row">
              <div
                  v-for="(question, index) in testStore.questions" :key="question.id" class="col-md-2 col-3 q-pa-sm">
                <q-btn
                    unelevated
                    :text-color="question.selected ? 'white' : $q.dark.isActive ? 'grey-3' : 'grey-8'"
                    :color=" question.selected ? 'primary' : $q.dark.isActive ? 'dark-7' : 'grey-3'"
                    class="full-width text-subtitle2"
                    :label="index + 1" dense
                    @click="$refs.carousel.goTo(index + 1)"
                >
                </q-btn>
              </div>
            </div>
          </q-scroll-area>
        </q-card-section>
        <q-card-section class="footer q-pa-md">
          <q-btn class="full-width" color="primary" label="Submit Exam" @click="endTest"></q-btn>
        </q-card-section>
      </q-card>
    </div
    >
  </q-page>
</template>
<!--eslint-enable-->

<script setup>
import {computed, onMounted, ref} from 'vue';
import {onBeforeRouteLeave} from 'vue-router';
import {useTestStore} from 'stores/test-store';
import {useTest} from 'src/composables/test';
import QTimer from 'src/components/QTimer.vue';
import {useQuasar} from 'quasar'

const $q = useQuasar()
const slide = ref(1);
const testStore = useTestStore();
const {loadTest, endTest, isSubmitting} = useTest();

onBeforeRouteLeave((to, from, next) => {
  if (isSubmitting.value) {
    next();
    return;
  }

  $q.dialog({
    title: 'Confirm',
    message: 'Do you really want to leave? You have unsaved changes!',
    cancel: true,
    persistent: true,
  })
      .onOk(() => next())
      .onCancel(() => next(false))
});

onMounted(() => {
  loadTest();

  let pane = document.querySelector('.pane');
  let paneLeftHeader = pane.querySelector('.pane-left .pane-header');
  let paneLeftBody = pane.querySelector('.pane-left .pane-body');
  let paneRightBody = pane.querySelector('.pane-right .pane-body');

  let subPaneHead = paneRightBody.querySelector('.head');
  let subPaneBody = paneRightBody.querySelector('.body');
  let subPaneFooter = paneRightBody.querySelector('.footer');

  const paneResizeObserver = new ResizeObserver(() => {
    let paneMinHeight = parseInt(pane.style.minHeight.slice(0, -2));

    paneLeftBody.style.height = `${paneMinHeight - paneLeftHeader.offsetHeight - 35}px`;

    paneRightBody.style.height = `${paneMinHeight - 35}px`;

    subPaneBody.style.height = `${
        paneRightBody.offsetHeight - subPaneHead.offsetHeight - subPaneFooter.offsetHeight
    }px`;
  });

  paneResizeObserver.observe(pane);
});

const attempted = computed(() => {
  return testStore.questions.filter((question) => question.selected).length;
});
</script>
