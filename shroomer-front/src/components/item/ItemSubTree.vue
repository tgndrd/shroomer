<script setup lang="ts">
import { onBeforeMount, onUnmounted, ref } from 'vue'
import SubTreeAsciinator from "@/asciinator/sub.tree.asciinator.ts";
import WeatherAsciinator from "@/asciinator/weather.asciinator.ts";

const props = defineProps({
  subtree: {
    genus: {type: String, required: true},
    size: {type: Number, required: true}
  },
  weatherState: {type: String, required: true}
})

const template = ref('')
let interval = setInterval(() => {}, 10000)

function refresh() {
  let usedtemplate = SubTreeAsciinator.prepareTemplate(props.subtree.size, props.subtree.genus)
  usedtemplate = WeatherAsciinator.prepareWeather(usedtemplate, props.weatherState)
  template.value = usedtemplate

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

<template><pre class="inline-block text-gray-300" v-html="template"></pre></template>

<style>
</style>
