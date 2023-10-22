<template>
  <q-layout
    :view="$q.platform.is.electron ? 'hHh lpR lFf' : 'lHh lpR lFf'"
    :class="{
      'bg-dark-9': $q.dark.isActive,
      'bg-grey-1': !$q.dark.isActive,
    }"
  >
    <q-header
      :bordered="!$q.dark.isActive"
      :class="{
        'bg-dark-8 text-grey-2': $q.dark.isActive,
        'bg-white text-grey-8': !$q.dark.isActive,
      }"
      height-hint="64"
    >
      <q-bar v-if="$q.platform.is.electron" class="q-electron-drag">
        <q-icon class="q-pr-sm" name="img:img/favicon.png"/>
        <div class="cursor-pointer">
          File
          <q-menu>
            <q-list dense style="min-width: 100px">
              <q-item v-close-popup clickable>
                <q-item-section>Open...</q-item-section>
              </q-item>
              <q-item v-close-popup clickable>
                <q-item-section>New</q-item-section>
              </q-item>

              <q-separator/>

              <q-item clickable>
                <q-item-section>Preferences</q-item-section>
                <q-item-section side>
                  <q-icon name="keyboard_arrow_right"/>
                </q-item-section>

                <q-menu anchor="top end" self="top start">
                  <q-list>
                    <q-item v-for="n in 3" :key="n" dense clickable>
                      <q-item-section>Submenu Label</q-item-section>
                      <q-item-section side>
                        <q-icon name="keyboard_arrow_right"/>
                      </q-item-section>
                      <q-menu auto-close anchor="top end" self="top start">
                        <q-list>
                          <q-item v-for="d in 3" :key="d" dense clickable>
                            <q-item-section>3rd level Label</q-item-section>
                          </q-item>
                        </q-list>
                      </q-menu>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-item>

              <q-separator/>

              <q-item v-close-popup clickable @click="closeApp">
                <q-item-section>Quit</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </div>
        <div class="cursor-pointer q-electron-drag--exception">Edit</div>
        <div class="cursor-pointer q-electron-drag--exception">View</div>
        <div class="cursor-pointer q-electron-drag--exception">Window</div>
        <div class="cursor-pointer q-electron-drag--exception">Help</div>
        <q-space/>
        <q-btn
          class="q-mr-lg"
          flat
          dense
          :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
          @click="$q.dark.toggle()"
        >
          <q-tooltip>
            {{ $q.dark.isActive ? "Enter Light Mode" : "Enter Dark Mode" }}
          </q-tooltip>
        </q-btn>
        <q-btn dense flat icon="minimize" @click="minimize"/>
        <q-btn dense flat icon="crop_square" @click="toggleMaximize"/>
        <q-btn dense flat icon="close" @click="closeApp"/>
      </q-bar>
      <q-toolbar v-else class="GNL__toolbar bg-transparent">
        <!-- <q-btn
          flat
          dense
          round
          aria-label="Menu"
          icon="menu"
          class="q-mr-sm"
          @click="toggleLeftDrawer"
        /> -->

        <q-toolbar-title v-if="$q.screen.gt.xs" shrink class="row items-center no-wrap">
          <q-img fit="fill" width="150px" src="/img/logo-black.png"/>
        </q-toolbar-title>

        <q-space/>

        <q-input
          v-model="search"
          borderless
          dense
          color="bg-grey-7"
          placeholder="Search here"
        >
          <template #prepend>
            <q-icon v-if="search === ''" name="search"/>
            <q-icon v-else name="clear" class="cursor-pointer" @click="search = ''"/>
          </template>
        </q-input>

        <q-space/>

        <div class="q-gutter-sm row items-center no-wrap">
          <q-btn
            round
            flat
            dense
            :color="$q.dark.isActive ? 'grey-5' : 'grey-8'"
            :icon="$q.fullscreen.isActive ? 'fullscreen_exit' : 'fullscreen'"
            @click="$q.fullscreen.toggle()"
          >
            <q-tooltip>
              {{ $q.fullscreen.isActive ? "Exit Fullscreen" : "Go Fullscreen" }}
            </q-tooltip>
          </q-btn>
          <q-btn
            round
            flat
            dense
            :color="$q.dark.isActive ? 'grey-5' : 'grey-8'"
            :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
            @click="$q.dark.toggle()"
          >
            <q-tooltip>
              {{ $q.dark.isActive ? "Enter Light Mode" : "Enter Dark Mode" }}
            </q-tooltip>
          </q-btn>
          <q-btn
            round
            dense
            flat
            :color="$q.dark.isActive ? 'grey-5' : 'grey-8'"
            icon="notifications"
          >
            <q-badge color="red" text-color="white" floating>2</q-badge>
            <q-tooltip>Notifications</q-tooltip>
          </q-btn>
          <q-btn round flat>
            <q-avatar size="26px">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png" alt="user avatar"/>
            </q-avatar>
            <q-menu>
              <q-list class="q-px-sm">
                <q-item v-close-popup clickable>
                  <q-item-section>New tab</q-item-section>
                </q-item>
                <q-item v-close-popup clickable>
                  <q-item-section>New incognito tab</q-item-section>
                </q-item>
                <q-separator/>
                <q-item v-close-popup>
                  <q-item-section>
                    <q-btn
                      v-close-popup
                      color="yellow-10"
                      label="Logout"
                      size="sm"
                      @click="logout"
                    />
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
            <q-tooltip>Account</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      behavior="desktop"
      mini
      show-if-above
      :bordered="!$q.dark.isActive"
      :class="{
        'bg-dark-8': $q.dark.isActive,
        'bg-white': !$q.dark.isActive,
      }"
      color="yellow-10"
      :width="280"
    >
      <!-- <q-scroll-area class="fit"> -->
      <q-list class="flex column full-height">
        <q-item
          v-for="link in links"
          :key="link.text"
          v-ripple
          clickable
          :to="link.to"
        >
          <q-item-section class="flex items-center justify-center" avatar>
            <q-icon
              :name="link.icon"
              :class="{ 'text-dark-6': $q.dark.isActive, 'text-grey-8': !$q.dark.isActive}"/>
            <q-tooltip
              :class="{
                'bg-dark-9 border': $q.dark.isActive,
                'bg-grey-6': !$q.dark.isActive,
              }"
              class="rounded-sm"
              anchor="center right"
              self="center left"
            >
              {{ link.text }}
            </q-tooltip>
          </q-item-section>
        </q-item>
        <q-space/>
        <q-item
          :class="{
            'text-grey-6': $q.dark.isActive,
            'text-grey-8': !$q.dark.isActive,
          }"
          clickable
        >
          <q-item-section class="flex items-center justify-center" avatar>
            <q-icon name="settings"/>
          </q-item-section>
          <q-tooltip
            :class="{
              'bg-dark-9 border': $q.dark.isActive,
              'bg-grey-6': !$q.dark.isActive,
            }"
            class="rounded-sm"
            anchor="center right"
            self="center left"
          >
            Settings
          </q-tooltip>
        </q-item>
      </q-list>
      <!-- </q-scroll-area> -->
    </q-drawer>

    <q-page-container>
      <router-view/>
    </q-page-container>
    <q-footer
      bordered
      class="text-white"
      :class="{
        'bg-dark-9 text-grey-2': $q.dark.isActive,
        'bg-grey-1 text-grey-8': !$q.dark.isActive,
      }"
    >
      <div class="text-grey-8 q-py-md q-px-sm">&copy; 2022 | QuizLand</div>
    </q-footer>
  </q-layout>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useAuth} from '../composables/auth';
import {useQuasar} from 'quasar';

const $q = useQuasar();
const leftDrawerOpen = true;
const {logout} = useAuth();
const search = ref('');

const links = [
  {icon: 'dashboard', text: 'Dashboard', to: {name: 'dashboard'}},
  {icon: 'description', text: 'Tests', to: {name: 'test.list'}},
  {icon: 'emoji_events', text: 'Leaderboard', to: ''},
  {icon: 'history', text: 'My Performance', to: ''},
];

const minimize = () => {
  if (process.env.MODE === 'electron') {
    window.myWindowAPI.minimize();
  }
}

const toggleMaximize = () => {
  if (process.env.MODE === 'electron') {
    window.myWindowAPI.toggleMaximize();
  }
}

const closeApp = () => {
  if (process.env.MODE === 'electron') {
    window.myWindowAPI.close();
  }
}
</script>

<style lang="scss">
.q-item.q-router-link--active,
.q-item--active {
  color: #fff !important;
  border-left: 2px solid $primary;
}

.q-bar--standard > div {
  font-size: 13.5px;
}
</style>
