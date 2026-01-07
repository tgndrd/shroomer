<script setup lang="ts">
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import CostComponent from "@/components/CostComponent.vue";
import authService from "@/services/auth.service.ts";
import userInfos from "@/services/user.infos.ts";

const genuses = ref([])
const route = useRoute()

onMounted(async () => {
  genuses.value = await authService.get('/api/tree_genuses_enums')
    .then(response => response.data)
    .then(data => {
      return data.member
    })
})

async function addATree(genus: string) {
  await authService.post('/api/trees', {
    'genus': genus,
    'zone': 'api/zones/'+ route.params.id
  }).then(() => userInfos.refresh())
}
</script>

<template>
  <div v-if="genuses" class="flex h-16 items-center flex place-content-center">
    <button @click="addATree(genus['@id'])"
            v-for="genus in genuses"
            :key="genus['@id']"
            :disabled="!userInfos.affordable(genus['cost'])"
            class="px-3 py-2 text-sm font-medium text-gray-300 hover:not-disabled:bg-gray-700 hover:not-disabled:text-white cursor-pointer disabled:cursor-default disabled:opacity-75"
    >
      Add a {{genus['name']}}
      <CostComponent :cost="genus['cost']" />
    </button>
  </div>
</template>

<style scoped>

</style>
