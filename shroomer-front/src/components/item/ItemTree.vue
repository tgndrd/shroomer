<script setup lang="ts">
import { onBeforeMount, onUnmounted, ref } from 'vue'
import ItemSubtree from "@/components/item/ItemSubTree.vue";
import TreeAsciinator from "@/asciinator/tree.asciinator.ts";
import WeatherAsciinator from '@/asciinator/weather.asciinator.ts'

const props = defineProps({
  tree: {
    genus: {type: String, required: true},
    id: {type: Number, required: true},
    slot: {type: Number, required: true},
    letter: {type: String, required: true},
  },
  weatherState: {type: String, required: true}
})

const empty = { genus: 'empty', size: 10}
const template = ref('')
let interval = setInterval(() => {}, 10000)

function refresh() {
  let usedTemplate = TreeAsciinator.prepareTemplate(props.tree.slot, props.tree.id)
  usedTemplate = TreeAsciinator.prepareTrunk(usedTemplate)
  usedTemplate = TreeAsciinator.prepareLeaf(usedTemplate, props.tree.letter)
  usedTemplate = WeatherAsciinator.prepareWeather(usedTemplate, props.weatherState)
  template.value = usedTemplate

  clearInterval(interval)
  interval = setInterval(function() {
    refresh()
  }, WeatherAsciinator.getRefreshRate(props.weatherState))
}

onBeforeMount(() => {
  refresh()
})

onUnmounted(() => clearInterval(interval))
</script>

<template>
  <div class="inline-block" v-if="props.tree.slot == 0" >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
  </div>

  <div class="inline-block" v-else-if="props.tree.slot <= 2" >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_1 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_2 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
  </div>

  <div class="box-content inline-block" v-else >
    <pre class="text-gray-200" v-html="template"></pre>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_3 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_1 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'trunk', size: 10}" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_2 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="props.tree.slot_4 ?? empty" :weather-state="weatherState"/>
    <item-subtree :subtree="{genus: 'empty', size: 10}" :weather-state="weatherState"/>
  </div>
</template>

<style>
pre {
  font-size: 9px;
}
</style>
