<script lang="ts" setup>
import {ref} from "vue";
import authService from "@/services/auth.service.ts";

const username = ref('')
const password = ref('')
const passwordConfirm = ref('')

const errors = ref<string[]>([])
const successful = ref(false)

function register(event: Event){
  event.preventDefault()
  errors.value = []

  if (3 > username.value.length) {
    errors.value.push('Email must be at least 3 chars.')
  }

  if (3 > password.value.length) {
    errors.value.push('Password must be at least 3 chars.')
  }

  if (password.value !== passwordConfirm.value) {
    errors.value.push('Password and confirm does not match.')
  }

  if (0 < errors.value.length) {
    return
  }

  authService.post('/api/register', {email: username.value, plainPassword: password.value})
    .then(() => successful.value = true)
    .catch(error => error.response.data.violations.forEach(
      (violation: {message: string}) => errors.value.push(violation.message)
    ))
    .catch(() => errors.value.push('Request went wrong.'))
}
</script>

<template>
  <div class="text-gray-300 p-5" v-if="!successful">
    <div class="flex place-content-center">
      <form class="w-md grid grid-cols-1 gap-4">
        <p class="text-center">Register to Shroomer</p>
        <input v-model="username" type="text" placeholder="Username" class="text-base border text-center"/>
        <input v-model="password" type="password" placeholder="Password" class="text-base border text-center"/>
        <input v-model="passwordConfirm" type="password" placeholder="Password Confirmation" class="text-base border text-center"/>
        <button @click="register($event)" class="border cursor-pointer">Register</button>
      </form>
    </div>

    <div v-if="errors.length" class="p-5">
      <p class="text-center"><b>Please correct the following error(s):</b></p>
      <p class="text-center" v-for="error in errors">- {{ error }}</p>
    </div>
  </div>

  <div class="text-gray-300 p-5" v-else>
    <div class="flex place-content-center">
      <p>Account created, proceed to login.</p>
    </div>
  </div>
</template>

<style>
</style>
