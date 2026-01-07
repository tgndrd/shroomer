<script setup lang="ts">
import {onMounted, onUnmounted} from "vue";
import userInfos from "@/services/user.infos";

onUnmounted(() => clearInterval(interval))
const interval = setInterval(function () {
  userInfos.refresh()
}, 10000)

onMounted(async () => {
  await userInfos.refresh()
})
</script>

<template>
  <div class="hidden md:flex h-16 items-center justify-between">
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Resource Flora {{ userInfos.user.value['resourceFlora'] }}
    </div>
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Resource Fauna {{ userInfos.user.value['resourceFauna'] }}
    </div>
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Resource Entomofauna {{ userInfos.user.value['resourceEntomofauna'] }}
    </div>
  </div>
  <div class="md:hidden sm:flex">
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Flora {{ userInfos.user.value['resourceFlora'] }}
    </div>
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Fauna {{ userInfos.user.value['resourceFauna'] }}
    </div>
    <div class="px-3 py-2 text-sm font-medium text-gray-300">
      Entomo {{ userInfos.user.value['resourceEntomofauna'] }}
    </div>
  </div>
</template>

<style>
</style>
