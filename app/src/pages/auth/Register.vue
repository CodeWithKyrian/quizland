<template>
  <q-layout view="hHh Lpr fFf">
    <q-page-container>
      <q-page class="row items-sm-center items-xs-stretch justify-center bg-orange-1">
        <q-card class="col-12 col-sm-10 col-md-9 shadow-11">
          <q-card-section class="q-pa-none full-height" :horizontal="$q.screen.gt.xs">
            <q-card-section
                class="col-6 q-pa-lg bg-yellow-10 flex justify-center items-center text-white">
              <q-img src="/img/kids.png" fit="contain" class="side-img"></q-img>
            </q-card-section>

            <q-separator vertical/>

            <q-card-section
                class="col-12 col-sm-6 q-pa-lg flex flex-col items-center justify-center"
            >
              <q-form class="full-width" style="max-width: 400px;" @submit.prevent="register">
                <div class="text-h4">Sign up for QuizLand</div>
                <div class="text-subtitle1 q-mb-lg">Heyyyyy, please provide your details to register</div>
                <div class="q-py-xs">
                  <q-input
                      v-model="user.name"
                      filled
                      label="Fullname"
                      color="yellow-10"
                      class="full-width"
                      :rules="[val => !!val || 'This field is required']"
                      :error="!!errors.name"
                      :error-message="errors.name"
                  >
                    <template #prepend>
                      <q-icon name="person"/>
                    </template>
                  </q-input>
                </div>
                <div class="q-py-xs">
                  <q-input
                      v-model="user.email"
                      filled
                      label="Email"
                      type="email"
                      color="yellow-10"
                      class="full-width"
                      :rules="[val => !!val || 'This field is required']"
                      :error="!!errors.email"
                      :error-message="errors.email"
                  >
                    <template #prepend>
                      <q-icon name="email"/>
                    </template>
                  </q-input>
                </div>
                <div class="q-py-xs">
                  <q-input
                      v-model="user.password"
                      color="yellow-10"
                      filled
                      label="Password"
                      :type="isPwd ? 'password' : 'text'"
                      :rules="[val => !!val || 'This field is required']"
                      :error="!!errors.password"
                      :error-message="errors.password"
                  >
                    <template #prepend>
                      <q-icon name="lock"/>
                    </template>
                    <template #append>
                      <q-icon
                          :name="isPwd ? 'visibility_off' : 'visibility'"
                          class="cursor-pointer"
                          @click="isPwd = !isPwd"
                      />
                    </template>
                  </q-input>
                </div>
                <div class="q-py-xs">
                  <q-input
                      v-model="user.password_confirmation"
                      color="yellow-10"
                      filled
                      label="Confirm Password"
                      :type="isPwd ? 'password' : 'text'"
                      :rules="[val => val && val == user.password || 'Passwords must match']"
                      lazy-rules
                  >
                    <template #prepend>
                      <q-icon name="lock"/>
                    </template>
                    <template #append>
                      <q-icon
                          :name="isPwd ? 'visibility_off' : 'visibility'"
                          class="cursor-pointer"
                          @click="isPwd = !isPwd"
                      />
                    </template>
                  </q-input>
                </div>
                <div class="q-py-md">
                  <q-btn
                      color="yellow-10"
                      type="submit"
                      size="lg"
                      class="full-width"
                      label="Register"
                      :loading="submitting"
                  >
                    <template #loading>
                      <q-spinner-bars size="sm"/>
                    </template>
                  </q-btn>
                </div>
              </q-form>
              <div class="q-pt-sm">
                Already a Quizlander?
                <router-link class="text-yellow-10" :to="{ name: 'login' }">Login</router-link>
              </div>
            </q-card-section>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import {ref} from 'vue';
import {useAuth} from 'src/composables/auth';

const isPwd = ref(true)
const {user, submitting, errors, register} = useAuth()

</script>

<style lang="scss">
.side-img {
  max-height: 350px;
  @media (max-width: $breakpoint-sm-min) {
    max-height: 30vh;
  }
}
</style>
