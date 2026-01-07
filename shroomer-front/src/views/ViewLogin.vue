<script lang="ts" setup>
import {ref} from "vue";
import authService from "@/services/auth.service.ts";
import router from "@/router";

const username = ref('')
const password = ref('')
const errors = ref<string[]>([])

function login(event: Event){
  event.preventDefault()
  errors.value = []

  if (3 >= username.value.length) {
    errors.value.push('Username must be at least 3 chars.')
  }

  if (3 >= password.value.length) {
    errors.value.push('Password must be at least 3 chars.')
  }

  if (0 < errors.value.length) {
    return
  }

  authService.login(username.value, password.value)
    .then(() => router.push('/shroomer'))
    .catch(error => errors.value.push(error.response.data.message))
    .catch(() => errors.value.push('Request went wrong.'))
}
</script>

<template>
  <div class="text-gray-300 p-5">
    <div class="flex place-content-center">
      <form class="w-md grid grid-cols-1 gap-4">
        <p class="text-center">Proceed to login</p>
        <input v-model="username" placeholder="Username" class="text-base border text-center"/>
        <input v-model="password" type="password" placeholder="Password"  class="text-base border text-center"/>
        <button @click="login($event)" class="border cursor-pointer">Login</button>
      </form>
  </div>

    <div v-if="errors.length" class="p-5">
      <p class="text-center"><b>Please correct the following error(s):</b></p>
      <p class="text-center" v-for="error in errors">- {{ error }}</p>
    </div>
  </div>
</template>

<style>
</style>
