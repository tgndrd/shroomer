<script setup lang="ts">
import {useRoute} from "vue-router";
import {onMounted, onUnmounted, ref} from "vue";
import authService from "@/services/auth.service.ts";
import TreeAdd from "@/components/TreeAdd.vue";
import ItemTree from "@/components/item/ItemTree.vue";

const route = useRoute()
const zone = ref({name: String, trees: {}})

onMounted(async () => {
  await refresh()
})

onUnmounted(() => clearInterval(interval))
const interval = setInterval(function () {
  refresh()
}, 5000)

async function refresh() {
  zone.value = await authService.get('/api/zones/'+route.params.id)
    .then(response => response.data)
    .then(data => {return data})
}
</script>

<template>
  <div v-if="zone" class="text-center">
    <TreeAdd :zone="zone"/>
    <p class="text-center text-gray-600">You are viewing {{zone.name}}</p>
    <div class="overflow-x-auto overscroll-x-auto whitespace-nowrap w-screen p-5">
      <item-tree v-for="tree in zone.trees" :tree="tree"/>
    </div>
  </div>
</template>

<style>
</style>
