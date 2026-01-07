<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import MenuZones from "@/components/MenuZones.vue";
import UserInfos from "@/components/UserInfos.vue";

import authService from "@/services/auth.service.ts";
</script>

<template>
  <header>
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-auto items-center justify-between">
          <RouterLink to="/shroomer" class="text-gray-200">Shroomer</RouterLink>

          <div v-if="authService.authenticated()">
            <user-infos/>
          </div>

          <div v-if="authService.authenticated()">
            <p><RouterLink to="/" @click="authService.logout()" class="px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Logout</RouterLink></p>
          </div>
          <div v-else class="ml-10 flex space-x-4">
            <RouterLink to="/login" class="px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</RouterLink>
            <RouterLink to="/register" class="px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Register</RouterLink>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <div v-if="authService.authenticated()">
      <menu-zones v-if="authService.authenticated()"/>
      <RouterView :key="$route.fullPath"/>
  </div>
  <div v-else>
    <RouterView :key="$route.fullPath"/>
  </div>
</template>

<style scoped>
</style>
