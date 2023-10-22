<template>
  <q-page class="pane row" padding>
    <div class="pane-center col-12">
      <div class="pane-header flex justify-between q-pa-md">
        <div class="flex items-center">
          <div class="text-h5 q-pr-sm">All Tests</div>
          <q-separator vertical inset/>
          <div class="text-subtitle q-pl-sm">{{ tests.length }} in total</div>
        </div>
      </div>
      <div class="pane-body">
        <q-scroll-area class="full-height">
          <div class="row q-pa-sm fit">
            <div
              v-for="test in tests"
              :key="test.id"
              class="q-pa-sm col-xs-12 col-sm-6 col-md-4"
            >
              <q-card
                class="q-pa-md"
                :class="{
                  'no-shadow': $q.dark.isActive,
                  'shadow-xl': !$q.dark.isActive,
                }"
                :bordered="!$q.dark.isActive"
              >
                <q-card-section class="q-pa-sm">
                  <div class="row items-center no-wrap">
                    <div class="col">
                      <div
                        class="text-h6"
                        :class="{
                          'text-grey-5': $q.dark.isActive,
                          'text-grey-9': !$q.dark.isActive,
                        }"
                        style="text-transform: capitalize"
                      >
                        {{ test.title }}
                      </div>
                    </div>

                    <div class="col-auto">
                      <q-btn color="grey-7" round flat icon="more_vert">
                        <q-menu cover auto-close>
                          <q-list>
                            <q-item clickable>
                              <q-item-section>Send Feedback</q-item-section>
                            </q-item>
                            <q-item clickable>
                              <q-item-section>Share</q-item-section>
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-btn>
                    </div>
                  </div>
                </q-card-section>

                <q-card-section
                  class="q-pa-sm"
                  :class="{
                    'text-grey-6': $q.dark.isActive,
                    'text-grey-8': !$q.dark.isActive,
                  }"
                >
                  <div class="row q-py-sm">
                    <div class="col-6">
                      <q-icon
                        :color="$q.dark.isActive ? 'grey-6' : 'grey-8'"
                        class="q-pr-sm"
                        name="calendar_month"
                      />
                      <span>{{ test.starts_at }}</span>
                    </div>
                    <div class="col-6">
                      <q-icon
                        :color="$q.dark.isActive ? 'grey-6' : 'grey-8'"
                        class="q-pr-sm"
                        name="calendar_month"
                      />
                      <span>{{ test.ends_at }}</span>
                    </div>
                  </div>
                  <div class="row q-py-sm">
                    <div class="col-6">
                      <q-icon
                        :color="$q.dark.isActive ? 'grey-6' : 'grey-8'"
                        class="q-pr-sm"
                        name="timer"
                      />
                      <span>{{ test.duration }}</span>
                    </div>
                    <div class="col-6">
                      <q-icon
                        :color="$q.dark.isActive ? 'grey-6' : 'grey-8'"
                        class="q-pr-sm"
                        name="lightbulb"
                      />
                      <span>{{ test.questions_count }} Questions</span>
                    </div>
                  </div>
                </q-card-section>

                <q-card-actions class="q-pa-sm row">
                  <div class="col-6 q-pr-sm">
                    <q-btn
                      unelevated
                      outline
                      class="rounded-md full-width"
                      :color="$q.dark.isActive ? 'grey-6' : 'yellow-10'"
                    >Decline
                    </q-btn
                    >
                  </div>
                  <div class="col-6 q-pl-sm">
                    <q-btn
                      unelevated
                      class="rounded-md full-width"
                      color="yellow-10"
                      @click="startTest(test.id)"
                    >Start Test
                    </q-btn
                    >
                  </div>
                </q-card-actions>
              </q-card>
            </div>
          </div>
        </q-scroll-area>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import {onMounted} from 'vue';
import {useTest} from 'src/composables/test';

const {tests, getTests, startTest} = useTest();

onMounted(() => {
  getTests();

  let pane = document.querySelector('.pane');
  let paneCenterHeader = pane.querySelector('.pane-center .pane-header');
  let paneCenterBody = pane.querySelector('.pane-center .pane-body');

  const paneResizeObserver = new ResizeObserver(() => {
    let paneMinHeight = parseInt(pane.style.minHeight.slice(0, -2));

    paneCenterBody.style.height = `${paneMinHeight - paneCenterHeader.offsetHeight - 35}px`;
  });

  paneResizeObserver.observe(pane);
});
</script>
