<script setup lang="ts">
import {onMounted, ref} from "vue";
import {RouterLink} from "vue-router";
import authService from "@/services/auth.service.ts";

const zones = ref([])

onMounted(async () => {
  zones.value = await authService.get('/api/zones')
    .then(response => response.data)
    .then(data => {
      return data["member"]
    })
})
</script>

<template>
  <div class="flex h-16 items-center flex place-content-center">
  <div v-if="zones.length > 0" class="ml-10 flex space-x-4">
    <div v-for="zone in zones" :key="zone['id']" >
      <RouterLink class="px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white"
                  :to="{name: 'zone', params: {'id': zone['id']}}">{{ zone['name'] }}</RouterLink>
    </div>
  </div>
  </div>
</template>

<style>
</style>
